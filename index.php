<?php
/**
 * INVENTORY MANAGEMENT SYSTEM - DASHBOARD
 * Main entry point and dashboard view
 */

// Include database configuration
require_once 'config/database.php';

// Include classes
require_once 'classes/Product.php';
require_once 'classes/Inventory.php';
require_once 'classes/Supplier.php';

// Initialize classes
$product = new Product($conn);
$inventory = new Inventory($conn);
$supplier = new Supplier($conn);

// Get dashboard statistics
$allProducts = $product->getAllProducts();
$totalProducts = $allProducts ? count($allProducts) : 0;

$allInventory = $inventory->getAllInventory();
$totalInventoryValue = 0;
$lowStockItems = $inventory->getLowStockItems();
$lowStockCount = $lowStockItems ? count($lowStockItems) : 0;

if ($allInventory) {
    foreach ($allInventory as $item) {
        $totalInventoryValue += ($item['current_stock'] * $item['unit_price']);
    }
}

$allSuppliers = $supplier->getAllSuppliers();
$totalSuppliers = $allSuppliers ? count($allSuppliers) : 0;

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventory Management System - Dashboard</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <!-- HEADER -->
    <header>
        <h1>📦 Inventory Management System</h1>
        <p style="margin: 0; font-size: 0.9em; opacity: 0.9;">Manage your inventory efficiently</p>
    </header>

    <!-- NAVIGATION -->
    <nav>
        <ul>
            <li><a href="index.php" class="active">Dashboard</a></li>
            <li><a href="pages/products/list.php">Products</a></li>
            <li><a href="pages/inventory/list.php">Inventory</a></li>
            <li><a href="pages/suppliers/list.php">Suppliers</a></li>
            <li><a href="pages/reports/index.php">Reports</a></li>
        </ul>
    </nav>

    <!-- MAIN CONTAINER -->
    <div class="container">
        <div class="content">
            <!-- Alert Container -->
            <div id="alert-container"></div>

            <!-- DASHBOARD TITLE -->
            <h1>Dashboard</h1>
            <p class="text-muted">Welcome to your inventory management dashboard. Here's an overview of your system.</p>

            <!-- STATISTICS ROW -->
            <div class="grid-3">
                <!-- Total Products Card -->
                <div class="stat-box" style="background: linear-gradient(135deg, #3498db, #5dade2);">
                    <div class="stat-label">Total Products</div>
                    <div class="stat-number"><?php echo $totalProducts; ?></div>
                    <a href="pages/products/list.php" style="color: white; text-decoration: none; font-size: 0.9em;">View Products →</a>
                </div>

                <!-- Low Stock Items Card -->
                <div class="stat-box" style="background: linear-gradient(135deg, #e74c3c, #ec7063);">
                    <div class="stat-label">Low Stock Items</div>
                    <div class="stat-number"><?php echo $lowStockCount; ?></div>
                    <a href="pages/inventory/low-stock.php" style="color: white; text-decoration: none; font-size: 0.9em;">View Low Stock →</a>
                </div>

                <!-- Total Suppliers Card -->
                <div class="stat-box" style="background: linear-gradient(135deg, #2ecc71, #58d68d);">
                    <div class="stat-label">Total Suppliers</div>
                    <div class="stat-number"><?php echo $totalSuppliers; ?></div>
                    <a href="pages/suppliers/list.php" style="color: white; text-decoration: none; font-size: 0.9em;">View Suppliers →</a>
                </div>
            </div>

            <!-- QUICK ACTIONS -->
            <div style="margin: 30px 0; padding: 20px; background-color: #f8f9fa; border-radius: 8px;">
                <h2 style="margin-top: 0;">Quick Actions</h2>
                <div class="d-flex gap-2" style="flex-wrap: wrap;">
                    <a href="pages/products/add.php" class="btn btn-primary">+ Add Product</a>
                    <a href="pages/suppliers/add.php" class="btn btn-primary">+ Add Supplier</a>
                    <a href="pages/inventory/add-transaction.php" class="btn btn-secondary">+ Add Stock</a>
                    <a href="pages/reports/inventory-report.php" class="btn btn-warning">Generate Report</a>
                </div>
            </div>

            <!-- LOW STOCK ITEMS SECTION -->
            <?php if ($lowStockItems): ?>
            <div style="margin: 30px 0;">
                <h2>⚠️ Low Stock Items</h2>
                <p class="text-muted">Items below minimum stock level</p>
                
                <table>
                    <thead>
                        <tr>
                            <th>Product Name</th>
                            <th>Product Code</th>
                            <th>Current Stock</th>
                            <th>Minimum Level</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach (array_slice($lowStockItems, 0, 5) as $item): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($item['product_name']); ?></td>
                            <td><?php echo htmlspecialchars($item['product_code']); ?></td>
                            <td><?php echo $item['current_stock']; ?></td>
                            <td><?php echo $item['minimum_stock']; ?></td>
                            <td>
                                <span style="background-color: #f8d7da; color: #721c24; padding: 4px 8px; border-radius: 4px; font-size: 0.85em;">
                                    Critical
                                </span>
                            </td>
                            <td>
                                <a href="pages/inventory/add-stock.php?product_id=<?php echo $item['product_id']; ?>" class="btn btn-small btn-warning">Restock</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>

                <?php if ($lowStockCount > 5): ?>
                <p style="text-align: center; margin-top: 15px;">
                    <a href="pages/inventory/low-stock.php" style="color: #3498db; text-decoration: none;">View all <?php echo $lowStockCount; ?> low stock items →</a>
                </p>
                <?php endif; ?>
            </div>
            <?php endif; ?>

            <!-- INVENTORY VALUE SECTION -->
            <div style="margin: 30px 0; padding: 20px; background: linear-gradient(135deg, #3498db, #5dade2); color: white; border-radius: 8px;">
                <h2 style="color: white; margin-top: 0;">Total Inventory Value</h2>
                <p style="font-size: 2em; font-weight: bold; margin: 10px 0;">
                    $<?php echo number_format($totalInventoryValue, 2); ?>
                </p>
                <p style="margin: 0; opacity: 0.9;">Based on current stock levels and unit prices</p>
            </div>

            <!-- RECENT PRODUCTS SECTION -->
            <?php if ($allProducts): ?>
            <div style="margin: 30px 0;">
                <h2>Recent Products</h2>
                <p class="text-muted">Latest products added to inventory</p>
                
                <table>
                    <thead>
                        <tr>
                            <th>Product Name</th>
                            <th>Code</th>
                            <th>Category</th>
                            <th>Supplier</th>
                            <th>Price</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach (array_slice($allProducts, 0, 5) as $prod): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($prod['product_name']); ?></td>
                            <td><?php echo htmlspecialchars($prod['product_code']); ?></td>
                            <td><?php echo htmlspecialchars($prod['category_name'] ?? 'N/A'); ?></td>
                            <td><?php echo htmlspecialchars($prod['supplier_name'] ?? 'N/A'); ?></td>
                            <td>$<?php echo number_format($prod['unit_price'], 2); ?></td>
                            <td>
                                <a href="pages/products/view.php?id=<?php echo $prod['product_id']; ?>" class="btn btn-small btn-primary">View</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php endif; ?>

            <!-- SYSTEM INFO -->
            <div style="margin-top: 40px; padding: 20px; background-color: #f8f9fa; border-radius: 8px; border-left: 4px solid #3498db;">
                <h3 style="margin-top: 0;">📊 System Information</h3>
                <p><strong>Total Products:</strong> <?php echo $totalProducts; ?></p>
                <p><strong>Total Suppliers:</strong> <?php echo $totalSuppliers; ?></p>
                <p><strong>Low Stock Items:</strong> <?php echo $lowStockCount; ?></p>
                <p><strong>Total Inventory Value:</strong> $<?php echo number_format($totalInventoryValue, 2); ?></p>
                <p class="text-muted" style="margin: 0;">Last updated: <?php echo date('Y-m-d H:i:s'); ?></p>
            </div>
        </div>
    </div>

    <!-- FOOTER -->
    <footer>
        <p>&copy; 2024 Inventory Management System. All rights reserved.</p>
        <p>Developed with ❤️ for efficient inventory management</p>
    </footer>

    <!-- SCRIPTS -->
    <script src="assets/js/script.js"></script>
</body>
</html>
