<?php
/**
 * DELETE PRODUCT PAGE (uses shared layout)
 * Consistent UI, CSRF-protected, shows errors when deletion fails
 */

require_once '../../config/database.php';
require_once '../../classes/Product.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$product = new Product($conn);
$product_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($product_id <= 0) {
    header("Location: list.php");
    exit;
}

$productData = $product->getProductById($product_id);
if (!$productData) {
    header("Location: list.php");
    exit;
}

$errorMsg = '';
$successMsg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirm_delete'])) {
    // CSRF check
    if (!hash_equals($_SESSION['csrf_token'] ?? '', $_POST['csrf_token'] ?? '')) {
        $errorMsg = 'Invalid CSRF token. Please reload the page and try again.';
    } else {
        $ok = $product->deleteProduct($product_id);
        if ($ok) {
            $successMsg = 'Product deleted successfully. Redirecting...';
            header("Location: list.php?message=deleted");
            exit;
        } else {
            $errorMsg = 'Failed to delete product. See server logs for details.';
            // DEV: append DB error to help debugging (remove on production)
            $errorMsg .= ' (DB error: ' . htmlspecialchars($conn->error) . ')';
        }
    }
}

$pageTitle = 'Delete Product';
$pageSubtitle = htmlspecialchars($productData['product_name']);
$activeMenu = 'products';
$cssPath = '../../assets/css/style.css';
$jsPath = '../../assets/js/script.js';
$basePath = '../../';
require_once '../../includes/layout.php';
?>

<div class="page-header">
    <div>
        <h1>Delete Product</h1>
        <p>Are you sure you want to remove this product permanently?</p>
    </div>
    <a href="view.php?id=<?php echo $product_id; ?>" class="btn btn-secondary"><?php echo icon('arrow-left', 15); ?> Back</a>
</div>

<?php if ($errorMsg): ?>
    <div class="alert alert-danger"><?php echo $errorMsg; ?></div>
<?php endif; ?>

<?php if ($successMsg): ?>
    <div class="alert alert-success"><?php echo $successMsg; ?></div>
<?php endif; ?>

<div class="card">
    <div class="card-header"><h3>Product to Delete</h3></div>
    <div class="card-body">
        <p><strong>Name:</strong> <?php echo htmlspecialchars($productData['product_name']); ?></p>
        <p><strong>Code:</strong> <code><?php echo htmlspecialchars($productData['product_code']); ?></code></p>
        <p><strong>Price:</strong> ₹<?php echo number_format($productData['unit_price'], 2); ?></p>
        <p><strong>Current Stock:</strong> <?php echo htmlspecialchars($productData['current_stock'] ?? '—'); ?></p>

        <div style="margin-top:18px;">
            <form method="POST">
                <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token'] ?? ''); ?>">
                <div style="display:flex;gap:10px;align-items:center;">
                    <button type="submit" name="confirm_delete" value="1" class="btn btn-danger">Yes, delete product</button>
                    <a href="view.php?id=<?php echo $product_id; ?>" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require_once '../../includes/layout-end.php'; ?>
