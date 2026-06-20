<?php
require_once '../../config/database.php';
require_once '../../classes/Product.php';

$product    = new Product($conn);
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';
$products   = !empty($searchTerm) ? $product->searchProducts($searchTerm) : $product->getAllProducts();

$pageTitle = 'Products'; $pageSubtitle = 'Manage all your products';
$activeMenu = 'products'; $cssPath = '../../assets/css/style.css';
$jsPath = '../../assets/js/script.js'; $basePath = '../../';
require_once '../../includes/layout.php';
?>

<div class="page-header">
    <div>
        <h1>📦 Products</h1>
        <p><?php echo $products ? count($products) : 0; ?> product(s) found</p>
    </div>
    <a href="add.php" class="btn btn-primary">➕ Add Product</a>
</div>

<div class="card">
    <div class="card-header">
        <h3>All Products</h3>
        <form method="GET" style="display:flex; gap:8px;">
            <input type="text" name="search" placeholder="Search products..." value="<?php echo htmlspecialchars($searchTerm); ?>" style="width:220px;">
            <button type="submit" class="btn btn-primary btn-sm">Search</button>
            <?php if (!empty($searchTerm)): ?><a href="list.php" class="btn btn-secondary btn-sm">Clear</a><?php endif; ?>
        </form>
    </div>
    <div class="table-responsive">
        <?php if ($products): ?>
        <table>
            <thead><tr><th>Product Name</th><th>Code</th><th>Category</th><th>Supplier</th><th>Unit Price</th><th>Actions</th></tr></thead>
            <tbody>
                <?php foreach ($products as $prod): ?>
                <tr>
                    <td><span class="fw-600"><?php echo htmlspecialchars($prod['product_name']); ?></span></td>
                    <td><code><?php echo htmlspecialchars($prod['product_code']); ?></code></td>
                    <td><?php echo htmlspecialchars($prod['category_name'] ?? '—'); ?></td>
                    <td><?php echo htmlspecialchars($prod['supplier_name'] ?? '—'); ?></td>
                    <td>$<?php echo number_format($prod['unit_price'], 2); ?></td>
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
        <?php else: ?>
        <div class="card-body"><div class="alert alert-info">No products found. <a href="add.php">Add your first product →</a></div></div>
        <?php endif; ?>
    </div>
</div>

<?php require_once '../../includes/layout-end.php'; ?>
