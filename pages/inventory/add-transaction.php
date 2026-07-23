<?php
require_once '../../config/database.php';
require_once '../../classes/Inventory.php';
require_once '../../classes/Product.php';

$inventory = new Inventory($conn);
$product = new Product($conn);
$message = ''; $messageType = '';
$preSelected = isset($_GET['product_id']) ? (int)$_GET['product_id'] : 0;
$allProducts = $product->getAllProducts();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $productId = (int)$_POST['product_id'];
    $type = $_POST['transaction_type'];
    $quantity = (int)$_POST['quantity'];
    $notes = trim($_POST['notes']);

    if ($productId <= 0 || $quantity <= 0 || empty($type)) {
        $message = 'Please fill all required fields with valid values.'; $messageType = 'danger';
    } else {
        if ($type === 'OUT') {
            $inv = $inventory->getInventoryByProductId($productId);
            if ($inv && $quantity > $inv['current_stock']) {
                $message = 'Cannot remove more than available stock (' . $inv['current_stock'] . ' units).'; $messageType = 'danger';
            }
        }
        if (empty($message)) {
            if ($inventory->addTransaction($productId, $type, $quantity, $notes)) {
                $message = 'Transaction recorded successfully!'; $messageType = 'success'; $preSelected = 0;
            } else {
                $message = 'Failed to record transaction.'; $messageType = 'danger';
            }
        }
    }
}

$pageTitle = 'Stock Transaction'; $pageSubtitle = 'Record stock IN, OUT or ADJUSTMENT';
$activeMenu = 'transaction'; $cssPath = '../../assets/css/style.css';
$jsPath = '../../assets/js/script.js'; $basePath = '../../';
require_once '../../includes/layout.php';
?>

<div class="page-header">
    <div><h1>Stock Transaction</h1><p>Record stock movements for any product</p></div>
    <a href="list.php" class="btn btn-secondary"><?php echo icon('arrow-left', 15); ?> Back to Inventory</a>
</div>

<?php if (!empty($message)): ?>
<div class="alert alert-<?php echo $messageType; ?>">
    <?php echo $message; ?>
    <?php if ($messageType === 'success'): ?><a href="list.php" style="margin-left:10px;">View Inventory </a><?php endif; ?>
</div>
<?php endif; ?>

<div style="display:grid; grid-template-columns:1fr 1fr; gap:24px;">

<div class="card">
    <div class="card-header"><h3>Transaction Form</h3></div>
    <div class="card-body">
        <form method="POST">
            <div class="form-group">
                <label>Product <span class="text-danger">*</span></label>
                <select name="product_id" required onchange="loadStock(this.value)">
                    <option value="">-- Select Product --</option>
                    <?php if ($allProducts): foreach ($allProducts as $prod): ?>
                    <option value="<?php echo $prod['product_id']; ?>" <?php echo ($preSelected == $prod['product_id']) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($prod['product_name']); ?> (<?php echo htmlspecialchars($prod['product_code']); ?>)
                    </option>
                    <?php endforeach; endif; ?>
                </select>
            </div>
            <div id="stock-info" style="display:none;" class="alert alert-info">
                <?php echo icon('box', 16); ?> Current Stock: <strong id="stock-display">--</strong> units
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label>Transaction Type <span class="text-danger">*</span></label>
                    <select name="transaction_type" required>
                        <option value="">-- Select --</option>
                        <option value="IN">IN — Add Stock</option>
                        <option value="OUT">OUT — Remove Stock</option>
                        <option value="ADJUSTMENT">ADJUSTMENT</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Quantity <span class="text-danger">*</span></label>
                    <input type="number" name="quantity" min="1" placeholder="Enter quantity" required>
                </div>
            </div>
            <div class="form-group">
                <label>Notes / Reason</label>
                <textarea name="notes" placeholder="Optional notes about this transaction..."></textarea>
            </div>
            <div style="display:flex;gap:10px;">
                <button type="submit" class="btn btn-primary"><?php echo icon('save', 15); ?> Record Transaction</button>
                <a href="list.php" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>

<div>
    <div class="card" style="margin-bottom:16px;">
        <div class="card-body" style="background:#f0fdf4; border-left:4px solid #10b981; border-radius:8px;">
            <div class="fw-600" style="color:#065f46; margin-bottom:6px;"><?php echo icon('arrow-down-circle', 15); ?> IN — Add Stock</div>
            <p class="text-muted" style="font-size:13px; margin:0;">Increases current stock. Use when receiving goods from a supplier.</p>
        </div>
    </div>
    <div class="card" style="margin-bottom:16px;">
        <div class="card-body" style="background:#fef2f2; border-left:4px solid #ef4444; border-radius:8px;">
            <div class="fw-600" style="color:#991b1b; margin-bottom:6px;"><?php echo icon('arrow-up-circle', 15); ?> OUT — Remove Stock</div>
            <p class="text-muted" style="font-size:13px; margin:0;">Decreases current stock. Use when selling or consuming goods.</p>
        </div>
    </div>
    <div class="card">
        <div class="card-body" style="background:#eff6ff; border-left:4px solid #3b82f6; border-radius:8px;">
            <div class="fw-600" style="color:#1e40af; margin-bottom:6px;"><?php echo icon('sliders', 15); ?> ADJUSTMENT — Set Exact Amount</div>
            <p class="text-muted" style="font-size:13px; margin:0;">Sets stock to exact value. Use after a manual stock count.</p>
        </div>
    </div>
</div>
</div>

<script>
const stockData = <?php
    $map = [];
    if ($allProducts) foreach ($allProducts as $p) {
        $inv = $inventory->getInventoryByProductId($p['product_id']);
        $map[$p['product_id']] = $inv ? $inv['current_stock'] : 0;
    }
    echo json_encode($map);
?>;
function loadStock(id) {
    const box = document.getElementById('stock-info');
    const disp = document.getElementById('stock-display');
    if (id && stockData[id] !== undefined) { disp.textContent = stockData[id]; box.style.display = 'block'; }
    else { box.style.display = 'none'; }
}
window.addEventListener('load', () => { const s = document.querySelector('[name=product_id]'); if (s.value) loadStock(s.value); });
</script>

<?php require_once '../../includes/layout-end.php'; ?>
