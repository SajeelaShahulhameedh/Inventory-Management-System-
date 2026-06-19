<?php
/**
 * INVENTORY LIST PAGE
 * Display all inventory stock levels
 */

require_once '../../config/database.php';
require_once '../../classes/Inventory.php';

$inventory = new Inventory($conn);

$allInventory = $inventory->getAllInventory();
$lowStockItems = $inventory->getLowStockItems();
$lowStockCount = $lowStockItems ? count($lowStockItems) : 0;

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventory - Inventory Management System</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
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
                    <h1>Inventory Stock Levels</h1>
                    <p class="text-muted">Track current stock for all products</p>
                </div>
                <div style="display: flex; gap: 10px;">
                    <a href="add-transaction.php" class="btn btn-primary">+ Add Stock Transaction</a>
                    <?php if ($lowStockCount > 0): ?>
                        <a href="low-stock.php" class="btn btn-danger">⚠️ Low Stock (<?php echo $lowStockCount; ?>)</a>
                    <?php endif; ?>
                </div>
            </div>

            <!-- INVENTORY TABLE -->
            <?php if ($allInventory): ?>
            <div style="overflow-x: auto;">
                <table>
                    <thead>
                        <tr>
                            <th>Product Name</th>
                            <th>Product Code</th>
                            <th>Current Stock</th>
                            <th>Min Level</th>
                            <th>Max Level</th>
                            <th>Unit Price</th>
                            <th>Stock Value</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($allInventory as $item): ?>
                        <?php
                            $stockValue = $item['current_stock'] * $item['unit_price'];
                            if ($item['current_stock'] <= 0) {
                                $status = 'Out of Stock';
                                $statusColor = '#e74c3c';
                                $statusBg = '#f8d7da';
                            } elseif ($item['current_stock'] <= $item['minimum_stock']) {
                                $status = 'Low Stock';
                                $statusColor = '#856404';
                                $statusBg = '#fff3cd';
                            } elseif ($item['current_stock'] >= $item['maximum_stock']) {
                                $status = 'Overstocked';
                                $statusColor = '#0c5460';
                                $statusBg = '#d1ecf1';
                            } else {
                                $status = 'Normal';
                                $statusColor = '#155724';
                                $statusBg = '#d4edda';
                            }
                        ?>
                        <tr>
                            <td><strong><?php echo htmlspecialchars($item['product_name']); ?></strong></td>
                            <td><code><?php echo htmlspecialchars($item['product_code']); ?></code></td>
                            <td><strong><?php echo $item['current_stock']; ?></strong></td>
                            <td><?php echo $item['minimum_stock']; ?></td>
                            <td><?php echo $item['maximum_stock']; ?></td>
                            <td>$<?php echo number_format($item['unit_price'], 2); ?></td>
                            <td>$<?php echo number_format($stockValue, 2); ?></td>
                            <td>
                                <span style="background-color: <?php echo $statusBg; ?>; color: <?php echo $statusColor; ?>; padding: 4px 8px; border-radius: 4px; font-size: 0.85em; font-weight: 600;">
                                    <?php echo $status; ?>
                                </span>
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <a href="add-transaction.php?product_id=<?php echo $item['product_id']; ?>" class="btn btn-small btn-primary">+ Stock</a>
                                    <a href="edit.php?product_id=<?php echo $item['product_id']; ?>" class="btn btn-small btn-warning">Edit</a>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php else: ?>
                <div class="alert alert-info">
                    <strong>No inventory records found.</strong>
                    <a href="../products/add.php">Add a product first</a> to start tracking inventory.
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