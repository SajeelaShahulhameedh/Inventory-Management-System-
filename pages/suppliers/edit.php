<?php
/**
 * EDIT SUPPLIER PAGE
 * Form to edit supplier details
 */

require_once '../../config/database.php';
require_once '../../classes/Supplier.php';

$supplier = new Supplier($conn);
$supplier_id = $_GET['id'] ?? 0;
$message = '';

if ($supplier_id <= 0) {
    header("Location: list.php");
    exit;
}

$supplierData = $supplier->getSupplierById($supplier_id);

if (!$supplierData) {
    header("Location: list.php");
    exit;
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $supplier->supplier_id = $supplier_id;
    $supplier->supplier_name = $_POST['supplier_name'] ?? '';
    $supplier->contact_person = $_POST['contact_person'] ?? '';
    $supplier->email = $_POST['email'] ?? '';
    $supplier->phone = $_POST['phone'] ?? '';
    $supplier->address = $_POST['address'] ?? '';
    $supplier->city = $_POST['city'] ?? '';
    $supplier->state = $_POST['state'] ?? '';
    $supplier->zip_code = $_POST['zip_code'] ?? '';

    if ($supplier->updateSupplier()) {
        $message = '<div class="alert alert-success">✓ Supplier updated successfully!</div>';
        $supplierData = $supplier->getSupplierById($supplier_id);
    } else {
        $message = '<div class="alert alert-danger">✗ Error updating supplier. Please try again.</div>';
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Supplier - Inventory Management System</title>
    <
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
            <li><a href="../../pages/inventory/list.php">Inventory</a></li>
            <li><a href="list.php" class="active">Suppliers</a></li>
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
            <h1>Edit Supplier</h1>
            <p class="text-muted">Update supplier information</p>

            <!-- EDIT SUPPLIER FORM -->
            <form method="POST" id="editSupplierForm" onsubmit="return validateSupplierForm()">
                
                <!-- Supplier Name -->
                <div class="form-group">
                    <label for="supplier_name">Supplier Name *</label>
                    <input 
                        type="text" 
                        id="supplier_name" 
                        name="supplier_name" 
                        value="<?php echo htmlspecialchars($supplierData['supplier_name']); ?>"
                        required
                    >
                </div>

                <!-- Contact Person -->
                <div class="form-group">
                    <label for="contact_person">Contact Person</label>
                    <input 
                        type="text" 
                        id="contact_person" 
                        name="contact_person" 
                        value="<?php echo htmlspecialchars($supplierData['contact_person'] ?? ''); ?>"
                    >
                </div>

                <div class="form-row">
                    <!-- Email -->
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input 
                            type="email" 
                            id="email" 
                            name="email" 
                            value="<?php echo htmlspecialchars($supplierData['email'] ?? ''); ?>"
                        >
                    </div>

                    <!-- Phone -->
                    <div class="form-group">
                        <label for="phone">Phone</label>
                        <input 
                            type="text" 
                            id="phone" 
                            name="phone" 
                            value="<?php echo htmlspecialchars($supplierData['phone'] ?? ''); ?>"
                        >
                    </div>
                </div>

                <!-- Address -->
                <div class="form-group">
                    <label for="address">Street Address</label>
                    <input 
                        type="text" 
                        id="address" 
                        name="address" 
                        value="<?php echo htmlspecialchars($supplierData['address'] ?? ''); ?>"
                    >
                </div>

                <div class="form-row">
                    <!-- City -->
                    <div class="form-group">
                        <label for="city">City</label>
                        <input 
                            type="text" 
                            id="city" 
                            name="city" 
                            value="<?php echo htmlspecialchars($supplierData['city'] ?? ''); ?>"
                        >
                    </div>

                    <!-- State -->
                    <div class="form-group">
                        <label for="state">State/Province</label>
                        <input 
                            type="text" 
                            id="state" 
                            name="state" 
                            value="<?php echo htmlspecialchars($supplierData['state'] ?? ''); ?>"
                        >
                    </div>

                    <!-- Zip Code -->
                    <div class="form-group">
                        <label for="zip_code">Zip/Postal Code</label>
                        <input 
                            type="text" 
                            id="zip_code" 
                            name="zip_code" 
                            value="<?php echo htmlspecialchars($supplierData['zip_code'] ?? ''); ?>"
                        >
                    </div>
                </div>

                <!-- Form Actions -->
                <div style="display: flex; gap: 10px; margin-top: 30px;">
                    <button type="submit" class="btn btn-primary">Update Supplier</button>
                    <a href="view.php?id=<?php echo $supplier_id; ?>" class="btn btn-secondary">Cancel</a>
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
