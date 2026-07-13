<?php
/**
 * Product Class - Handles all product-related operations
 * 
 * This class manages:
 * - Adding new products
 * - Retrieving product information
 * - Updating product details
 * - Deleting products
 */

class Product {
    // Private variables to store database connection
    private $conn;
    private $table = 'products';
    
    // Product properties
    public $product_id;
    public $product_name;
    public $product_code;
    public $category_id;
    public $supplier_id;
    public $unit_price;
    public $description;
    public $image_url;
    
    /**
     * Constructor - Initialize database connection
     * 
     * @param object $db - Database connection
     */
    public function __construct($db) {
        $this->conn = $db;
    }
    
    /**
     * GET ALL PRODUCTS
     * Retrieves all products from the database
     * 
     * @return array - Array of products or false if error
     */
    public function getAllProducts() {
        $query = "SELECT p.*, c.category_name, s.supplier_name,
                         i.current_stock, i.minimum_stock, i.maximum_stock
                  FROM " . $this->table . " p
                  LEFT JOIN categories c ON p.category_id = c.category_id
                  LEFT JOIN suppliers s ON p.supplier_id = s.supplier_id
                  LEFT JOIN inventory i ON p.product_id = i.product_id
                  ORDER BY c.category_name ASC, p.product_name ASC";
        
        $result = $this->conn->query($query);
        
        if ($result && $result->num_rows > 0) {
            return $result->fetch_all(MYSQLI_ASSOC);
        }
        return false;
    }
    
    /**
     * GET PRODUCT BY ID
     * Retrieves a specific product using its ID
     * 
     * @param int $id - Product ID
     * @return array - Product details or false if not found
     */
    public function getProductById($id) {
        $query = "SELECT p.*, c.category_name, s.supplier_name 
                  FROM " . $this->table . " p
                  LEFT JOIN categories c ON p.category_id = c.category_id
                  LEFT JOIN suppliers s ON p.supplier_id = s.supplier_id
                  WHERE p.product_id = ?";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param('i', $id);
        $stmt->execute();
        
        $result = $stmt->get_result();
        
        if ($result && $result->num_rows > 0) {
            return $result->fetch_assoc();
        }
        return false;
    }
    
    /**
     * ADD NEW PRODUCT
     * Inserts a new product into the database
     * 
     * @return bool - True if successful, false otherwise
     */
    public function addProduct() {
        // Check for duplicate product code
        $query = "SELECT product_id FROM " . $this->table . " WHERE product_code = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param('s', $this->product_code);
        $stmt->execute();
        
        if ($stmt->get_result()->num_rows > 0) {
            return false; // Product code already exists
        }
        
        // Insert new product
        $query = "INSERT INTO " . $this->table . 
                 " (product_name, product_code, category_id, supplier_id, unit_price, description, image_url) 
                  VALUES (?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = $this->conn->prepare($query);
        
        if (!$stmt) {
            return false;
        }
        
        $stmt->bind_param('ssiidss', 
            $this->product_name,
            $this->product_code,
            $this->category_id,
            $this->supplier_id,
            $this->unit_price,
            $this->description,
            $this->image_url
        );
        
        $success = $stmt->execute();

        if ($success) {
            $this->product_id = $this->conn->insert_id;
        }

        return $success;
    }

    /**
     * UPDATE PRODUCT
     * Updates an existing product's information
     * 
     * @return bool - True if successful, false otherwise
     */
    public function updateProduct() {
        $query = "UPDATE " . $this->table . 
                 " SET product_name = ?, product_code = ?, category_id = ?, supplier_id = ?, 
                       unit_price = ?, description = ?, image_url = ? 
                   WHERE product_id = ?";
        
        $stmt = $this->conn->prepare($query);
        
        if (!$stmt) {
            return false;
        }
        
        // Correct parameter types: s = string, i = integer, d = double
        $stmt->bind_param('ssiidssi',
            $this->product_name,
            $this->product_code,
            $this->category_id,
            $this->supplier_id,
            $this->unit_price,
            $this->description,
            $this->image_url,
            $this->product_id
        );
        
        return $stmt->execute();
    }
    
    /**
     * DELETE PRODUCT
     * Deletes a product and related inventory records from the database
     * 
     * @param int $id - Product ID
     * @return bool - True if successful, false otherwise
     */
    public function deleteProduct($id) {
        // Use a transaction to remove dependent rows first to satisfy foreign keys
        $this->conn->begin_transaction();
        try {
            // Delete inventory transactions
            $q1 = $this->conn->prepare("DELETE FROM inventory_transactions WHERE product_id = ?");
            $q1->bind_param('i', $id);
            $q1->execute();

            // Delete inventory
            $q2 = $this->conn->prepare("DELETE FROM inventory WHERE product_id = ?");
            $q2->bind_param('i', $id);
            $q2->execute();

            // Delete product
            $q3 = $this->conn->prepare("DELETE FROM " . $this->table . " WHERE product_id = ?");
            $q3->bind_param('i', $id);
            $q3->execute();

            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            $this->conn->rollback();
            error_log('Delete product failed: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * SEARCH PRODUCTS
     * Searches for products by name or code
     * 
     * @param string $keyword - Search keyword
     * @return array - Matching products or false
     */
    public function searchProducts($keyword) {
        $query = "SELECT p.*, c.category_name, s.supplier_name,
                         i.current_stock, i.minimum_stock, i.maximum_stock
                  FROM " . $this->table . " p
                  LEFT JOIN categories c ON p.category_id = c.category_id
                  LEFT JOIN suppliers s ON p.supplier_id = s.supplier_id
                  LEFT JOIN inventory i ON p.product_id = i.product_id
                  WHERE p.product_name LIKE ? OR p.product_code LIKE ?
                  ORDER BY p.product_name ASC";
        
        $keyword = '%' . $keyword . '%';
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param('ss', $keyword, $keyword);
        $stmt->execute();
        
        $result = $stmt->get_result();
        
        if ($result && $result->num_rows > 0) {
            return $result->fetch_all(MYSQLI_ASSOC);
        }
        return false;
    }
}

?>
