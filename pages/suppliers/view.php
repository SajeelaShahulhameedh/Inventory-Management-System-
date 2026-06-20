<?php
/**
 * VIEW SUPPLIER PAGE
 * Display detailed supplier information
 */

require_once '../../config/database.php';
require_once '../../classes/Supplier.php';

$supplier = new Supplier($conn);
$supplier_id = $_GET['id'] ?? 0;

if ($supplier_id <= 0) {
    header("Location: list.php");
    exit;
}

$supplierData = $supplier->getSupplierById($supplier_id);

if (!$supplierData) {
    header("Location: list.php");
    exit;
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($supplierData['supplier_name']); ?> - Inventory Management System</title>
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
            <!-- PAGE TITLE -->
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1><?php echo htmlspecialchars($supplierData['supplier_name']); ?></h1>
                    <p class="text-muted">Supplier Profile</p>
                </div>
                <div>
                    <a href="edit.php?id=<?php echo $supplierData['supplier_id']; ?>" class="btn btn-warning">Edit</a>
                    <a href="list.php" class="btn btn-secondary">Back</a>
                </div>
            </div>

            <!-- SUPPLIER DETAILS -->
            <div class="grid-2">
                <!-- Contact Information -->
                <div class="card">
                    <div class="card-title">Contact Information</div>
                    <div class="card-body">
                        <p>
                            <strong>Contact Person:</strong><br>
                            <?php echo htmlspecialchars($supplierData['contact_person'] ?? 'Not provided'); ?>
                        </p>
                        <p>
                            <strong>Email:</strong><br>
                            <?php if (!empty($supplierData['email'])): ?>
                                <a href="mailto:<?php echo htmlspecialchars($supplierData['email']); ?>">
                                    <?php echo htmlspecialchars($supplierData['email']); ?>
                                </a>
                            <?php else: ?>
                                Not provided
                            <?php endif; ?>
                        </p>
                        <p>
                            <strong>Phone:</strong><br>
                            <?php if (!empty($supplierData['phone'])): ?>
                                <a href="tel:<?php echo htmlspecialchars($supplierData['phone']); ?>">
                                    <?php echo htmlspecialchars($supplierData['phone']); ?>
                                </a>
                            <?php else: ?>
                                Not provided
                            <?php endif; ?>
                        </p>
                    </div>
                </div>

                <!-- Address Information -->
                <div class="card">
                    <div class="card-title">Address</div>
                    <div class="card-body">
                        <p>
                            <?php 
                            $address = [];
                            if (!empty($supplierData['address'])) $address[] = htmlspecialchars($supplierData['address']);
                            if (!empty($supplierData['city'])) $address[] = htmlspecialchars($supplierData['city']);
                            if (!empty($supplierData['state'])) $address[] = htmlspecialchars($supplierData['state']);
                            if (!empty($supplierData['zip_code'])) $address[] = htmlspecialchars($supplierData['zip_code']);
                            
                            echo !empty($address) ? implode('<br>', $address) : 'Not provided';
                            ?>
                        </p>
                    </div>
                </div>
            </div>

            <!-- ACTION BUTTONS -->
            <div style="margin: 30px 0; display: flex; gap: 10px;">
                <a href="edit.php?id=<?php echo $supplierData['supplier_id']; ?>" class="btn btn-warning">Edit Supplier</a>
                <a href="delete.php?id=<?php echo $supplierData['supplier_id']; ?>" class="btn btn-danger" onclick="return confirmDelete('<?php echo htmlspecialchars($supplierData['supplier_name']); ?>')">Delete Supplier</a>
            </div>
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
