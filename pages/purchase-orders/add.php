<?php
require_once '../../config/database.php';
require_once '../../classes/PurchaseOrder.php';
require_once '../../classes/Product.php';
require_once '../../classes/Supplier.php';

if (session_status() === PHP_SESSION_NONE) session_start();

$po = new PurchaseOrder($conn);
$productObj = new Product($conn);
$supplierObj = new Supplier($conn);
$allProducts = $productObj->getAllProducts();
$allSuppliers = $supplierObj->getAllSuppliers();

$message = ''; $messageType = '';
$preSelected = isset($_GET['product_id']) ? (int)$_GET['product_id'] : 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!hash_equals($_SESSION['csrf_token'] ?? '', $_POST['csrf_token'] ?? '')) {
        $message = 'Invalid CSRF token. Please reload and try again.'; $messageType = 'danger';
    } else {
        $po->product_id = (int)($_POST['product_id'] ?? 0);
        $po->supplier_id = (int)($_POST['supplier_id'] ?? 0);
        $po->quantity = (int)($_POST['quantity'] ?? 0);
        $po->expected_delivery = trim($_POST['expected_delivery'] ?? '');

        if ($po->product_id <= 0 || $po->supplier_id <= 0 || $po->quantity <= 0) {
            $message = 'Please select a product, a supplier, and enter a quantity greater than 0.';
            $messageType = 'danger';
        } else {
            if ($po->create()) {
                $message = 'Purchase order placed successfully!';
                $messageType = 'success';
                $preSelected = 0;
            } else {
                $message = 'Failed to place purchase order. Please try again.';
                $messageType = 'danger';
            }
        }
    }
}

$pageTitle = 'New Purchase Order'; $pageSubtitle = 'Order more stock from a supplier';
$activeMenu = 'purchase-orders'; $cssPath = '../../assets/css/style.css';
$jsPath = '../../assets/js/script.js'; $basePath = '../../';
require_once '../../includes/layout.php';
?>

<div class="page-header">
    <div><h1>New Purchase Order</h1><p>Place a restock order with a supplier</p></div>
    <a href="list.php" class="btn btn-secondary"><?php echo icon('arrow-left', 15); ?> Back</a>
</div>

<?php if (!empty($message)): ?>
<div class="alert alert-<?php echo $messageType; ?>">
    <?php echo $messageType === 'success' ? icon('check-circle', 16) : icon('alert-circle', 16); ?>
    <?php echo htmlspecialchars($message); ?>
    <?php if ($messageType === 'success'): ?><a href="list.php" style="margin-left:10px;">View all orders</a><?php endif; ?>
</div>
<?php endif; ?>

<div class="card">
    <div class="card-header"><h3>Order Details</h3></div>
    <div class="card-body">
        <form method="POST">
            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token'] ?? ''); ?>">

            <div class="form-group">
                <label>Product <span class="text-danger">*</span></label>
                <select name="product_id" id="product_id" required onchange="autoFillSupplier(this.value)">
                    <option value="">-- Select Product --</option>
                    <?php if ($allProducts): foreach ($allProducts as $prod): ?>
                    <option value="<?php echo $prod['product_id']; ?>" data-supplier="<?php echo (int)$prod['supplier_id']; ?>" <?php echo ($preSelected == $prod['product_id']) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($prod['product_name']); ?> (<?php echo htmlspecialchars($prod['product_code']); ?>)
                    </option>
                    <?php endforeach; endif; ?>
                </select>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Supplier <span class="text-danger">*</span></label>
                    <select name="supplier_id" id="supplier_id" required>
                        <option value="">-- Select Supplier --</option>
                        <?php if ($allSuppliers): foreach ($allSuppliers as $sup): ?>
                        <option value="<?php echo $sup['supplier_id']; ?>"><?php echo htmlspecialchars($sup['supplier_name']); ?></option>
                        <?php endforeach; endif; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Quantity <span class="text-danger">*</span></label>
                    <input type="number" name="quantity" min="1" placeholder="Units to order" required>
                </div>
            </div>

            <div class="form-group">
                <label>Expected Delivery Date</label>
                <input type="date" name="expected_delivery">
            </div>

            <div style="display:flex;gap:10px;">
                <button type="submit" class="btn btn-primary"><?php echo icon('save', 15); ?> Place Order</button>
                <a href="list.php" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>

<script>
// Pre-fill the supplier dropdown based on the product's usual supplier,
// while still letting the user pick a different one if needed.
function autoFillSupplier(productId) {
    const productSelect = document.getElementById('product_id');
    const supplierSelect = document.getElementById('supplier_id');
    const opt = productSelect.querySelector('option[value="' + productId + '"]');
    if (opt) {
        const supplierId = opt.getAttribute('data-supplier');
        if (supplierId && supplierId !== '0') {
            supplierSelect.value = supplierId;
        }
    }
}
window.addEventListener('load', () => {
    const s = document.getElementById('product_id');
    if (s.value) autoFillSupplier(s.value);
});
</script>

<?php require_once '../../includes/layout-end.php'; ?>
