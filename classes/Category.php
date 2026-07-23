<?php
class Category {
    private $conn;
    private $table = 'categories';
    public $category_id;
    public $category_name;
    public $description;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getAll() {
        $query = "SELECT category_id, category_name, description, created_at FROM {$this->table} ORDER BY category_name ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $res = $stmt->get_result();
        return $res ? $res->fetch_all(MYSQLI_ASSOC) : [];
    }

    public function getById($id) {
        $query = "SELECT * FROM {$this->table} WHERE category_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $res = $stmt->get_result();
        return $res && $res->num_rows ? $res->fetch_assoc() : false;
    }

    public function add() {
        // duplicate check
        $query = "SELECT category_id FROM {$this->table} WHERE category_name = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param('s', $this->category_name);
        $stmt->execute();
        if ($stmt->get_result()->num_rows > 0) { return false; }

        $query = "INSERT INTO {$this->table} (category_name, description) VALUES (?, ?)";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param('ss', $this->category_name, $this->description);
        if ($stmt->execute()) { $this->category_id = $this->conn->insert_id; return true; }
        return false;
    }

    public function update() {
        $query = "UPDATE {$this->table} SET category_name = ?, description = ? WHERE category_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param('ssi', $this->category_name, $this->description, $this->category_id);
        return $stmt->execute();
    }

    public function delete($id) {
        // prevent deletion if products exist
        $q = "SELECT product_id FROM products WHERE category_id = ? LIMIT 1";
        $s = $this->conn->prepare($q);
        $s->bind_param('i', $id);
        $s->execute();
        if ($s->get_result()->num_rows > 0) { return false; }

        $query = "DELETE FROM {$this->table} WHERE category_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param('i', $id);
        return $stmt->execute();
    }
}
