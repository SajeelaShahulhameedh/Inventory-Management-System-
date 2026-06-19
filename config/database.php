<?php
/**
 * Database Configuration File
 * 
 * This file contains database connection settings.
 * Update these values with your database credentials.
 */

// =============================================
// DATABASE CONNECTION DETAILS
// =============================================

// Database host (usually 'localhost' for local development)
define('DB_HOST', 'localhost');

// Database username (default is 'root' for XAMPP/WAMP)
define('DB_USER', 'root');

// Database password (usually empty for local development)
define('DB_PASS', '');

// Database name
define('DB_NAME', 'inventory_system');

// Database port (default is 3306)
define('DB_PORT', 3307);

// =============================================
// CREATE DATABASE CONNECTION
// =============================================

try {
    // Create MySQLi connection
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME, DB_PORT);
    
    // Check if connection was successful
    if ($conn->connect_error) {
        // If connection fails, show error message
        die('Database Connection Error: ' . $conn->connect_error);
    }
    
    // Set character set to UTF-8 for proper text encoding
    $conn->set_charset('utf8');
    
    // Optional: Display success message (remove in production)
    // echo "Database connected successfully!";
    
} catch (Exception $e) {
    // Catch any exceptions
    die('Error: ' . $e->getMessage());
}

?>
