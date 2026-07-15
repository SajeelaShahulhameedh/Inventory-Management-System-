<?php
require_once '../../config/database.php';
require_once '../../classes/Product.php';

$product = new Product($conn);
$searchTerm = isset($_GET['search']) ? trim($_GET['search']) : '';
$view = isset($_GET['view']) && $_GET['view'] === 'table' ? 'table' : 'grid';
$products = !empty($searchTerm) ? $product->searchProducts($searchTerm) : $product->getAllProducts();
$products = $products ?: [];

// Group products by category for the grid view
$grouped = [];
foreach ($products as $p) {
    $cat = $p['category_name'] ?? 'Uncategorized';
    if (!isset($grouped[$cat])) { $grouped[$cat] = []; }
    $grouped[$cat][] = $p;
}

$totalProducts = count($products);
$totalItems = array_sum(array_map(fn($p) => (int)($p['current_stock'] ?? 0), $products));

$pageTitle = 'Products'; $pageSubtitle = 'Manage all your products';
$activeMenu = 'products'; $cssPath = '../../assets/css/style.css';
$jsPath = '../../assets/js/script.js'; $basePath = '../../';
require_once '../../includes/layout.php';
?>

<div class="page-header">
    <div>
        <h1>Products</h1>
        <p><?php echo $totalProducts; ?> product(s) &middot; <?php echo number_format($totalItems); ?> items on hand</p>
    </div>
    <a href="add.php" class="btn btn-primary">+ Create New Product</a>
</div>

<div class="product-toolbar">
    <form method="GET" class="product-search-form">
        <input type="hidden" name="view" value="<?php echo htmlspecialchars($view); ?>">
        <span class="search-icon"><?php echo icon('search', 16); ?></span>
        <input type="text" name="search" placeholder="Search products by name or code..." value="<?php echo htmlspecialchars($searchTerm); ?>">
        <?php if (!empty($searchTerm)): ?><a href="list.php?view=<?php echo $view; ?>" class="btn btn-secondary btn-sm">Clear</a><?php endif; ?>
    </form>

    <div class="toolbar-right">
        <span class="grouped-label">Grouped by <strong>Category</strong></span>
        <div class="view-toggle">
            <a href="?view=grid<?php echo $searchTerm ? '&search=' . urlencode($searchTerm) : ''; ?>" class="view-btn <?php echo $view === 'grid' ? 'active' : ''; ?>" title="Grid view">&#9638;</a>
            <a href="?view=table<?php echo $searchTerm ? '&search=' . urlencode($searchTerm) : ''; ?>" class="view-btn <?php echo $view === 'table' ? 'active' : ''; ?>" title="Table view">&#9776;</a>
        </div>
    </div>
</div>

<?php if (empty($products)): ?>

    <div class="card"><div class="card-body"><div class="alert alert-info">No products found. <a href="add.php">Add your first product <?php echo icon('chevron-right', 13); ?></a></div></div></div>

<?php elseif ($view === 'table'): ?>

    <div class="card">
        <div class="table-responsive">
            <table>
                <thead><tr><th></th><th>Product Name</th><th>Code</th><th>Category</th><th>Supplier</th><th>Unit Price</th><th>On Hand</th><th>Actions</th></tr></thead>
                <tbody>
                    <?php foreach ($products as $prod):
                        $stock = (int)($prod['current_stock'] ?? 0);
                        $min = (int)($prod['minimum_stock'] ?? 0);
                        $isLow = $stock <= $min;
                    ?>
                    <tr>
                        <td style="width:54px;">
                            <?php if (!empty($prod['image_url'])): ?>
                                <img src="<?php echo htmlspecialchars($prod['image_url']); ?>" alt="" class="product-thumb" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                <div class="product-thumb-fallback" style="display:none;"></div>
                            <?php else: ?>
                                <div class="product-thumb-fallback"></div>
                            <?php endif; ?>
                        </td>
                        <td><span class="fw-600"><?php echo htmlspecialchars($prod['product_name']); ?></span></td>
                        <td><code><?php echo htmlspecialchars($prod['product_code']); ?></code></td>
                        <td><?php echo htmlspecialchars($prod['category_name'] ?? '—'); ?></td>
                        <td><?php echo htmlspecialchars($prod['supplier_name'] ?? '—'); ?></td>
                        <td>Rs. <?php echo number_format($prod['unit_price'], 2); ?></td>
                        <td><?php echo $isLow ? '<span class="badge badge-danger">' . $stock . ' low</span>' : $stock; ?></td>
                        <td>
                            <div class="action-buttons">
                                <a href="view.php?id=<?php echo $prod['product_id']; ?>" class="btn btn-sm btn-secondary">View</a>
                                <a href="edit.php?id=<?php echo $prod['product_id']; ?>" class="btn btn-sm btn-primary">Edit</a>
                                <a href="delete.php?id=<?php echo $prod['product_id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete <?php echo htmlspecialchars($prod['product_name']); ?>?')">Delete</a>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

<?php else: ?>

    <?php foreach ($grouped as $catName => $catProducts):
        $catItemCount = array_sum(array_map(fn($p) => (int)($p['current_stock'] ?? 0), $catProducts));
    ?>
    <details class="product-group" open>
        <summary class="group-header">
            <span class="group-header-left">
                <span class="group-icon"><?php echo icon('tag', 15); ?></span>
                <span class="group-title"><?php echo htmlspecialchars($catName); ?></span>
                <span class="group-meta"><?php echo count($catProducts); ?> products &middot; <?php echo number_format($catItemCount); ?> items</span>
            </span>
            <span class="group-chevron">&#8964;</span>
        </summary>

        <div class="product-grid">
            <?php foreach ($catProducts as $prod):
                $stock = (int)($prod['current_stock'] ?? 0);
                $min = (int)($prod['minimum_stock'] ?? 0);
                $isLow = $stock <= $min;
            ?>
            <div class="product-card">
                <div class="product-card-img">
                    <?php if ($isLow): ?><span class="low-stock-tag">Low Stock</span><?php endif; ?>
                    <?php if (!empty($prod['image_url'])): ?>
                        <img src="<?php echo htmlspecialchars($prod['image_url']); ?>" alt="<?php echo htmlspecialchars($prod['product_name']); ?>" onerror="this.onerror=null; this.style.display='none'; this.nextElementSibling.style.display='flex';">
                        <div class="product-card-fallback" style="display:none;"></div>
                    <?php else: ?>
                        <div class="product-card-fallback"></div>
                    <?php endif; ?>
                </div>
                <div class="product-card-body">
                    <div class="product-card-row">
                        <span class="product-code-chip"><?php echo htmlspecialchars($prod['product_code']); ?></span>
                        <span class="product-price">Rs. <?php echo number_format($prod['unit_price'], 2); ?></span>
                    </div>
                    <h4 class="product-card-name"><?php echo htmlspecialchars($prod['product_name']); ?></h4>
                    <p class="product-card-stock <?php echo $isLow ? 'low' : ''; ?>">On hand: <?php echo $stock; ?> items</p>
                    <div class="product-card-actions">
                        <a href="view.php?id=<?php echo $prod['product_id']; ?>" class="btn btn-sm btn-secondary">View</a>
                        <a href="edit.php?id=<?php echo $prod['product_id']; ?>" class="btn btn-sm btn-primary">Edit</a>
                        <a href="delete.php?id=<?php echo $prod['product_id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete <?php echo htmlspecialchars($prod['product_name']); ?>?')">&times;</a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </details>
    <?php endforeach; ?>

<?php endif; ?>

<?php require_once '../../includes/layout-end.php'; ?>
