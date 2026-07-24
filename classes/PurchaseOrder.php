<?php
/**
 * PurchaseOrder Class - Handles restocking orders placed with suppliers
 *
 * A purchase order records that a certain quantity of a product has been
 * ordered from a supplier, with an expected delivery date. Its status
 * moves through PENDING -> RECEIVED (stock is added automatically) or
 * PENDING -> CANCELLED.
 */

class PurchaseOrder {
    // Private variables
    private $conn;
    private $table = 'purchase_orders';

    // Purchase order properties
    public $order_id;
    public $product_id;
    public $supplier_id;
    public $quantity;
    public $expected_delivery;
    public $status;

    /**
     * Constructor - Initialize database connection
     *
     * @param object $db - Database connection
     */
    public function __construct($db) {
        $this->conn = $db;
    }

    /**
     * GET ALL PURCHASE ORDERS
     * Retrieves every order with product/supplier names attached,
     * most recent first.
     *
     * @return array - Purchase order records
     */
    public function getAll() {
        $query = "SELECT po.*, p.product_name, p.product_code, s.supplier_name
                  FROM " . $this->table . " po
                  JOIN products p ON po.product_id = p.product_id
                  JOIN suppliers s ON po.supplier_id = s.supplier_id
                  ORDER BY po.order_date DESC";

        $result = $this->conn->query($query);
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

    /**
     * GET PURCHASE ORDER BY ID
     *
     * @param int $id - Order ID
     * @return array - Order details or false if not found
     */
    public function getById($id) {
        $query = "SELECT po.*, p.product_name, p.product_code, s.supplier_name
                  FROM " . $this->table . " po
                  JOIN products p ON po.product_id = p.product_id
                  JOIN suppliers s ON po.supplier_id = s.supplier_id
                  WHERE po.order_id = ?";

        $stmt = $this->conn->prepare($query);
        $stmt->bind_param('i', $id);
        $stmt->execute();

        $result = $stmt->get_result();
        return ($result && $result->num_rows > 0) ? $result->fetch_assoc() : false;
    }

    /**
     * CREATE PURCHASE ORDER
     * Places a new restock order, always starting as PENDING.
     *
     * @return bool - True if successful
     */
    public function create() {
        $query = "INSERT INTO " . $this->table . "
                  (product_id, supplier_id, quantity, expected_delivery, status)
                  VALUES (?, ?, ?, ?, 'PENDING')";

        $stmt = $this->conn->prepare($query);
        $stmt->bind_param(
            'iiis',
            $this->product_id,
            $this->supplier_id,
            $this->quantity,
            $this->expected_delivery
        );

        return $stmt->execute();
    }

    /**
     * UPDATE STATUS
     * Moves an order to RECEIVED or CANCELLED. Only orders that are
     * still PENDING can be updated, so an order can't be received twice
     * or un-cancelled by resubmitting an old form.
     *
     * @param int $id - Order ID
     * @param string $status - 'RECEIVED' or 'CANCELLED'
     * @return bool - True if the update actually changed a row
     */
    public function updateStatus($id, $status) {
        $query = "UPDATE " . $this->table . "
                  SET status = ? WHERE order_id = ? AND status = 'PENDING'";

        $stmt = $this->conn->prepare($query);
        $stmt->bind_param('si', $status, $id);
        $stmt->execute();

        return $stmt->affected_rows > 0;
    }

    /**
     * COUNT PENDING ORDERS
     * Used for a dashboard/nav badge showing outstanding orders.
     *
     * @return int
     */
    public function countPending() {
        $query = "SELECT COUNT(*) AS total FROM " . $this->table . " WHERE status = 'PENDING'";
        $result = $this->conn->query($query);
        $row = $result ? $result->fetch_assoc() : null;
        return $row ? (int)$row['total'] : 0;
    }
}
