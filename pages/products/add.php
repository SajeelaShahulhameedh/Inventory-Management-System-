<?php
/**
 * ADD PRODUCT PAGE
 * Form to add a new product
 */

require_once '../../config/database.php';
require_once '../../classes/Product.php';

$product = new Product($conn);
$message = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $product->product_name = $_POST['product_name'] ?? '';
    $product->product_code = $_POST['product_code'] ?? '';
    $product->category_id = $_POST['category_id'] ?? 0;
    $product->supplier_id = $_POST['supplier_id'] ?? 0;
    $product->unit_price = $_POST['unit_price'] ?? 0;
    $product->description = $_POST['description'] ?? '';
    $product->image_url = $_POST['image_url'] ?? '';

    if ($product->addProduct()) {
        $message = '<div class="alert alert-success">✓ Product added successfully!</div>';
        // Redirect after 2 seconds
        header("refresh:2;url=list.php");
    } else {
        $message = '<div class="alert alert-danger">✗ Error adding product. Please try again.</div>';
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product - Inventory Management System</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
</head>
<body>
    <!-- HEADER -->
    <header>
        <h1>📦 Inventory Management System</h1>
    </header>

    <!-- NAVIGATION -->
    <nav>
        <ul>
            <li><a href="../../index.php">Dashboard</a></li>
            <li><a href="list.php" class="active">Products</a></li>
            <li><a href="../../pages/inventory/list.php">Inventory</a></li>
            <li><a href="../../pages/suppliers/list.php">Suppliers</a></li>
            <li><a href="../../pages/reports/index.php">Reports</a></li>
        </ul>
    </nav>

    <!-- MAIN CONTAINER -->
    <div class="container">
        <div class="content">
            <!-- Alert Container -->
            <div id="alert-container">
                <?php echo $message; ?>
            </div>

            <!-- PAGE TITLE -->
            <h1>Add New Product</h1>
            <p class="text-muted">Fill in the form below to add a new product to your inventory</p>

            <!-- ADD PRODUCT FORM -->
            <form method="POST" id="addProductForm" onsubmit="return validateProductForm()">
                
                <div class="form-row">
                    <!-- Product Name -->
                    <div class="form-group">
                        <label for="product_name">Product Name *</label>
                        <input 
                            type="text" 
                            id="product_name" 
                            name="product_name" 
                            placeholder="Enter product name"
                            required
                        >
                    </div>

                    <!-- Product Code -->
                    <div class="form-group">
                        <label for="product_code">Product Code *</label>
                        <input 
                            type="text" 
                            id="product_code" 
                            name="product_code" 
                            placeholder="e.g., PROD-001"
                            required
                        >
                    </div>
                </div>

                <div class="form-row">
                    <!-- Category -->
                    <div class="form-group">
                        <label for="category_id">Category *</label>
                        <select id="category_id" name="category_id" required>
                            <option value="">-- Select Category --</option>
                            <option value="1">Electronics</option>
                            <option value="2">Furniture</option>
                            <option value="3">Office Supplies</option>
                            <option value="4">Tools</option>
                            <option value="5">Other</option>
                        </select>
                    </div>

                    <!-- Supplier -->
                    <div class="form-group">
                        <label for="supplier_id">Supplier *</label>
                        <select id="supplier_id" name="supplier_id" required>
                            <option value="">-- Select Supplier --</option>
                            <option value="1">Supplier 1</option>
                            <option value="2">Supplier 2</option>
                            <option value="3">Supplier 3</option>
                        </select>
                    </div>
                </div>

                <div class="form-row">
                    <!-- Unit Price -->
                    <div class="form-group">
                        <label for="unit_price">Unit Price ($) *</label>
                        <input 
                            type="number" 
                            id="unit_price" 
                            name="unit_price" 
                            placeholder="0.00"
                            step="0.01"
                            min="0"
                            required
                        >
                    </div>

                    <!-- Image URL -->
                    <div class="form-group">
                        <label for="image_url">Image URL</label>
                        <input 
                            type="text" 
                            id="image_url" 
                            name="image_url" 
                            placeholder="https://example.com/image.jpg"
                        >
                    </div>
                </div>

                <!-- Description -->
                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea 
                        id="description" 
                        name="description" 
                        placeholder="Enter product description..."
                    ></textarea>
                </div>

                <!-- Form Actions -->
                <div style="display: flex; gap: 10px; margin-top: 30px;">
                    <button type="submit" class="btn btn-primary">Add Product</button>
                    <a href="list.php" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>

    <!-- FOOTER -->
    <footer>
        <p>&copy; 2024 Inventory Management System. All rights reserved.</p>
    </footer>

    <!-- SCRIPTS -->
    <script src="../../assets/js/script.js"></script>
</body>
</html>
