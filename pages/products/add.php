<?php
require_once '../../config/database.php';
require_once '../../classes/Product.php';
require_once '../../classes/Inventory.php';

$product   = new Product($conn);
$inventory = new Inventory($conn);
$message   = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $product->product_name = $_POST['product_name'] ?? '';
    $product->product_code = $_POST['product_code'] ?? '';
    $product->category_id  = $_POST['category_id'] ?? 0;
    $product->supplier_id  = $_POST['supplier_id'] ?? 0;
    $product->unit_price   = $_POST['unit_price'] ?? 0;
    $product->description  = $_POST['description'] ?? '';
    $product->image_url    = $_POST['image_url'] ?? '';

    $initialStock  = (int)($_POST['current_stock'] ?? 0);
    $minimumStock  = (int)($_POST['minimum_stock'] ?? 10);
    $maximumStock  = (int)($_POST['maximum_stock'] ?? 100);

    if ($product->addProduct()) {
        // Create the matching inventory record so stock shows up immediately
        $inventory->createInventory($product->product_id, $initialStock, $minimumStock, $maximumStock);

        $message = '<div class="alert alert-success">Product added successfully! <a href="list.php">View all products →</a></div>';
        header("refresh:2;url=list.php");
    } else {
        $message = '<div class="alert alert-danger">Error adding product. Please try again.</div>';
    }
}

$pageTitle = 'Add Product'; $pageSubtitle = 'Add a new product to inventory';
$activeMenu = 'products'; $cssPath = '../../assets/css/style.css';
$jsPath = '../../assets/js/script.js'; $basePath = '../../';
require_once '../../includes/layout.php';
?>

<div class="page-header">
    <div><h1>Add New Product</h1><p>Fill in the details below</p></div>
    <a href="list.php" class="btn btn-secondary">← Back to Products</a>
</div>

<?php echo $message; ?>

<div class="card">
    <div class="card-header"><h3>Product Details</h3></div>
    <div class="card-body">
        <form method="POST" onsubmit="return validateProductForm()">
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
                        <option value="1">Electronics</option>
                        <option value="2">Furniture</option>
                        <option value="3">Office Supplies</option>
                        <option value="4">Tools</option>
                        <option value="5">Other</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Supplier <span class="text-danger">*</span></label>
                    <select id="supplier_id" name="supplier_id" required>
                        <option value="">-- Select Supplier --</option>
                        <option value="1">Supplier 1</option>
                        <option value="2">Supplier 2</option>
                        <option value="3">Supplier 3</option>
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
