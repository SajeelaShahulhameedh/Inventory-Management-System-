<?php
/**
 * REPORTS INDEX PAGE
 * Overview of all available reports
 */

require_once '../../config/database.php';
require_once '../../classes/Inventory.php';
require_once '../../classes/Product.php';
require_once '../../classes/Supplier.php';

$inventory = new Inventory($conn);
$product   = new Product($conn);
$supplier  = new Supplier($conn);

$allInventory  = $inventory->getAllInventory();
$lowStockItems = $inventory->getLowStockItems();
$allProducts   = $product->getAllProducts();
$allSuppliers  = $supplier->getAllSuppliers();

$totalProducts  = $allProducts  ? count($allProducts)  : 0;
$totalSuppliers = $allSuppliers ? count($allSuppliers) : 0;
$lowStockCount  = $lowStockItems ? count($lowStockItems) : 0;

$totalValue = 0;
if ($allInventory) {
    foreach ($allInventory as $item) {
        $totalValue += $item['current_stock'] * $item['unit_price'];
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reports - Inventory Management System</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../../assets/css/style.css">
</head>
<body>
    <header>
        <h1>📦 Inventory Management System</h1>
    </header>

    <nav>
        <ul>
            <li><a href="../../index.php">Dashboard</a></li>
            <li><a href="../products/list.php">Products</a></li>
            <li><a href="../inventory/list.php">Inventory</a></li>
            <li><a href="../suppliers/list.php">Suppliers</a></li>
            <li><a href="index.php" class="active">Reports</a></li>
        </ul>
    </nav>

    <div class="container">
        <div class="content">

            <h1>Reports</h1>
            <p class="text-muted">View and analyse your inventory data</p>

            <!-- SUMMARY STATS -->
            <div class="grid-3" style="margin: 20px 0;">
                <div class="stat-box">
                    <div class="stat-label">Total Products</div>
                    <div class="stat-number"><?php echo $totalProducts; ?></div>
                </div>
                <div class="stat-box" style="background: linear-gradient(135deg, #2ecc71, #27ae60);">
                    <div class="stat-label">Total Inventory Value</div>
                    <div class="stat-number">$<?php echo number_format($totalValue, 0); ?></div>
                </div>
                <div class="stat-box" style="background: linear-gradient(135deg, #e74c3c, #c0392b);">
                    <div class="stat-label">Low Stock Items</div>
                    <div class="stat-number"><?php echo $lowStockCount; ?></div>
                </div>
            </div>

            <!-- REPORT CARDS -->
            <h2 style="margin-top: 30px;">Available Reports</h2>
            <div class="grid-2" style="margin-top: 15px;">

                <div class="card">
                    <div class="card-title">📊 Inventory Stock Report</div>
                    <div class="card-body">
                        <p>See current stock levels, values, and status for all products.</p>
                    </div>
                    <div class="card-footer">
                        <a href="inventory-report.php" class="btn btn-primary">View Report</a>
                    </div>
                </div>

                <div class="card">
                    <div class="card-title">⚠️ Low Stock Report</div>
                    <div class="card-body">
                        <p>View all products that are below their minimum stock level.</p>
                        <?php if ($lowStockCount > 0): ?>
                            <p class="text-danger"><strong><?php echo $lowStockCount; ?> item(s) need restocking.</strong></p>
                        <?php else: ?>
                            <p class="text-success">All stock levels are healthy!</p>
                        <?php endif; ?>
                    </div>
                    <div class="card-footer">
                        <a href="../inventory/low-stock.php" class="btn btn-danger">View Report</a>
                    </div>
                </div>

                <div class="card">
                    <div class="card-title">📦 Products Summary</div>
                    <div class="card-body">
                        <p>Full list of all products with categories and supplier info.</p>
                    </div>
                    <div class="card-footer">
                        <a href="../products/list.php" class="btn btn-primary">View Products</a>
                    </div>
                </div>

                <div class="card">
                    <div class="card-title">🏢 Suppliers Summary</div>
                    <div class="card-body">
                        <p>Full list of all suppliers with contact details.</p>
                        <p class="text-muted"><?php echo $totalSuppliers; ?> supplier(s) registered.</p>
                    </div>
                    <div class="card-footer">
                        <a href="../suppliers/list.php" class="btn btn-primary">View Suppliers</a>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <footer>
        <p>&copy; 2024 Inventory Management System. All rights reserved.</p>
    </footer>

    <script src="../../assets/js/script.js"></script>
</body>
</html>