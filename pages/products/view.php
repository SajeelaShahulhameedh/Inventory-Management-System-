<?php
require_once '../../config/database.php';
require_once '../../classes/Product.php';
require_once '../../includes/icons.php';

$product = new Product($conn);
$product_id = $_GET['id'] ?? 0;
$searchedCode = isset($_GET['code']) ? trim($_GET['code']) : '';

if ($searchedCode !== '') {
    $productData = $product->getProductByCode($searchedCode);
} elseif ($product_id > 0) {
    $productData = $product->getProductById($product_id);
} else {
    header("Location: list.php"); exit;
}

if (!$productData) {
    // Show a friendly "not found" message instead of silently redirecting,
    // so a mistyped product code is easy to understand and correct.
    $pageTitle = 'Product Not Found'; $pageSubtitle = 'No product matches that code';
    $activeMenu = 'products'; $cssPath = '../../assets/css/style.css';
    $jsPath = '../../assets/js/script.js'; $basePath = '../../';
    require_once '../../includes/layout.php';
    ?>
    <div class="page-header">
        <div><h1>Product Not Found</h1><p>We couldn't find a product with that code.</p></div>
        <a href="list.php" class="btn btn-secondary"><?php echo icon('arrow-left', 15); ?> Back to Products</a>
    </div>
    <div class="card"><div class="card-body">
        <div class="alert alert-warning">
            <?php echo icon('alert-circle', 16); ?>
            No product found for code "<strong><?php echo htmlspecialchars($searchedCode ?: $product_id); ?></strong>". Please check the code and try again, or browse the full product list.
        </div>
    </div></div>
    <?php
    require_once '../../includes/layout-end.php';
    exit;
}

$pageTitle = htmlspecialchars($productData['product_name']);
$pageSubtitle = 'Product Code: ' . htmlspecialchars($productData['product_code']);
$activeMenu = 'products'; $cssPath = '../../assets/css/style.css';
$jsPath = '../../assets/js/script.js'; $basePath = '../../';
require_once '../../includes/layout.php';
?>

<div class="page-header">
    <div>
        <h1><?php echo htmlspecialchars($productData['product_name']); ?></h1>
        <p>Code: <code><?php echo htmlspecialchars($productData['product_code']); ?></code></p>
    </div>
    <div style="display:flex;gap:10px;">
        <a href="edit.php?id=<?php echo $productData['product_id']; ?>" class="btn btn-warning">Edit</a>
        <a href="list.php" class="btn btn-secondary"><?php echo icon('arrow-left', 15); ?> Back</a>
    </div>
</div>

<div style="display:grid;grid-template-columns:280px 1fr 1fr;gap:24px;">
    <div class="card">
        <div class="card-header"><h3>Photo</h3></div>
        <div class="card-body" style="padding:0;">
            <?php if (!empty($productData['image_url'])): ?>
                <img src="<?php echo htmlspecialchars($productData['image_url']); ?>" alt="<?php echo htmlspecialchars($productData['product_name']); ?>" class="product-photo" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                <div class="product-photo-fallback" style="display:none;"></div>
            <?php else: ?>
                <div class="product-photo-fallback"></div>
            <?php endif; ?>
        </div>
    </div>
    <div class="card">
        <div class="card-header"><h3>Product Information</h3></div>
        <div class="card-body">
            <table style="margin:0;">
                <tbody>
                    <tr><td class="text-muted" style="width:140px;">Name</td><td><span class="fw-600"><?php echo htmlspecialchars($productData['product_name']); ?></span></td></tr>
                    <tr><td class="text-muted">Code</td><td><code><?php echo htmlspecialchars($productData['product_code']); ?></code></td></tr>
                    <tr><td class="text-muted">Category</td><td><?php echo htmlspecialchars($productData['category_name'] ?? '—'); ?></td></tr>
                    <tr><td class="text-muted">Supplier</td><td><?php echo htmlspecialchars($productData['supplier_name'] ?? '—'); ?></td></tr>
                    <tr><td class="text-muted">Unit Price</td><td><span class="fw-bold text-primary">Rs. <?php echo number_format($productData['unit_price'], 2); ?></span></td></tr>
                </tbody>
            </table>
        </div>
    </div>
    <div class="card">
        <div class="card-header"><h3>Description</h3></div>
        <div class="card-body">
            <p><?php echo nl2br(htmlspecialchars($productData['description'] ?? 'No description provided.')); ?></p>
        </div>
    </div>
</div>

<div style="display:flex;gap:10px;margin-top:8px;">
    <a href="../../pages/inventory/add-transaction.php?product_id=<?php echo $productData['product_id']; ?>" class="btn btn-primary">+ Add Stock</a>
    <a href="edit.php?id=<?php echo $productData['product_id']; ?>" class="btn btn-warning">Edit Product</a>
    <a href="delete.php?id=<?php echo $productData['product_id']; ?>" class="btn btn-danger" onclick="return confirm('Delete this product?')">Delete Product</a>
</div>

<?php require_once '../../includes/layout-end.php'; ?>
