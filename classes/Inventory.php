<?php
/**
 * Inventory Class - Handles inventory tracking and stock management
 * 
 * This class manages:
 * - Stock level tracking
 * - Inventory transactions (IN, OUT, ADJUSTMENT)
 * - Low stock alerts
 * - Stock history
 */

class Inventory {
    // Private variables
    private $conn;
    private $inventory_table = 'inventory';
    private $transaction_table = 'inventory_transactions';
    
    // Inventory properties
    public $inventory_id;
    public $product_id;
    public $current_stock;
    public $minimum_stock;
    public $maximum_stock;
    public $last_restock_date;
    
    /**
     * Constructor - Initialize database connection
     * 
     * @param object $db - Database connection
     */
    public function __construct($db) {
        $this->conn = $db;
    }
    
    /**
     * GET ALL INVENTORY
     * Retrieves all inventory records with product details
     * 
     * @return array - Inventory records or false
     */
    public function getAllInventory() {
        $query = "SELECT i.*, p.product_name, p.product_code, p.unit_price
                  FROM " . $this->inventory_table . " i
                  JOIN products p ON i.product_id = p.product_id
                  ORDER BY p.product_name ASC";
        
        $result = $this->conn->query($query);
        
        if ($result && $result->num_rows > 0) {
            return $result->fetch_all(MYSQLI_ASSOC);
        }
        return false;
    }
    
    /**
     * CREATE INVENTORY RECORD
     * Creates the initial stock record for a newly added product
     *
     * @param int $product_id - Product ID
     * @param int $current_stock - Starting stock quantity
     * @param int $minimum_stock - Reorder threshold
     * @param int $maximum_stock - Maximum stock capacity
     * @return bool - True if successful
     */
    public function createInventory($product_id, $current_stock = 0, $minimum_stock = 10, $maximum_stock = 100) {
        $query = "INSERT INTO " . $this->inventory_table .
                 " (product_id, current_stock, minimum_stock, maximum_stock, last_restock_date)
                  VALUES (?, ?, ?, ?, NOW())";

        $stmt = $this->conn->prepare($query);
        $stmt->bind_param('iiii', $product_id, $current_stock, $minimum_stock, $maximum_stock);

        return $stmt->execute();
    }

    /**
     * GET INVENTORY BY PRODUCT ID
     * 
     * @param int $product_id - Product ID
     * @return array - Inventory details or false
     */
    public function getInventoryByProductId($product_id) {
        $query = "SELECT i.*, p.product_name, p.product_code, p.unit_price
                  FROM " . $this->inventory_table . " i
                  JOIN products p ON i.product_id = p.product_id
                  WHERE i.product_id = ?";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param('i', $product_id);
        $stmt->execute();
        
        $result = $stmt->get_result();
        
        if ($result && $result->num_rows > 0) {
            return $result->fetch_assoc();
        }
        return false;
    }
    
    /**
     * ADD INVENTORY TRANSACTION
     * Records stock movements (IN, OUT, ADJUSTMENT)
     * 
     * @param int $product_id - Product ID
     * @param string $type - Transaction type (IN/OUT/ADJUSTMENT)
     * @param int $quantity - Quantity
     * @param string $notes - Transaction notes
     * @return bool - True if successful
     */
    public function addTransaction($product_id, $type, $quantity, $notes = '') {
        // Insert transaction record
        $query = "INSERT INTO " . $this->transaction_table . 
                 " (product_id, transaction_type, quantity, notes) 
                  VALUES (?, ?, ?, ?)";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param('isis', $product_id, $type, $quantity, $notes);
        
        if (!$stmt->execute()) {
            return false;
        }
        
        // Update stock level
        if ($type == 'IN') {
            $query = "UPDATE " . $this->inventory_table . 
                     " SET current_stock = current_stock + ?, last_restock_date = NOW() 
                      WHERE product_id = ?";
        } else if ($type == 'OUT') {
            $query = "UPDATE " . $this->inventory_table . 
                     " SET current_stock = current_stock - ? 
                      WHERE product_id = ?";
        } else if ($type == 'ADJUSTMENT') {
            $query = "UPDATE " . $this->inventory_table . 
                     " SET current_stock = ? WHERE product_id = ?";
        }
        
        $stmt = $this->conn->prepare($query);
        
        if ($type == 'ADJUSTMENT') {
            $stmt->bind_param('ii', $quantity, $product_id);
        } else {
            $stmt->bind_param('ii', $quantity, $product_id);
        }
        
        return $stmt->execute();
    }
    
    /**
     * GET LOW STOCK ITEMS
     * Returns items with stock below minimum level
     * 
     * @return array - Low stock items or false
     */
    public function getLowStockItems() {
        $query = "SELECT i.*, p.product_name, p.product_code, p.unit_price
                  FROM " . $this->inventory_table . " i
                  JOIN products p ON i.product_id = p.product_id
                  WHERE i.current_stock <= i.minimum_stock
                  ORDER BY i.current_stock ASC";
        
        $result = $this->conn->query($query);
        
        if ($result && $result->num_rows > 0) {
            return $result->fetch_all(MYSQLI_ASSOC);
        }
        return false;
    }
    
    /**
     * GET TRANSACTION HISTORY
     * Retrieves transaction history for a product
     * 
     * @param int $product_id - Product ID
     * @return array - Transaction history or false
     */
    public function getTransactionHistory($product_id) {
        $query = "SELECT it.*, p.product_name, p.product_code
                  FROM " . $this->transaction_table . " it
                  JOIN products p ON it.product_id = p.product_id
                  WHERE it.product_id = ?
                  ORDER BY it.transaction_date DESC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param('i', $product_id);
        $stmt->execute();
        
        $result = $stmt->get_result();
        
        if ($result && $result->num_rows > 0) {
            return $result->fetch_all(MYSQLI_ASSOC);
        }
        return false;
    }
    
    /**
     * UPDATE MINIMUM STOCK
     * 
     * @param int $product_id - Product ID
     * @param int $min_stock - New minimum stock level
     * @return bool - True if successful
     */
    public function updateMinimumStock($product_id, $min_stock) {
        $query = "UPDATE " . $this->inventory_table . 
                 " SET minimum_stock = ? WHERE product_id = ?";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param('ii', $min_stock, $product_id);
        
        return $stmt->execute();
    }
}

?>
