<?php
require_once 'config/database.php';
require_once 'classes/Product.php';
require_once 'classes/Inventory.php';
require_once 'classes/Supplier.php';
require_once 'classes/PurchaseOrder.php';

$product = new Product($conn);
$inventory = new Inventory($conn);
$supplier = new Supplier($conn);
$purchaseOrder = new PurchaseOrder($conn);

$allProducts = $product->getAllProducts();
$totalProducts = $allProducts ? count($allProducts) : 0;
$allInventory = $inventory->getAllInventory();
$lowStockItems = $inventory->getLowStockItems();
$lowStockCount = $lowStockItems ? count($lowStockItems) : 0;
$allSuppliers = $supplier->getAllSuppliers();
$totalSuppliers = $allSuppliers ? count($allSuppliers) : 0;
$pendingOrdersCount = $purchaseOrder->countPending();

$totalInventoryValue = 0;
if ($allInventory) {
    foreach ($allInventory as $item)
        $totalInventoryValue += ($item['current_stock'] * $item['unit_price']);
}

$pageTitle = 'Dashboard';
$pageSubtitle = 'Welcome back! Here is your inventory overview.';
$activeMenu = 'dashboard';
$cssPath = 'assets/css/style.css';
$jsPath = 'assets/js/script.js';
$basePath = '';
require_once 'includes/layout.php';
?>

<!-- STAT CARDS -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon blue"><?php echo icon('box', 22); ?></div>
        <div class="stat-info">
            <div class="stat-label">Total Products</div>
            <div class="stat-number"><?php echo $totalProducts; ?></div>
            <a href="pages/products/list.php" class="stat-link">View all <?php echo icon('chevron-right', 13); ?></a>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon green"><?php echo icon('building', 22); ?></div>
        <div class="stat-info">
            <div class="stat-label">Total Suppliers</div>
            <div class="stat-number"><?php echo $totalSuppliers; ?></div>
            <a href="pages/suppliers/list.php" class="stat-link">View all <?php echo icon('chevron-right', 13); ?></a>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon red"><?php echo icon('alert-triangle', 22); ?></div>
        <div class="stat-info">
            <div class="stat-label">Low Stock Items</div>
            <div class="stat-number"><?php echo $lowStockCount; ?></div>
            <a href="pages/inventory/low-stock.php" class="stat-link">View alerts <?php echo icon('chevron-right', 13); ?></a>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon yellow"><?php echo icon('dollar', 22); ?></div>
        <div class="stat-info">
            <div class="stat-label">Inventory Value</div>
            <div class="stat-number" style="font-size:20px;">Rs. <?php echo number_format($totalInventoryValue, 0); ?></div>
            <a href="pages/reports/inventory-report.php" class="stat-link">Full report <?php echo icon('chevron-right', 13); ?></a>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon purple"><?php echo icon('clipboard', 22); ?></div>
        <div class="stat-info">
            <div class="stat-label">Pending Orders</div>
            <div class="stat-number"><?php echo $pendingOrdersCount; ?></div>
            <a href="pages/purchase-orders/list.php" class="stat-link">View orders <?php echo icon('chevron-right', 13); ?></a>
        </div>
    </div>
</div>

<!-- QUICK ACTIONS -->
<div class="action-grid">
    <a href="pages/products/add.php" class="action-card">
        <div class="action-icon"><?php echo icon('plus', 24); ?></div>
        <div class="action-label">Add Product</div>
    </a>
    <a href="pages/suppliers/add.php" class="action-card">
        <div class="action-icon"><?php echo icon('building', 24); ?></div>
        <div class="action-label">Add Supplier</div>
    </a>
    <a href="pages/inventory/add-transaction.php" class="action-card">
        <div class="action-icon"><?php echo icon('repeat', 24); ?></div>
        <div class="action-label">Stock Transaction</div>
    </a>
    <a href="pages/reports/inventory-report.php" class="action-card">
        <div class="action-icon"><?php echo icon('bar-chart', 24); ?></div>
        <div class="action-label">Generate Report</div>
    </a>
    <a href="pages/purchase-orders/add.php" class="action-card">
        <div class="action-icon"><?php echo icon('clipboard', 24); ?></div>
        <div class="action-label">New Purchase Order</div>
    </a>
</div>

<!-- QUICK PRODUCT LOOKUP -->
<div class="card" style="margin-bottom:24px;">
    <div class="card-header"><h3>Quick Product Lookup</h3></div>
    <div class="card-body">
        <form method="GET" action="pages/products/view.php" style="display:flex; gap:10px; align-items:center;">
            <div class="product-search-form" style="flex:1; max-width:420px;">
                <span class="search-icon"><?php echo icon('tag', 16); ?></span>
                <input type="text" name="code" placeholder="Enter exact product code, e.g. ELEC-101" required>
            </div>
            <button type="submit" class="btn btn-primary"><?php echo icon('search', 15); ?> Find Product</button>
        </form>
        <p class="text-muted" style="font-size:12.5px; margin-top:10px;">Jumps straight to that product's full details. For broader, partial-match search, use the <a href="pages/products/list.php">Products page</a>.</p>
    </div>
</div>

<!-- VALUE BANNER -->
<div class="value-banner">
    <div>
        <h3>TOTAL INVENTORY VALUE</h3>
        <div class="value">Rs. <?php echo number_format($totalInventoryValue, 2); ?></div>
        <p>Based on current stock levels × unit prices</p>
    </div>
    <div style="opacity: 0.35;"><?php echo icon('dollar', 48); ?></div>
</div>

<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px;">

<!-- LOW STOCK TABLE -->
<div class="card">
    <div class="card-header">
        <h3>Low Stock Items</h3>
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
    <div class="card-body"><div class="alert alert-success"><?php echo icon('check-circle', 16); ?> All stock levels are healthy!</div></div>
    <?php endif; ?>
</div>

<!-- RECENT PRODUCTS TABLE -->
<div class="card">
    <div class="card-header">
        <h3>Recent Products</h3>
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
