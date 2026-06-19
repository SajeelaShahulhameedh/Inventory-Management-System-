<?php
/**
 * ADD INVENTORY TRANSACTION PAGE
 * Add stock IN, OUT or ADJUSTMENT
 */

require_once '../../config/database.php';
require_once '../../classes/Inventory.php';
require_once '../../classes/Product.php';

$inventory = new Inventory($conn);
$product   = new Product($conn);

$message = '';
$messageType = '';

// Pre-select product if coming from list/dashboard
$preSelectedProduct = isset($_GET['product_id']) ? (int)$_GET['product_id'] : 0;

// Get all products for dropdown
$allProducts = $product->getAllProducts();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $productId = (int)$_POST['product_id'];
    $type      = $_POST['transaction_type'];
    $quantity  = (int)$_POST['quantity'];
    $notes     = trim($_POST['notes']);

    if ($productId <= 0 || $quantity <= 0 || empty($type)) {
        $message = 'Please fill in all required fields with valid values.';
        $messageType = 'danger';
    } else {
        // Check if OUT exceeds current stock
        if ($type === 'OUT') {
            $inv = $inventory->getInventoryByProductId($productId);
            if ($inv && $quantity > $inv['current_stock']) {
                $message = 'Cannot remove more stock than currently available (' . $inv['current_stock'] . ' units).';
                $messageType = 'danger';
            }
        }

        if (empty($message)) {
            if ($inventory->addTransaction($productId, $type, $quantity, $notes)) {
                $message = 'Stock transaction recorded successfully!';
                $messageType = 'success';
                $preSelectedProduct = 0;
            } else {
                $message = 'Failed to record transaction. Please try again.';
                $messageType = 'danger';
            }
        }
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Stock Transaction - Inventory Management System</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../../assets/css/style.css">
</head>
<body>
    <!-- HEADER -->
    <header>
        <h1>📦 Inventory Management System</h1>
    </header>

    <!-- NAVIGATION -->
    <nav>
        <ul>
            <li><a href="../../index.php">Dashboard</a></li>
            <li><a href="../products/list.php">Products</a></li>
            <li><a href="list.php" class="active">Inventory</a></li>
            <li><a href="../suppliers/list.php">Suppliers</a></li>
            <li><a href="../reports/index.php">Reports</a></li>
        </ul>
    </nav>

    <!-- MAIN CONTAINER -->
    <div class="container">
        <div class="content">

            <!-- PAGE TITLE -->
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1>Add Stock Transaction</h1>
                    <p class="text-muted">Record stock movements — IN, OUT, or Adjustment</p>
                </div>
                <a href="list.php" class="btn btn-secondary">← Back to Inventory</a>
            </div>

            <!-- ALERT MESSAGE -->
            <?php if (!empty($message)): ?>
                <div class="alert alert-<?php echo $messageType; ?>">
                    <?php echo $message; ?>
                    <?php if ($messageType === 'success'): ?>
                        <a href="list.php" style="margin-left: 10px;">View Inventory →</a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

            <!-- TRANSACTION FORM -->
            <form method="POST" action="">

                <div class="form-group">
                    <label for="product_id">Product <span class="text-danger">*</span></label>
                    <select id="product_id" name="product_id" required onchange="loadStockInfo(this.value)">
                        <option value="">-- Select Product --</option>
                        <?php if ($allProducts): ?>
                            <?php foreach ($allProducts as $prod): ?>
                                <option value="<?php echo $prod['product_id']; ?>"
                                    <?php echo ($preSelectedProduct == $prod['product_id']) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($prod['product_name']); ?> (<?php echo htmlspecialchars($prod['product_code']); ?>)
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>

                <!-- Current Stock Info (shown after product selection) -->
                <div id="stock-info" style="display:none; margin-bottom: 20px; padding: 15px; background-color: #f8f9fa; border-radius: 8px; border-left: 4px solid #3498db;">
                    <strong>Current Stock:</strong> <span id="current-stock-display">--</span> units
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="transaction_type">Transaction Type <span class="text-danger">*</span></label>
                        <select id="transaction_type" name="transaction_type" required>
                            <option value="">-- Select Type --</option>
                            <option value="IN">📥 IN — Add Stock</option>
                            <option value="OUT">📤 OUT — Remove Stock</option>
                            <option value="ADJUSTMENT">🔧 ADJUSTMENT — Set Exact Amount</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="quantity">Quantity <span class="text-danger">*</span></label>
                        <input type="number" id="quantity" name="quantity" min="1"
                            placeholder="Enter quantity" required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="notes">Notes / Reason</label>
                    <textarea id="notes" name="notes" placeholder="Optional: Enter notes about this transaction (e.g. Purchase from supplier, Sold to customer, Damaged goods)"></textarea>
                </div>

                <!-- Type explanation boxes -->
                <div style="margin: 20px 0; display: flex; gap: 15px; flex-wrap: wrap;">
                    <div style="flex: 1; min-width: 200px; padding: 12px; background-color: #d4edda; border-radius: 6px; border-left: 4px solid #27ae60;">
                        <strong style="color: #155724;">📥 IN</strong>
                        <p style="margin: 5px 0 0 0; font-size: 0.85em; color: #155724;">Adds quantity to current stock. Use when receiving goods from supplier.</p>
                    </div>
                    <div style="flex: 1; min-width: 200px; padding: 12px; background-color: #f8d7da; border-radius: 6px; border-left: 4px solid #e74c3c;">
                        <strong style="color: #721c24;">📤 OUT</strong>
                        <p style="margin: 5px 0 0 0; font-size: 0.85em; color: #721c24;">Removes quantity from current stock. Use when selling or consuming goods.</p>
                    </div>
                    <div style="flex: 1; min-width: 200px; padding: 12px; background-color: #d1ecf1; border-radius: 6px; border-left: 4px solid #3498db;">
                        <strong style="color: #0c5460;">🔧 ADJUSTMENT</strong>
                        <p style="margin: 5px 0 0 0; font-size: 0.85em; color: #0c5460;">Sets stock to exact value. Use for manual correction after stock count.</p>
                    </div>
                </div>

                <div style="margin-top: 20px; display: flex; gap: 10px;">
                    <button type="submit" class="btn btn-primary">Record Transaction</button>
                    <a href="list.php" class="btn btn-secondary">Cancel</a>
                </div>

            </form>
        </div>
    </div>

    <!-- FOOTER -->
    <footer>
        <p>&copy; 2024 Inventory Management System. All rights reserved.</p>
    </footer>

    <script src="../../assets/js/script.js"></script>
    <script>
        // Stock data from PHP
        const stockData = <?php
            $stockMap = [];
            if ($allProducts) {
                foreach ($allProducts as $prod) {
                    $inv = $inventory->getInventoryByProductId($prod['product_id']);
                    $stockMap[$prod['product_id']] = $inv ? $inv['current_stock'] : 0;
                }
            }
            echo json_encode($stockMap);
        ?>;

        function loadStockInfo(productId) {
            const infoBox = document.getElementById('stock-info');
            const display = document.getElementById('current-stock-display');
            if (productId && stockData[productId] !== undefined) {
                display.textContent = stockData[productId];
                infoBox.style.display = 'block';
            } else {
                infoBox.style.display = 'none';
            }
        }

        // Auto-load if product was pre-selected
        window.addEventListener('load', function() {
            const sel = document.getElementById('product_id');
            if (sel.value) loadStockInfo(sel.value);
        });
    </script>
</body>
</html>