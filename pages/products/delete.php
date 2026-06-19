<?php
/**
 * DELETE PRODUCT PAGE
 * Handle product deletion with confirmation
 */

require_once '../../config/database.php';
require_once '../../classes/Product.php';

$product = new Product($conn);
$product_id = $_GET['id'] ?? 0;

if ($product_id <= 0) {
    header("Location: list.php");
    exit;
}

$productData = $product->getProductById($product_id);

if (!$productData) {
    header("Location: list.php");
    exit;
}

// Handle deletion
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['confirm_delete'])) {
    if ($product->deleteProduct($product_id)) {
        header("Location: list.php?message=deleted");
        exit;
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Product - Inventory Management System</title>
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
            <!-- WARNING ALERT -->
            <div class="alert alert-danger">
                <strong>⚠️ Warning!</strong> You are about to delete a product. This action cannot be undone.
            </div>

            <!-- PRODUCT INFO -->
            <div class="card" style="margin: 20px 0;">
                <div class="card-title">Product to Delete</div>
                <div class="card-body">
                    <p><strong>Name:</strong> <?php echo htmlspecialchars($productData['product_name']); ?></p>
                    <p><strong>Code:</strong> <code><?php echo htmlspecialchars($productData['product_code']); ?></code></p>
                    <p><strong>Price:</strong> $<?php echo number_format($productData['unit_price'], 2); ?></p>
                </div>
            </div>

            <!-- CONFIRMATION FORM -->
            <form method="POST" style="margin: 30px 0;">
                <div style="background-color: #fff3cd; border-left: 4px solid #f39c12; padding: 15px; border-radius: 5px; margin: 20px 0;">
                    <p style="margin: 0;">
                        <strong>Are you sure you want to delete this product?</strong><br>
                        This will also affect related inventory records.
                    </p>
                </div>

                <div style="display: flex; gap: 10px; margin-top: 30px;">
                    <button type="submit" name="confirm_delete" value="1" class="btn btn-danger">
                        Yes, Delete Product
                    </button>
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
