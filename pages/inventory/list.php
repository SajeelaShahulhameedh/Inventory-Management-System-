<?php
require_once '../../config/database.php';
require_once '../../classes/Inventory.php';

$inventory = new Inventory($conn);
$allInventory = $inventory->getAllInventory();
$lowStockItems = $inventory->getLowStockItems();
$lowStockCount = $lowStockItems ? count($lowStockItems) : 0;

$pageTitle = 'Stock Levels'; $pageSubtitle = 'Current inventory stock for all products';
$activeMenu = 'inventory'; $cssPath = '../../assets/css/style.css';
$jsPath = '../../assets/js/script.js'; $basePath = '../../';
require_once '../../includes/layout.php';
?>

<div class="page-header">
    <div><h1>Stock Levels</h1><p>Monitor all product stock quantities</p></div>
    <div style="display:flex;gap:10px;">
        <a href="add-transaction.php" class="btn btn-primary"><?php echo icon('repeat', 15); ?> Stock Transaction</a>
        <?php if ($lowStockCount > 0): ?>
        <a href="low-stock.php" class="btn btn-danger"><?php echo icon('alert-triangle', 15); ?> Low Stock (<?php echo $lowStockCount; ?>)</a>
        <?php endif; ?>
    </div>
</div>

<div class="card">
    <div class="card-header"><h3>All Products — Stock Summary</h3></div>
    <div class="table-responsive">
        <?php if ($allInventory): ?>
        <table>
            <thead>
                <tr><th>Product</th><th>Code</th><th>Current Stock</th><th>Min</th><th>Max</th><th>Unit Price</th><th>Stock Value</th><th>Status</th><th>Actions</th></tr>
            </thead>
            <tbody>
                <?php foreach ($allInventory as $item):
                    $stockValue = $item['current_stock'] * $item['unit_price'];
                    if ($item['current_stock'] <= 0) { $badge = 'badge-danger'; $status = 'Out of Stock'; }
                    elseif ($item['current_stock'] <= $item['minimum_stock']) { $badge = 'badge-warning'; $status = 'Low Stock'; }
                    elseif ($item['current_stock'] >= $item['maximum_stock']) { $badge = 'badge-info'; $status = 'Overstocked'; }
                    else { $badge = 'badge-success'; $status = 'Normal'; }
                ?>
                <tr>
                    <td><span class="fw-600"><?php echo htmlspecialchars($item['product_name']); ?></span></td>
                    <td><code><?php echo htmlspecialchars($item['product_code']); ?></code></td>
                    <td><span class="fw-bold"><?php echo $item['current_stock']; ?></span></td>
                    <td><?php echo $item['minimum_stock']; ?></td>
                    <td><?php echo $item['maximum_stock']; ?></td>
                    <td>Rs. <?php echo number_format($item['unit_price'], 2); ?></td>
                    <td>Rs. <?php echo number_format($stockValue, 2); ?></td>
                    <td><span class="badge <?php echo $badge; ?>"><?php echo $status; ?></span></td>
                    <td>
                        <div class="action-buttons">
                            <a href="add-transaction.php?product_id=<?php echo $item['product_id']; ?>" class="btn btn-sm btn-primary">+ Stock</a>
                            <a href="edit.php?product_id=<?php echo $item['product_id']; ?>" class="btn btn-sm btn-secondary">Edit</a>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php else: ?>
        <div class="card-body"><div class="alert alert-info">No inventory records. <a href="../products/add.php">Add a product first <?php echo icon('chevron-right', 13); ?></a></div></div>
        <?php endif; ?>
    </div>
</div>

<?php require_once '../../includes/layout-end.php'; ?>
