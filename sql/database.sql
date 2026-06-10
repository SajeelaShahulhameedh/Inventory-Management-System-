-- =============================================
-- INVENTORY MANAGEMENT SYSTEM DATABASE
-- =============================================

CREATE DATABASE IF NOT EXISTS inventory_system;
USE inventory_system;

-- =============================================
-- TABLE: categories (Product Categories)
-- =============================================
CREATE TABLE categories (
    category_id INT PRIMARY KEY AUTO_INCREMENT,
    category_name VARCHAR(100) NOT NULL UNIQUE,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- =============================================
-- TABLE: suppliers
-- =============================================
CREATE TABLE suppliers (
    supplier_id INT PRIMARY KEY AUTO_INCREMENT,
    supplier_name VARCHAR(100) NOT NULL,
    contact_person VARCHAR(100),
    email VARCHAR(100),
    phone VARCHAR(15),
    address TEXT,
    city VARCHAR(50),
    state VARCHAR(50),
    zip_code VARCHAR(10),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- =============================================
-- TABLE: products
-- =============================================
CREATE TABLE products (
    product_id INT PRIMARY KEY AUTO_INCREMENT,
    product_name VARCHAR(150) NOT NULL,
    product_code VARCHAR(50) NOT NULL UNIQUE,
    category_id INT NOT NULL,
    supplier_id INT NOT NULL,
    unit_price DECIMAL(10, 2) NOT NULL,
    description TEXT,
    image_url VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(category_id),
    FOREIGN KEY (supplier_id) REFERENCES suppliers(supplier_id)
);

-- =============================================
-- TABLE: inventory (Stock Tracking)
-- =============================================
CREATE TABLE inventory (
    inventory_id INT PRIMARY KEY AUTO_INCREMENT,
    product_id INT NOT NULL UNIQUE,
    current_stock INT NOT NULL DEFAULT 0,
    minimum_stock INT NOT NULL DEFAULT 10,
    maximum_stock INT NOT NULL DEFAULT 100,
    last_restock_date DATE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (product_id) REFERENCES products(product_id)
);

-- =============================================
-- TABLE: inventory_transactions (Stock Movements)
-- =============================================
CREATE TABLE inventory_transactions (
    transaction_id INT PRIMARY KEY AUTO_INCREMENT,
    product_id INT NOT NULL,
    transaction_type ENUM('IN', 'OUT', 'ADJUSTMENT') NOT NULL,
    quantity INT NOT NULL,
    notes TEXT,
    transaction_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (product_id) REFERENCES products(product_id)
);

-- =============================================
-- TABLE: purchase_orders (For restocking)
-- =============================================
CREATE TABLE purchase_orders (
    order_id INT PRIMARY KEY AUTO_INCREMENT,
    product_id INT NOT NULL,
    supplier_id INT NOT NULL,
    quantity INT NOT NULL,
    order_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    expected_delivery DATE,
    status ENUM('PENDING', 'RECEIVED', 'CANCELLED') DEFAULT 'PENDING',
    FOREIGN KEY (product_id) REFERENCES products(product_id),
    FOREIGN KEY (supplier_id) REFERENCES suppliers(supplier_id)
);

-- =============================================
-- Sample Data for Testing
-- =============================================

INSERT INTO categories (category_name, description) VALUES
('Electronics', 'Electronic devices and gadgets'),
('Clothing', 'Apparel and fashion items'),
('Books', 'Educational and recreational books'),
('Food & Beverages', 'Consumable food items');

INSERT INTO suppliers (supplier_name, contact_person, email, phone, address, city, state, zip_code) VALUES
('Tech Supplies Co', 'John Smith', 'john@techsupplies.com', '555-0101', '123 Tech Street', 'New York', 'NY', '10001'),
('Fashion World', 'Sarah Johnson', 'sarah@fashionworld.com', '555-0102', '456 Fashion Ave', 'Los Angeles', 'CA', '90001'),
('Book Distributors Inc', 'Mike Brown', 'mike@bookdist.com', '555-0103', '789 Book Lane', 'Chicago', 'IL', '60601');

INSERT INTO products (product_name, product_code, category_id, supplier_id, unit_price, description) VALUES
('Laptop', 'PROD001', 1, 1, 899.99, 'High-performance laptop'),
('T-Shirt', 'PROD002', 2, 2, 19.99, 'Cotton t-shirt'),
('Programming Book', 'PROD003', 3, 3, 45.00, 'Learn PHP & MySQL');

INSERT INTO inventory (product_id, current_stock, minimum_stock, maximum_stock) VALUES
(1, 25, 5, 50),
(2, 100, 20, 200),
(3, 50, 10, 100);
