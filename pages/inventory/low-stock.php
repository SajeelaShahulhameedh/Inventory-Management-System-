<?php
/**
 * LOW STOCK PAGE
 * Shows all items with stock below minimum level
 */

require_once '../../config/database.php';
require_once '../../classes/Inventory.php';

$inventory = new Inventory($conn);
$lowStockItems = $inventory->getLowStockItems();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Low Stock Alert - Inventory Management System</title>
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
            <li><a href="../products/list.php">Products</a></li>
            <li><a href="list.php" class="active">Inventory</a></li>
            <li><a href="../suppliers/list.php">Suppliers</a></li>
            <li><a href="../reports/index.php">Reports</a></li>
        </ul>
    </nav>

    <!-- MAIN CONTAINER -->
    <div class="container">
        <div class="content">
            <div id="alert-container"></div>

            <!-- PAGE TITLE -->
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1>⚠️ Low Stock Alert</h1>
                    <p class="text-muted">
                        <?php echo $lowStockItems ? count($lowStockItems) : 0; ?> item(s) are below the minimum stock level
                    </p>
                </div>
                <div style="display: flex; gap: 10px;">
                    <a href="add-transaction.php" class="btn btn-primary">+ Add Stock</a>
                    <a href="list.php" class="btn btn-secondary">← All Inventory</a>
                </div>
            </div>

            <?php if ($lowStockItems): ?>

                <!-- WARNING BANNER -->
                <div class="alert alert-warning">
                    <strong>⚠️ Attention Required!</strong> The following products are running low on stock. Please restock them as soon as possible to avoid shortages.
                </div>

                <div style="overflow-x: auto;">
                    <table>
                        <thead>
                            <tr>
                                <th>Product Name</th>
                                <th>Product Code</th>
                                <th>Current Stock</th>
                                <th>Minimum Level</th>
                                <th>Units Needed</th>
                                <th>Unit Price</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($lowStockItems as $item): ?>
                            <?php
                                $unitsNeeded = $item['minimum_stock'] - $item['current_stock'];
                                $isOutOfStock = $item['current_stock'] <= 0;
                            ?>
                            <tr>
                                <td><strong><?php echo htmlspecialchars($item['product_name']); ?></strong></td>
                                <td><code><?php echo htmlspecialchars($item['product_code']); ?></code></td>
                                <td>
                                    <strong style="color: <?php echo $isOutOfStock ? '#e74c3c' : '#f39c12'; ?>">
                                        <?php echo $item['current_stock']; ?>
                                    </strong>
                                </td>
                                <td><?php echo $item['minimum_stock']; ?></td>
                                <td>
                                    <span style="color: #e74c3c; font-weight: 600;">
                                        +<?php echo max(0, $unitsNeeded); ?>
                                    </span>
                                </td>
                                <td>$<?php echo number_format($item['unit_price'], 2); ?></td>
                                <td>
                                    <?php if ($isOutOfStock): ?>
                                        <span style="background-color: #f8d7da; color: #721c24; padding: 4px 8px; border-radius: 4px; font-size: 0.85em; font-weight: 600;">
                                            Out of Stock
                                        </span>
                                    <?php else: ?>
                                        <span style="background-color: #fff3cd; color: #856404; padding: 4px 8px; border-radius: 4px; font-size: 0.85em; font-weight: 600;">
                                            Low Stock
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <a href="add-transaction.php?product_id=<?php echo $item['product_id']; ?>" class="btn btn-small btn-warning">Restock</a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

            <?php else: ?>
                <div class="alert alert-success">
                    <strong>✅ All stock levels are healthy!</strong> No items are currently below the minimum stock level.
                    <a href="list.php" style="margin-left: 10px;">View All Inventory →</a>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- FOOTER -->
    <footer>
        <p>&copy; 2024 Inventory Management System. All rights reserved.</p>
    </footer>

    <script src="../../assets/js/script.js"></script>
</body>
</html>