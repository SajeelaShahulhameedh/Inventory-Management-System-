<?php
require_once '../../config/database.php';
require_once '../../classes/Product.php';
require_once '../../classes/Category.php';
require_once '../../classes/Supplier.php';
require_once '../../includes/icons.php';

if (session_status() === PHP_SESSION_NONE) session_start();

$product = new Product($conn);
$categoryObj = new Category($conn);
$supplierObj = new Supplier($conn);
$categories = $categoryObj->getAll();
$suppliers = $supplierObj->getAllSuppliers();

$product_id = $_GET['id'] ?? 0;
$message = '';
if ($product_id <= 0) { header("Location: list.php"); exit; }
$productData = $product->getProductById($product_id);
if (!$productData) { header("Location: list.php"); exit; }

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!hash_equals($_SESSION['csrf_token'] ?? '', $_POST['csrf_token'] ?? '')) {
        $message = '<div class="alert alert-danger">Invalid CSRF token. Please reload and try again.</div>';
    } else {
    $product->product_id = $product_id;
    $product->product_name = $_POST['product_name'] ?? '';
    $product->product_code = trim($_POST['product_code'] ?? '');
    $product->category_id = $_POST['category_id'] ?? 0;
    $product->supplier_id = $_POST['supplier_id'] ?? 0;
    $product->unit_price = $_POST['unit_price'] ?? 0;
    $product->description = $_POST['description'] ?? '';
    $product->image_url = $_POST['image_url'] ?? '';

    if ($product->product_code === '') {
        $message = '<div class="alert alert-danger">Product code is required.</div>';
    } elseif ($product->codeExists($product->product_code, $product_id)) {
        $message = '<div class="alert alert-danger">' . icon('alert-circle', 16) . ' Product code "' . htmlspecialchars($product->product_code) . '" is already used by another product. Please choose a different code.</div>';
    } elseif ($product->updateProduct()) {
        $message = '<div class="alert alert-success">Product updated successfully!</div>';
        $productData = $product->getProductById($product_id);
    } else {
        $message = '<div class="alert alert-danger">Error updating product. Please try again.</div>';
    }
    }
}

$pageTitle = 'Edit Product'; $pageSubtitle = htmlspecialchars($productData['product_name']);
$activeMenu = 'products'; $cssPath = '../../assets/css/style.css';
$jsPath = '../../assets/js/script.js'; $basePath = '../../';
require_once '../../includes/layout.php';
?>

<div class="page-header">
    <div><h1>Edit Product</h1><p><?php echo htmlspecialchars($productData['product_name']); ?></p></div>
    <a href="view.php?id=<?php echo $product_id; ?>" class="btn btn-secondary"><?php echo icon('arrow-left', 15); ?> Back</a>
</div>

<?php echo $message; ?>

<div class="card">
    <div class="card-header"><h3>Edit Product Details</h3></div>
    <div class="card-body">
        <form method="POST" onsubmit="return validateProductForm()">
            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token'] ?? ''); ?>">
            <div class="form-row">
                <div class="form-group">
                    <label>Product Name <span class="text-danger">*</span></label>
                    <input type="text" id="product_name" name="product_name" value="<?php echo htmlspecialchars($productData['product_name']); ?>" required>
                </div>
                <div class="form-group">
                    <label>Product Code <span class="text-danger">*</span></label>
                    <input type="text" id="product_code" name="product_code" value="<?php echo htmlspecialchars($productData['product_code']); ?>" required>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label>Category <span class="text-danger">*</span></label>
                    <select id="category_id" name="category_id" required>
                        <option value="">-- Select Category --</option>
                        <?php foreach ($categories as $c): ?>
                            <option value="<?php echo (int)$c['category_id']; ?>" <?php echo $productData['category_id']==$c['category_id']?'selected':''; ?>><?php echo htmlspecialchars($c['category_name']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Supplier <span class="text-danger">*</span></label>
                    <select id="supplier_id" name="supplier_id" required>
                        <option value="">-- Select Supplier --</option>
                        <?php foreach ($suppliers as $s): ?>
                            <option value="<?php echo (int)$s['supplier_id']; ?>" <?php echo $productData['supplier_id']==$s['supplier_id']?'selected':''; ?>><?php echo htmlspecialchars($s['supplier_name']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label>Unit Price (Rs.) <span class="text-danger">*</span></label>
                    <input type="number" id="unit_price" name="unit_price" value="<?php echo htmlspecialchars($productData['unit_price']); ?>" step="0.01" min="0" required>
                </div>
                <div class="form-group">
                    <label>Image URL</label>
                    <input type="text" name="image_url" value="<?php echo htmlspecialchars($productData['image_url'] ?? ''); ?>">
                </div>
            </div>
            <div class="form-group">
                <label>Description</label>
                <textarea name="description"><?php echo htmlspecialchars($productData['description'] ?? ''); ?></textarea>
            </div>
            <div style="display:flex;gap:10px;margin-top:8px;">
                <button type="submit" class="btn btn-primary">Update Product</button>
                <a href="view.php?id=<?php echo $product_id; ?>" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>

<?php require_once '../../includes/layout-end.php'; ?>
