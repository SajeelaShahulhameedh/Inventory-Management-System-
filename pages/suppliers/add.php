<?php
/**
 * ADD SUPPLIER PAGE
 * Form to add a new supplier
 */

require_once '../../config/database.php';
require_once '../../classes/Supplier.php';

$supplier = new Supplier($conn);
$message = '';
$messageType = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $supplier->supplier_name   = trim($_POST['supplier_name']);
    $supplier->contact_person  = trim($_POST['contact_person']);
    $supplier->email           = trim($_POST['email']);
    $supplier->phone           = trim($_POST['phone']);
    $supplier->address         = trim($_POST['address']);
    $supplier->city            = trim($_POST['city']);
    $supplier->state           = trim($_POST['state']);
    $supplier->zip_code        = trim($_POST['zip_code']);

    if (empty($supplier->supplier_name)) {
        $message = 'Supplier name is required.';
        $messageType = 'danger';
    } else {
        if ($supplier->addSupplier()) {
            $message = 'Supplier added successfully!';
            $messageType = 'success';
            // Reset fields after success
            $supplier->supplier_name = $supplier->contact_person = $supplier->email = '';
            $supplier->phone = $supplier->address = $supplier->city = '';
            $supplier->state = $supplier->zip_code = '';
        } else {
            $message = 'Failed to add supplier. Please try again.';
            $messageType = 'danger';
        }
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Supplier - Inventory Management System</title>
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
            <li><a href="../inventory/list.php">Inventory</a></li>
            <li><a href="list.php" class="active">Suppliers</a></li>
            <li><a href="../reports/index.php">Reports</a></li>
        </ul>
    </nav>

    <!-- MAIN CONTAINER -->
    <div class="container">
        <div class="content">

            <!-- PAGE TITLE -->
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1>Add New Supplier</h1>
                    <p class="text-muted">Fill in the details to add a new supplier</p>
                </div>
                <a href="list.php" class="btn btn-secondary">← Back to Suppliers</a>
            </div>

            <!-- ALERT MESSAGE -->
            <?php if (!empty($message)): ?>
                <div class="alert alert-<?php echo $messageType; ?>">
                    <?php echo $message; ?>
                    <?php if ($messageType === 'success'): ?>
                        <a href="list.php" style="margin-left: 10px;">View All Suppliers →</a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

            <!-- ADD SUPPLIER FORM -->
            <form method="POST" action="">

                <div class="form-row">
                    <div class="form-group">
                        <label for="supplier_name">Supplier Name <span class="text-danger">*</span></label>
                        <input type="text" id="supplier_name" name="supplier_name"
                            value="<?php echo htmlspecialchars($supplier->supplier_name ?? ''); ?>"
                            placeholder="Enter supplier company name" required>
                    </div>

                    <div class="form-group">
                        <label for="contact_person">Contact Person</label>
                        <input type="text" id="contact_person" name="contact_person"
                            value="<?php echo htmlspecialchars($supplier->contact_person ?? ''); ?>"
                            placeholder="Enter contact person name">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="email">Email Address</label>
                        <input type="email" id="email" name="email"
                            value="<?php echo htmlspecialchars($supplier->email ?? ''); ?>"
                            placeholder="Enter email address">
                    </div>

                    <div class="form-group">
                        <label for="phone">Phone Number</label>
                        <input type="text" id="phone" name="phone"
                            value="<?php echo htmlspecialchars($supplier->phone ?? ''); ?>"
                            placeholder="Enter phone number">
                    </div>
                </div>

                <div class="form-group">
                    <label for="address">Address</label>
                    <textarea id="address" name="address" placeholder="Enter full address"><?php echo htmlspecialchars($supplier->address ?? ''); ?></textarea>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="city">City</label>
                        <input type="text" id="city" name="city"
                            value="<?php echo htmlspecialchars($supplier->city ?? ''); ?>"
                            placeholder="Enter city">
                    </div>

                    <div class="form-group">
                        <label for="state">State / Province</label>
                        <input type="text" id="state" name="state"
                            value="<?php echo htmlspecialchars($supplier->state ?? ''); ?>"
                            placeholder="Enter state or province">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="zip_code">ZIP / Postal Code</label>
                        <input type="text" id="zip_code" name="zip_code"
                            value="<?php echo htmlspecialchars($supplier->zip_code ?? ''); ?>"
                            placeholder="Enter ZIP or postal code">
                    </div>
                </div>

                <div style="margin-top: 20px; display: flex; gap: 10px;">
                    <button type="submit" class="btn btn-primary">Save Supplier</button>
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
</body>
</html>