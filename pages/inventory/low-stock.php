<?php
require_once '../../config/database.php';
require_once '../../classes/Inventory.php';

$inventory     = new Inventory($conn);
$lowStockItems = $inventory->getLowStockItems();

$pageTitle = 'Low Stock Alert'; $pageSubtitle = 'Items below minimum stock level';
$activeMenu = 'lowstock'; $cssPath = '../../assets/css/style.css';
$jsPath = '../../assets/js/script.js'; $basePath = '../../';
require_once '../../includes/layout.php';
?>

<div class="page-header">
    <div><h1>⚠️ Low Stock Alert</h1><p><?php echo $lowStockItems ? count($lowStockItems) : 0; ?> item(s) need restocking</p></div>
    <div style="display:flex;gap:10px;">
        <a href="add-transaction.php" class="btn btn-primary">➕ Add Stock</a>
        <a href="list.php" class="btn btn-secondary">← All Inventory</a>
    </div>
</div>

<?php if ($lowStockItems): ?>
<div class="alert alert-warning">⚠️ The following products are running low. Please restock them as soon as possible.</div>
<div class="card">
    <div class="table-responsive">
        <table>
            <thead><tr><th>Product</th><th>Code</th><th>Current Stock</th><th>Min Level</th><th>Units Needed</th><th>Unit Price</th><th>Status</th><th>Action</th></tr></thead>
            <tbody>
                <?php foreach ($lowStockItems as $item):
                    $needed = $item['minimum_stock'] - $item['current_stock'];
                    $isOut  = $item['current_stock'] <= 0;
                ?>
                <tr>
                    <td><span class="fw-600"><?php echo htmlspecialchars($item['product_name']); ?></span></td>
                    <td><code><?php echo htmlspecialchars($item['product_code']); ?></code></td>
                    <td><span class="fw-bold text-danger"><?php echo $item['current_stock']; ?></span></td>
                    <td><?php echo $item['minimum_stock']; ?></td>
                    <td><span class="text-danger fw-bold">+<?php echo max(0, $needed); ?></span></td>
                    <td>$<?php echo number_format($item['unit_price'], 2); ?></td>
                    <td><span class="badge <?php echo $isOut ? 'badge-danger' : 'badge-warning'; ?>"><?php echo $isOut ? 'Out of Stock' : 'Low Stock'; ?></span></td>
                    <td><a href="add-transaction.php?product_id=<?php echo $item['product_id']; ?>" class="btn btn-sm btn-warning">Restock</a></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?php else: ?>
<div class="alert alert-success">✅ All stock levels are healthy! No items below minimum.</div>
<?php endif; ?>

<?php require_once '../../includes/layout-end.php'; ?>
