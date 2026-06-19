<?php
/**
 * EDIT PRODUCT PAGE
 * Form to edit product details
 */

require_once '../../config/database.php';
require_once '../../classes/Product.php';

$product = new Product($conn);
$product_id = $_GET['id'] ?? 0;
$message = '';

if ($product_id <= 0) {
    header("Location: list.php");
    exit;
}

$productData = $product->getProductById($product_id);

if (!$productData) {
    header("Location: list.php");
    exit;
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $product->product_id = $product_id;
    $product->product_name = $_POST['product_name'] ?? '';
    $product->product_code = $_POST['product_code'] ?? '';
    $product->category_id = $_POST['category_id'] ?? 0;
    $product->supplier_id = $_POST['supplier_id'] ?? 0;
    $product->unit_price = $_POST['unit_price'] ?? 0;
    $product->description = $_POST['description'] ?? '';
    $product->image_url = $_POST['image_url'] ?? '';

    if ($product->updateProduct()) {
        $message = '<div class="alert alert-success">✓ Product updated successfully!</div>';
        $productData = $product->getProductById($product_id);
    } else {
        $message = '<div class="alert alert-danger">✗ Error updating product. Please try again.</div>';
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product - Inventory Management System</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
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
            <h1>Edit Product</h1>
            <p class="text-muted">Update product information</p>

            <!-- EDIT PRODUCT FORM -->
            <form method="POST" id="editProductForm" onsubmit="return validateProductForm()">
                
                <div class="form-row">
                    <!-- Product Name -->
                    <div class="form-group">
                        <label for="product_name">Product Name *</label>
                        <input 
                            type="text" 
                            id="product_name" 
                            name="product_name" 
                            value="<?php echo htmlspecialchars($productData['product_name']); ?>"
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
                            value="<?php echo htmlspecialchars($productData['product_code']); ?>"
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
                            <option value="1" <?php echo $productData['category_id'] == 1 ? 'selected' : ''; ?>>Electronics</option>
                            <option value="2" <?php echo $productData['category_id'] == 2 ? 'selected' : ''; ?>>Furniture</option>
                            <option value="3" <?php echo $productData['category_id'] == 3 ? 'selected' : ''; ?>>Office Supplies</option>
                            <option value="4" <?php echo $productData['category_id'] == 4 ? 'selected' : ''; ?>>Tools</option>
                            <option value="5" <?php echo $productData['category_id'] == 5 ? 'selected' : ''; ?>>Other</option>
                        </select>
                    </div>

                    <!-- Supplier -->
                    <div class="form-group">
                        <label for="supplier_id">Supplier *</label>
                        <select id="supplier_id" name="supplier_id" required>
                            <option value="">-- Select Supplier --</option>
                            <option value="1" <?php echo $productData['supplier_id'] == 1 ? 'selected' : ''; ?>>Supplier 1</option>
                            <option value="2" <?php echo $productData['supplier_id'] == 2 ? 'selected' : ''; ?>>Supplier 2</option>
                            <option value="3" <?php echo $productData['supplier_id'] == 3 ? 'selected' : ''; ?>>Supplier 3</option>
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
                            value="<?php echo htmlspecialchars($productData['unit_price']); ?>"
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
                            value="<?php echo htmlspecialchars($productData['image_url'] ?? ''); ?>"
                        >
                    </div>
                </div>

                <!-- Description -->
                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea 
                        id="description" 
                        name="description"
                    ><?php echo htmlspecialchars($productData['description'] ?? ''); ?></textarea>
                </div>

                <!-- Form Actions -->
                <div style="display: flex; gap: 10px; margin-top: 30px;">
                    <button type="submit" class="btn btn-primary">Update Product</button>
                    <a href="view.php?id=<?php echo $product_id; ?>" class="btn btn-secondary">Cancel</a>
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
