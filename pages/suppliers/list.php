<?php
/**
 * SUPPLIERS LIST PAGE
 * Display all suppliers in a table format
 */

require_once '../../config/database.php';
require_once '../../classes/Supplier.php';

$supplier = new Supplier($conn);

// Get search parameter
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';

// Get suppliers
if (!empty($searchTerm)) {
    $suppliers = $supplier->searchSuppliers($searchTerm);
    $pageTitle = "Search Results for: " . htmlspecialchars($searchTerm);
} else {
    $suppliers = $supplier->getAllSuppliers();
    $pageTitle = "All Suppliers";
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle; ?> - Inventory Management System</title>
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
            <!-- Alert Container -->
            <div id="alert-container"></div>

            <!-- PAGE TITLE -->
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1><?php echo $pageTitle; ?></h1>
                    <p class="text-muted">Total: <?php echo $suppliers ? count($suppliers) : 0; ?> supplier(s)</p>
                </div>
                <a href="add.php" class="btn btn-primary">+ Add New Supplier</a>
            </div>

            <!-- SEARCH -->
            <div style="margin: 20px 0; display: flex; gap: 10px; align-items: center;">
                <form method="GET" style="display: flex; gap: 10px; flex-grow: 1;">
                    <input
                        type="text"
                        name="search"
                        placeholder="Search by name, email or phone..."
                        value="<?php echo htmlspecialchars($searchTerm); ?>"
                        style="flex-grow: 1;"
                    >
                    <button type="submit" class="btn btn-primary">Search</button>
                    <?php if (!empty($searchTerm)): ?>
                        <a href="list.php" class="btn btn-secondary">Clear</a>
                    <?php endif; ?>
                </form>
            </div>

            <!-- SUPPLIERS TABLE -->
            <?php if ($suppliers): ?>
            <div style="overflow-x: auto;">
                <table>
                    <thead>
                        <tr>
                            <th>Supplier Name</th>
                            <th>Contact Person</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>City</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($suppliers as $sup): ?>
                        <tr>
                            <td><strong><?php echo htmlspecialchars($sup['supplier_name']); ?></strong></td>
                            <td><?php echo htmlspecialchars($sup['contact_person'] ?? 'N/A'); ?></td>
                            <td><?php echo htmlspecialchars($sup['email'] ?? 'N/A'); ?></td>
                            <td><?php echo htmlspecialchars($sup['phone'] ?? 'N/A'); ?></td>
                            <td><?php echo htmlspecialchars($sup['city'] ?? 'N/A'); ?></td>
                            <td>
                                <div class="action-buttons">
                                    <a href="view.php?id=<?php echo $sup['supplier_id']; ?>" class="btn btn-small btn-primary">View</a>
                                    <a href="edit.php?id=<?php echo $sup['supplier_id']; ?>" class="btn btn-small btn-warning">Edit</a>
                                    <a href="delete.php?id=<?php echo $sup['supplier_id']; ?>" class="btn btn-small btn-danger" onclick="return confirmDelete('<?php echo htmlspecialchars($sup['supplier_name']); ?>')">Delete</a>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php else: ?>
                <div class="alert alert-info">
                    <strong>No suppliers found.</strong>
                    <?php if (!empty($searchTerm)): ?>
                        Try a different search term or <a href="list.php">view all suppliers</a>.
                    <?php else: ?>
                        <a href="add.php">Add your first supplier</a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- FOOTER -->
    <footer>
        <p>&copy; 2024 Inventory Management System. All rights reserved.</p>
    </footer>

    <script src="../../assets/js/script.js"></script>
</body>
</html>