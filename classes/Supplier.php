<?php
/**
 * Supplier Class - Handles supplier management
 * 
 * This class manages:
 * - Adding new suppliers
 * - Viewing supplier information
 * - Updating supplier details
 * - Deleting suppliers
 */

class Supplier {
    // Private variables
    private $conn;
    private $table = 'suppliers';
    
    // Supplier properties
    public $supplier_id;
    public $supplier_name;
    public $contact_person;
    public $email;
    public $phone;
    public $address;
    public $city;
    public $state;
    public $zip_code;
    
    /**
     * Constructor - Initialize database connection
     * 
     * @param object $db - Database connection
     */
    public function __construct($db) {
        $this->conn = $db;
    }
    
    /**
     * GET ALL SUPPLIERS
     * Retrieves all suppliers from the database
     * 
     * @return array - Array of suppliers or false
     */
    public function getAllSuppliers() {
        $query = "SELECT * FROM " . $this->table . " ORDER BY supplier_name ASC";
        
        $result = $this->conn->query($query);
        
        if ($result && $result->num_rows > 0) {
            return $result->fetch_all(MYSQLI_ASSOC);
        }
        return false;
    }
    
    /**
     * GET SUPPLIER BY ID
     * 
     * @param int $id - Supplier ID
     * @return array - Supplier details or false
     */
    public function getSupplierById($id) {
        $query = "SELECT * FROM " . $this->table . " WHERE supplier_id = ?";
        
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
     * ADD NEW SUPPLIER
     * 
     * @return bool - True if successful, false otherwise
     */
    public function addSupplier() {
        $query = "INSERT INTO " . $this->table . 
                 " (supplier_name, contact_person, email, phone, address, city, state, zip_code) 
                  VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = $this->conn->prepare($query);
        
        if (!$stmt) {
            return false;
        }
        
        $stmt->bind_param('ssssssss',
            $this->supplier_name,
            $this->contact_person,
            $this->email,
            $this->phone,
            $this->address,
            $this->city,
            $this->state,
            $this->zip_code
        );
        
        return $stmt->execute();
    }
    
    /**
     * UPDATE SUPPLIER
     * 
     * @return bool - True if successful, false otherwise
     */
    public function updateSupplier() {
        $query = "UPDATE " . $this->table . 
                 " SET supplier_name = ?, contact_person = ?, email = ?, phone = ?, 
                       address = ?, city = ?, state = ?, zip_code = ? 
                  WHERE supplier_id = ?";
        
        $stmt = $this->conn->prepare($query);
        
        if (!$stmt) {
            return false;
        }
        
        $stmt->bind_param('ssssssssi',
            $this->supplier_name,
            $this->contact_person,
            $this->email,
            $this->phone,
            $this->address,
            $this->city,
            $this->state,
            $this->zip_code,
            $this->supplier_id
        );
        
        return $stmt->execute();
    }
    
    /**
     * DELETE SUPPLIER
     * 
     * @param int $id - Supplier ID
     * @return bool - True if successful, false otherwise
     */
    public function deleteSupplier($id) {
        $query = "DELETE FROM " . $this->table . " WHERE supplier_id = ?";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param('i', $id);
        
        return $stmt->execute();
    }
    
    /**
     * SEARCH SUPPLIERS
     * 
     * @param string $keyword - Search keyword
     * @return array - Matching suppliers or false
     */
    public function searchSuppliers($keyword) {
        $query = "SELECT * FROM " . $this->table . 
                 " WHERE supplier_name LIKE ? OR email LIKE ? OR phone LIKE ?
                  ORDER BY supplier_name ASC";
        
        $keyword = '%' . $keyword . '%';
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param('sss', $keyword, $keyword, $keyword);
        $stmt->execute();
        
        $result = $stmt->get_result();
        
        if ($result && $result->num_rows > 0) {
            return $result->fetch_all(MYSQLI_ASSOC);
        }
        return false;
    }
}

?>
