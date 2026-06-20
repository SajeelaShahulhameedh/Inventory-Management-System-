<?php
require_once '../../config/database.php';
require_once '../../classes/Inventory.php';
require_once '../../classes/Product.php';
require_once '../../classes/Supplier.php';

$inventory    = new Inventory($conn);
$product      = new Product($conn);
$supplier     = new Supplier($conn);
$allInventory = $inventory->getAllInventory();
$lowStock     = $inventory->getLowStockItems();
$allProducts  = $product->getAllProducts();
$allSuppliers = $supplier->getAllSuppliers();

$totalValue = 0;
if ($allInventory) foreach ($allInventory as $i) $totalValue += $i['current_stock'] * $i['unit_price'];

$pageTitle = 'Reports'; $pageSubtitle = 'Inventory analytics and summaries';
$activeMenu = 'reports'; $cssPath = '../../assets/css/style.css';
$jsPath = '../../assets/js/script.js'; $basePath = '../../';
require_once '../../includes/layout.php';
?>

<div class="page-header">
    <div><h1>📊 Reports</h1><p>Overview of your inventory system</p></div>
</div>

<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon blue">📦</div>
        <div class="stat-info">
            <div class="stat-label">Total Products</div>
            <div class="stat-number"><?php echo $allProducts ? count($allProducts) : 0; ?></div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon green">💰</div>
        <div class="stat-info">
            <div class="stat-label">Inventory Value</div>
            <div class="stat-number" style="font-size:20px;">$<?php echo number_format($totalValue, 0); ?></div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon red">⚠️</div>
        <div class="stat-info">
            <div class="stat-label">Low Stock Items</div>
            <div class="stat-number"><?php echo $lowStock ? count($lowStock) : 0; ?></div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon yellow">🏢</div>
        <div class="stat-info">
            <div class="stat-label">Total Suppliers</div>
            <div class="stat-number"><?php echo $allSuppliers ? count($allSuppliers) : 0; ?></div>
        </div>
    </div>
</div>

<div class="report-grid">
    <div class="card">
        <div class="card-header"><h3>📊 Inventory Stock Report</h3></div>
        <div class="card-body"><p class="text-muted">Full stock levels, values, and status for all products.</p></div>
        <div class="card-footer"><a href="inventory-report.php" class="btn btn-primary">View Report</a></div>
    </div>
    <div class="card">
        <div class="card-header"><h3>⚠️ Low Stock Report</h3></div>
        <div class="card-body">
            <p class="text-muted">Products below their minimum stock level.</p>
            <?php if ($lowStock && count($lowStock) > 0): ?>
            <p class="text-danger fw-600"><?php echo count($lowStock); ?> item(s) need restocking!</p>
            <?php else: ?>
            <p class="text-success">All stock levels are healthy ✅</p>
            <?php endif; ?>
        </div>
        <div class="card-footer"><a href="../inventory/low-stock.php" class="btn btn-danger">View Report</a></div>
    </div>
    <div class="card">
        <div class="card-header"><h3>📦 Products Summary</h3></div>
        <div class="card-body"><p class="text-muted">All products with categories and supplier info.</p></div>
        <div class="card-footer"><a href="../products/list.php" class="btn btn-primary">View Products</a></div>
    </div>
    <div class="card">
        <div class="card-header"><h3>🏢 Suppliers Summary</h3></div>
        <div class="card-body">
            <p class="text-muted">All suppliers with contact details.</p>
            <p class="text-muted"><?php echo $allSuppliers ? count($allSuppliers) : 0; ?> supplier(s) registered.</p>
        </div>
        <div class="card-footer"><a href="../suppliers/list.php" class="btn btn-primary">View Suppliers</a></div>
    </div>
</div>

<?php require_once '../../includes/layout-end.php'; ?>
