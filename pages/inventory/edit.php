<?php
/**
 * INVENTORY EDIT PAGE
 * Form to edit inventory levels
 */

require_once '../../config/database.php';
require_once '../../classes/Inventory.php';

$inventory = new Inventory($conn);
$inventory_id = $_GET['id'] ?? 0;
$message = '';

if ($inventory_id <= 0) {
    header("Location: list.php");
    exit;
}

$inventoryData = $inventory->getInventoryById($inventory_id);

if (!$inventoryData) {
    header("Location: list.php");
    exit;
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $inventory->inventory_id = $inventory_id;
    $inventory->current_stock = $_POST['current_stock'] ?? 0;
    $inventory->minimum_stock = $_POST['minimum_stock'] ?? 0;
    $inventory->maximum_stock = $_POST['maximum_stock'] ?? 0;

    if ($inventory->updateInventory()) {
        $message = '<div class="alert alert-success">✓ Inventory updated successfully!</div>';
        $inventoryData = $inventory->getInventoryById($inventory_id);
    } else {
        $message = '<div class="alert alert-danger">✗ Error updating inventory. Please try again.</div>';
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Inventory - Inventory Management System</title>
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
            <li><a href="../../pages/products/list.php">Products</a></li>
            <li><a href="list.php" class="active">Inventory</a></li>
            <li><a href="../../pages/suppliers/list.php">Suppliers</a></li>
            <li><a href="../../pages/reports/index.php">Reports</a></li>
        </ul>
    </nav>

    <!-- MAIN CONTAINER -->
    <div class="container">
        <div class="content">
            <!-- Alert Container -->
            <div id="alert-container">
                <?php echo $message; ?>
            </div>

            <!-- PAGE TITLE -->
            <h1>Edit Inventory: <?php echo htmlspecialchars($inventoryData['product_name']); ?></h1>
            <p class="text-muted">Update stock levels and thresholds</p>

            <!-- EDIT INVENTORY FORM -->
            <form method="POST" id="editInventoryForm" onsubmit="return validateInventoryForm()">
                
                <div class="form-row">
                    <!-- Current Stock -->
                    <div class="form-group">
                        <label for="current_stock">Current Stock *</label>
                        <input 
                            type="number" 
                            id="current_stock" 
                            name="current_stock" 
                            value="<?php echo htmlspecialchars($inventoryData['current_stock']); ?>"
                            min="0"
                            required
                        >
                    </div>

                    <!-- Minimum Stock -->
                    <div class="form-group">
                        <label for="minimum_stock">Minimum Stock Level *</label>
                        <input 
                            type="number" 
                            id="minimum_stock" 
                            name="minimum_stock" 
                            value="<?php echo htmlspecialchars($inventoryData['minimum_stock']); ?>"
                            min="0"
                            required
                        >
                    </div>

                    <!-- Maximum Stock -->
                    <div class="form-group">
                        <label for="maximum_stock">Maximum Stock Level *</label>
                        <input 
                            type="number" 
                            id="maximum_stock" 
                            name="maximum_stock" 
                            value="<?php echo htmlspecialchars($inventoryData['maximum_stock']); ?>"
                            min="0"
                            required
                        >
                    </div>
                </div>

                <!-- Info Box -->
                <div style="background-color: #d1ecf1; border-left: 4px solid #0c5460; padding: 15px; border-radius: 5px; margin: 20px 0;">
                    <strong style="color: #0c5460;">💡 Stock Level Guidelines:</strong>
                    <ul style="margin: 10px 0 0 0; padding-left: 20px; color: #0c5460;">
                        <li><strong>Current Stock:</strong> The actual number of items in stock</li>
                        <li><strong>Minimum Level:</strong> Alert when stock falls below this level</li>
                        <li><strong>Maximum Level:</strong> Recommended highest stock to maintain</li>
                    </ul>
                </div>

                <!-- Form Actions -->
                <div style="display: flex; gap: 10px; margin-top: 30px;">
                    <button type="submit" class="btn btn-primary">Update Inventory</button>
                    <a href="list.php" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>

    <!-- FOOTER -->
    <footer>
        <p>&copy; 2024 Inventory Management System. All rights reserved.</p>
    </footer>

    <!-- SCRIPTS -->
    <script src="../../assets/js/script.js"></script>
</body>
</html>
