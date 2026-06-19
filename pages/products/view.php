<?php
/**
 * VIEW PRODUCT PAGE
 * Display detailed product information
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

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($productData['product_name']); ?> - Inventory Management System</title>
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
            <!-- PAGE TITLE -->
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1><?php echo htmlspecialchars($productData['product_name']); ?></h1>
                    <p class="text-muted">Product Code: <?php echo htmlspecialchars($productData['product_code']); ?></p>
                </div>
                <div>
                    <a href="edit.php?id=<?php echo $productData['product_id']; ?>" class="btn btn-warning">Edit</a>
                    <a href="list.php" class="btn btn-secondary">Back</a>
                </div>
            </div>

            <!-- PRODUCT DETAILS -->
            <div class="grid-2">
                <!-- Left Column -->
                <div class="card">
                    <div class="card-title">Product Information</div>
                    <div class="card-body">
                        <p><strong>Name:</strong> <?php echo htmlspecialchars($productData['product_name']); ?></p>
                        <p><strong>Code:</strong> <code><?php echo htmlspecialchars($productData['product_code']); ?></code></p>
                        <p><strong>Category:</strong> <?php echo htmlspecialchars($productData['category_name'] ?? 'N/A'); ?></p>
                        <p><strong>Supplier:</strong> <?php echo htmlspecialchars($productData['supplier_name'] ?? 'N/A'); ?></p>
                        <p><strong>Unit Price:</strong> $<?php echo number_format($productData['unit_price'], 2); ?></p>
                    </div>
                </div>

                <!-- Right Column -->
                <div class="card">
                    <div class="card-title">Description</div>
                    <div class="card-body">
                        <p><?php echo nl2br(htmlspecialchars($productData['description'] ?? 'No description provided')); ?></p>
                    </div>
                </div>
            </div>

            <!-- ACTION BUTTONS -->
            <div style="margin: 30px 0; display: flex; gap: 10px;">
                <a href="../../pages/inventory/add-transaction.php?product_id=<?php echo $productData['product_id']; ?>" class="btn btn-primary">Add Stock</a>
                <a href="edit.php?id=<?php echo $productData['product_id']; ?>" class="btn btn-warning">Edit Product</a>
                <a href="delete.php?id=<?php echo $productData['product_id']; ?>" class="btn btn-danger" onclick="return confirmDelete('<?php echo htmlspecialchars($productData['product_name']); ?>')">Delete Product</a>
            </div>
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
