<?php
require_once 'config/database.php';
require_once 'classes/Product.php';
require_once 'classes/Inventory.php';
require_once 'classes/Supplier.php';

$product   = new Product($conn);
$inventory = new Inventory($conn);
$supplier  = new Supplier($conn);

$allProducts      = $product->getAllProducts();
$totalProducts    = $allProducts ? count($allProducts) : 0;
$allInventory     = $inventory->getAllInventory();
$lowStockItems    = $inventory->getLowStockItems();
$lowStockCount    = $lowStockItems ? count($lowStockItems) : 0;
$allSuppliers     = $supplier->getAllSuppliers();
$totalSuppliers   = $allSuppliers ? count($allSuppliers) : 0;

$totalInventoryValue = 0;
if ($allInventory) {
    foreach ($allInventory as $item)
        $totalInventoryValue += ($item['current_stock'] * $item['unit_price']);
}

$pageTitle    = 'Dashboard';
$pageSubtitle = 'Welcome back! Here is your inventory overview.';
$activeMenu   = 'dashboard';
$cssPath      = 'assets/css/style.css';
$jsPath       = 'assets/js/script.js';
$basePath     = '';
require_once 'includes/layout.php';
?>

<!-- STAT CARDS -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon blue">📦</div>
        <div class="stat-info">
            <div class="stat-label">Total Products</div>
            <div class="stat-number"><?php echo $totalProducts; ?></div>
            <a href="pages/products/list.php" class="stat-link">View all →</a>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon green">🏢</div>
        <div class="stat-info">
            <div class="stat-label">Total Suppliers</div>
            <div class="stat-number"><?php echo $totalSuppliers; ?></div>
            <a href="pages/suppliers/list.php" class="stat-link">View all →</a>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon red">⚠️</div>
        <div class="stat-info">
            <div class="stat-label">Low Stock Items</div>
            <div class="stat-number"><?php echo $lowStockCount; ?></div>
            <a href="pages/inventory/low-stock.php" class="stat-link">View alerts →</a>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon yellow">💰</div>
        <div class="stat-info">
            <div class="stat-label">Inventory Value</div>
            <div class="stat-number" style="font-size:20px;">Rs. <?php echo number_format($totalInventoryValue, 0); ?></div>
            <a href="pages/reports/inventory-report.php" class="stat-link">Full report →</a>
        </div>
    </div>
</div>

<!-- QUICK ACTIONS -->
<div class="action-grid">
    <a href="pages/products/add.php" class="action-card">
        <div class="action-icon">➕</div>
        <div class="action-label">Add Product</div>
    </a>
    <a href="pages/suppliers/add.php" class="action-card">
        <div class="action-icon">🏢</div>
        <div class="action-label">Add Supplier</div>
    </a>
    <a href="pages/inventory/add-transaction.php" class="action-card">
        <div class="action-icon">🔄</div>
        <div class="action-label">Stock Transaction</div>
    </a>
    <a href="pages/reports/inventory-report.php" class="action-card">
        <div class="action-icon">📊</div>
        <div class="action-label">Generate Report</div>
    </a>
</div>

<!-- VALUE BANNER -->
<div class="value-banner">
    <div>
        <h3>TOTAL INVENTORY VALUE</h3>
        <div class="value">Rs. <?php echo number_format($totalInventoryValue, 2); ?></div>
        <p>Based on current stock levels × unit prices</p>
    </div>
    <div style="font-size: 48px; opacity: 0.3;">💰</div>
</div>

<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px;">

<!-- LOW STOCK TABLE -->
<div class="card">
    <div class="card-header">
        <h3>⚠️ Low Stock Items</h3>
        <a href="pages/inventory/low-stock.php" class="btn btn-sm btn-danger">View All</a>
    </div>
    <?php if ($lowStockItems): ?>
    <div class="table-responsive">
        <table>
            <thead><tr><th>Product</th><th>Stock</th><th>Min</th><th>Action</th></tr></thead>
            <tbody>
                <?php foreach (array_slice($lowStockItems, 0, 5) as $item): ?>
                <tr>
                    <td><span class="fw-600"><?php echo htmlspecialchars($item['product_name']); ?></span></td>
                    <td><span class="badge badge-danger"><?php echo $item['current_stock']; ?></span></td>
                    <td><?php echo $item['minimum_stock']; ?></td>
                    <td><a href="pages/inventory/add-transaction.php?product_id=<?php echo $item['product_id']; ?>" class="btn btn-sm btn-warning">Restock</a></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php else: ?>
    <div class="card-body"><div class="alert alert-success">✅ All stock levels are healthy!</div></div>
    <?php endif; ?>
</div>

<!-- RECENT PRODUCTS TABLE -->
<div class="card">
    <div class="card-header">
        <h3>📦 Recent Products</h3>
        <a href="pages/products/list.php" class="btn btn-sm btn-outline">View All</a>
    </div>
    <?php if ($allProducts): ?>
    <div class="table-responsive">
        <table>
            <thead><tr><th>Product</th><th>Code</th><th>Price</th><th>Action</th></tr></thead>
            <tbody>
                <?php foreach (array_slice($allProducts, 0, 5) as $prod): ?>
                <tr>
                    <td><span class="fw-600"><?php echo htmlspecialchars($prod['product_name']); ?></span></td>
                    <td><code><?php echo htmlspecialchars($prod['product_code']); ?></code></td>
                    <td>Rs. <?php echo number_format($prod['unit_price'], 2); ?></td>
                    <td><a href="pages/products/view.php?id=<?php echo $prod['product_id']; ?>" class="btn btn-sm btn-secondary">View</a></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php else: ?>
    <div class="card-body"><div class="alert alert-info">No products found. <a href="pages/products/add.php">Add one now</a></div></div>
    <?php endif; ?>
</div>

</div>

<?php require_once 'includes/layout-end.php'; ?>
