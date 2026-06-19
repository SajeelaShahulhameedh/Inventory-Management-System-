<?php
/**
 * INVENTORY STOCK REPORT PAGE
 */

require_once '../../config/database.php';
require_once '../../classes/Inventory.php';

$inventory    = new Inventory($conn);
$allInventory = $inventory->getAllInventory();

$totalValue = 0;
$totalUnits = 0;
if ($allInventory) {
    foreach ($allInventory as $item) {
        $totalValue += $item['current_stock'] * $item['unit_price'];
        $totalUnits += $item['current_stock'];
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventory Report - Inventory Management System</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
    <style>
        @media print {
            header, nav, footer, .no-print { display: none; }
            body { background: white; }
            .container { padding: 0; }
        }
    </style>
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

            <!-- TITLE ROW -->
            <div class="d-flex justify-content-between align-items-center no-print">
                <div>
                    <h1>📊 Inventory Stock Report</h1>
                    <p class="text-muted">Generated on: <?php echo date('F j, Y  g:i A'); ?></p>
                </div>
                <div style="display: flex; gap: 10px;">
                    <button onclick="window.print()" class="btn btn-primary">🖨️ Print</button>
                    <a href="index.php" class="btn btn-secondary">← Back</a>
                </div>
            </div>

            <!-- SUMMARY BOXES -->
            <div class="grid-3" style="margin: 20px 0;">
                <div class="stat-box">
                    <div class="stat-label">Total Products</div>
                    <div class="stat-number"><?php echo $allInventory ? count($allInventory) : 0; ?></div>
                </div>
                <div class="stat-box" style="background: linear-gradient(135deg, #2ecc71, #27ae60);">
                    <div class="stat-label">Total Units in Stock</div>
                    <div class="stat-number"><?php echo number_format($totalUnits); ?></div>
                </div>
                <div class="stat-box" style="background: linear-gradient(135deg, #9b59b6, #8e44ad);">
                    <div class="stat-label">Total Stock Value</div>
                    <div class="stat-number">$<?php echo number_format($totalValue, 2); ?></div>
                </div>
            </div>

            <!-- STOCK TABLE -->
            <?php if ($allInventory): ?>
            <div style="overflow-x: auto;">
                <table>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Product Name</th>
                            <th>Code</th>
                            <th>Current Stock</th>
                            <th>Min Level</th>
                            <th>Max Level</th>
                            <th>Unit Price</th>
                            <th>Stock Value</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i = 1; foreach ($allInventory as $item): ?>
                        <?php
                            $stockValue = $item['current_stock'] * $item['unit_price'];
                            if ($item['current_stock'] <= 0) {
                                $status = 'Out of Stock';
                                $color  = '#721c24'; $bg = '#f8d7da';
                            } elseif ($item['current_stock'] <= $item['minimum_stock']) {
                                $status = 'Low Stock';
                                $color  = '#856404'; $bg = '#fff3cd';
                            } elseif ($item['current_stock'] >= $item['maximum_stock']) {
                                $status = 'Overstocked';
                                $color  = '#0c5460'; $bg = '#d1ecf1';
                            } else {
                                $status = 'Normal';
                                $color  = '#155724'; $bg = '#d4edda';
                            }
                        ?>
                        <tr>
                            <td><?php echo $i++; ?></td>
                            <td><strong><?php echo htmlspecialchars($item['product_name']); ?></strong></td>
                            <td><code><?php echo htmlspecialchars($item['product_code']); ?></code></td>
                            <td><strong><?php echo $item['current_stock']; ?></strong></td>
                            <td><?php echo $item['minimum_stock']; ?></td>
                            <td><?php echo $item['maximum_stock']; ?></td>
                            <td>$<?php echo number_format($item['unit_price'], 2); ?></td>
                            <td>$<?php echo number_format($stockValue, 2); ?></td>
                            <td>
                                <span style="background:<?php echo $bg; ?>; color:<?php echo $color; ?>; padding: 3px 8px; border-radius: 4px; font-size: 0.82em; font-weight: 600;">
                                    <?php echo $status; ?>
                                </span>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                        <tr style="background-color: #2c3e50; color: white; font-weight: bold;">
                            <td colspan="3">TOTAL</td>
                            <td><?php echo number_format($totalUnits); ?> units</td>
                            <td colspan="3"></td>
                            <td>$<?php echo number_format($totalValue, 2); ?></td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            <?php else: ?>
                <div class="alert alert-info">No inventory data found.</div>
            <?php endif; ?>

        </div>
    </div>

    <footer>
        <p>&copy; 2024 Inventory Management System. All rights reserved.</p>
    </footer>

    <script src="../../assets/js/script.js"></script>
</body>
</html>