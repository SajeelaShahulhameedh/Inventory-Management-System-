<?php
require_once '../../config/database.php';
require_once '../../classes/Product.php';
require_once '../../classes/Inventory.php';
require_once '../../classes/Category.php';
require_once '../../classes/Supplier.php';
require_once '../../includes/icons.php';

if (session_status() === PHP_SESSION_NONE) session_start();

$product = new Product($conn);
$inventory = new Inventory($conn);
$categoryObj = new Category($conn);
$supplierObj = new Supplier($conn);
$categories = $categoryObj->getAll();
$suppliers = $supplierObj->getAllSuppliers();

$message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // CSRF check
    if (!hash_equals($_SESSION['csrf_token'] ?? '', $_POST['csrf_token'] ?? '')) {
        $message = '<div class="alert alert-danger">Invalid CSRF token.</div>';
    } else {
        $product->product_name = trim($_POST['product_name'] ?? '');
        $product->product_code = trim($_POST['product_code'] ?? '');
        $product->category_id = (int)($_POST['category_id'] ?? 0);
        $product->supplier_id = (int)($_POST['supplier_id'] ?? 0);
        $product->unit_price = (float)($_POST['unit_price'] ?? 0);
        $product->description = trim($_POST['description'] ?? '');
        $product->image_url = trim($_POST['image_url'] ?? '');

        $initialStock = max(0, (int)($_POST['current_stock'] ?? 0));
        $minimumStock = max(0, (int)($_POST['minimum_stock'] ?? 10));
        $maximumStock = max(0, (int)($_POST['maximum_stock'] ?? 100));

        // Basic server-side validation
        if ($product->product_name === '' || $product->product_code === '' || $product->category_id <= 0 || $product->supplier_id <= 0) {
            $message = '<div class="alert alert-danger">Please fill required fields.</div>';
        } elseif ($product->codeExists($product->product_code)) {
            $message = '<div class="alert alert-danger">' . icon('alert-circle', 16) . ' Product code "' . htmlspecialchars($product->product_code) . '" is already in use. Please choose a different code.</div>';
        } else {
            if ($product->addProduct()) {
                // Create the matching inventory record so stock shows up immediately
                $inventory->createInventory($product->product_id, $initialStock, $minimumStock, $maximumStock);

                $message = '<div class="alert alert-success">Product added successfully! <a href="list.php">View all products </a></div>';
                header("refresh:2;url=list.php");
            } else {
                $message = '<div class="alert alert-danger">Error adding product. Please try again.</div>';
            }
        }
    }
}

$pageTitle = 'Add Product'; $pageSubtitle = 'Add a new product to inventory';
$activeMenu = 'products'; $cssPath = '../../assets/css/style.css';
$jsPath = '../../assets/js/script.js'; $basePath = '../../';
require_once '../../includes/layout.php';
?>

<div class="page-header">
    <div><h1>Add New Product</h1><p>Fill in the details below</p></div>
    <a href="list.php" class="btn btn-secondary"><?php echo icon('arrow-left', 15); ?> Back to Products</a>
</div>

<?php echo $message; ?>

<div class="card">
    <div class="card-header"><h3>Product Details</h3></div>
    <div class="card-body">
        <form method="POST" onsubmit="return validateProductForm()">
            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token'] ?? ''); ?>">
            <div class="form-row">
                <div class="form-group">
                    <label>Product Name <span class="text-danger">*</span></label>
                    <input type="text" id="product_name" name="product_name" placeholder="Enter product name" required>
                </div>
                <div class="form-group">
                    <label>Product Code <span class="text-danger">*</span></label>
                    <input type="text" id="product_code" name="product_code" placeholder="e.g., PROD-001" required>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label>Category <span class="text-danger">*</span></label>
                    <select id="category_id" name="category_id" required>
                        <option value="">-- Select Category --</option>
                        <?php foreach ($categories as $c): ?>
                            <option value="<?php echo (int)$c['category_id']; ?>"><?php echo htmlspecialchars($c['category_name']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Supplier <span class="text-danger">*</span></label>
                    <select id="supplier_id" name="supplier_id" required>
                        <option value="">-- Select Supplier --</option>
                        <?php foreach ($suppliers as $s): ?>
                            <option value="<?php echo (int)$s['supplier_id']; ?>"><?php echo htmlspecialchars($s['supplier_name']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label>Unit Price (Rs.) <span class="text-danger">*</span></label>
                    <input type="number" id="unit_price" name="unit_price" placeholder="0.00" step="0.01" min="0" required>
                </div>
                <div class="form-group">
                    <label>Image URL</label>
                    <input type="text" name="image_url" placeholder="https://example.com/image.jpg">
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label>Initial Stock (On Hand)</label>
                    <input type="number" id="current_stock" name="current_stock" placeholder="0" min="0" value="0">
                </div>
                <div class="form-group">
                    <label>Minimum Stock (Low-Stock Threshold)</label>
                    <input type="number" id="minimum_stock" name="minimum_stock" placeholder="10" min="0" value="10">
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label>Maximum Stock Capacity</label>
                    <input type="number" id="maximum_stock" name="maximum_stock" placeholder="100" min="0" value="100">
                </div>
            </div>
            <div class="form-group">
                <label>Description</label>
                <textarea name="description" placeholder="Enter product description..."></textarea>
            </div>
            <div style="display:flex;gap:10px;margin-top:8px;">
                <button type="submit" class="btn btn-primary">Save Product</button>
                <a href="list.php" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>

<?php require_once '../../includes/layout-end.php'; ?>
