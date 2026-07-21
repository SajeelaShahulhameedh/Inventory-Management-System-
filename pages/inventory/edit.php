<?php
require_once '../../config/database.php';
require_once '../../classes/Inventory.php';

$inventory = new Inventory($conn);
$inventory_id = $_GET['id'] ?? 0;
$message = '';
if ($inventory_id <= 0) { header("Location: list.php"); exit; }
$inventoryData = $inventory->getInventoryById($inventory_id);
if (!$inventoryData) { header("Location: list.php"); exit; }

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $inventory->inventory_id = $inventory_id;
    $inventory->current_stock = $_POST['current_stock'] ?? 0;
    $inventory->minimum_stock = $_POST['minimum_stock'] ?? 0;
    $inventory->maximum_stock = $_POST['maximum_stock'] ?? 0;
    if ($inventory->updateInventory()) {
        $message = '<div class="alert alert-success">Inventory updated successfully!</div>';
        $inventoryData = $inventory->getInventoryById($inventory_id);
    } else {
        $message = '<div class="alert alert-danger">Error updating inventory. Please try again.</div>';
    }
}

$pageTitle = 'Edit Inventory'; $pageSubtitle = htmlspecialchars($inventoryData['product_name']);
$activeMenu = 'inventory'; $cssPath = '../../assets/css/style.css';
$jsPath = '../../assets/js/script.js'; $basePath = '../../';
require_once '../../includes/layout.php';
?>

<div class="page-header">
    <div><h1>Edit Inventory</h1><p><?php echo htmlspecialchars($inventoryData['product_name']); ?></p></div>
    <a href="list.php" class="btn btn-secondary"><?php echo icon('arrow-left', 15); ?> Back to Inventory</a>
</div>

<?php echo $message; ?>

<div class="card">
    <div class="card-header"><h3>Stock Level Settings</h3></div>
    <div class="card-body">
        <form method="POST">
            <div class="form-row">
                <div class="form-group">
                    <label>Current Stock <span class="text-danger">*</span></label>
                    <input type="number" id="current_stock" name="current_stock" value="<?php echo htmlspecialchars($inventoryData['current_stock']); ?>" min="0" required>
                </div>
                <div class="form-group">
                    <label>Minimum Stock Level <span class="text-danger">*</span></label>
                    <input type="number" id="minimum_stock" name="minimum_stock" value="<?php echo htmlspecialchars($inventoryData['minimum_stock']); ?>" min="0" required>
                </div>
                <div class="form-group">
                    <label>Maximum Stock Level <span class="text-danger">*</span></label>
                    <input type="number" id="maximum_stock" name="maximum_stock" value="<?php echo htmlspecialchars($inventoryData['maximum_stock']); ?>" min="0" required>
                </div>
            </div>

            <div class="alert alert-info" style="margin-top:8px;">
                <strong>Guidelines:</strong> Current Stock = actual items in store. Minimum = alert threshold. Maximum = recommended highest stock.
            </div>

            <div style="display:flex;gap:10px;margin-top:16px;">
                <button type="submit" class="btn btn-primary">Update Inventory</button>
                <a href="list.php" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>

<?php require_once '../../includes/layout-end.php'; ?>
