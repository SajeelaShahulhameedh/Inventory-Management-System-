<?php
/**
 * PRODUCTS LIST PAGE
 * Display all products in a table format
 */

require_once '../../config/database.php';
require_once '../../classes/Product.php';

$product = new Product($conn);

// Get search parameter
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';

// Get products
if (!empty($searchTerm)) {
    $products = $product->searchProducts($searchTerm);
    $pageTitle = "Search Results for: " . htmlspecialchars($searchTerm);
} else {
    $products = $product->getAllProducts();
    $pageTitle = "All Products";
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle; ?> - Inventory Management System</title>
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
            <li><a href="list.php" class="active">Products</a></li>
            <li><a href="../../pages/inventory/list.php">Inventory</a></li>
            <li><a href="../../pages/suppliers/list.php">Suppliers</a></li>
            <li><a href="../../pages/reports/index.php">Reports</a></li>
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
                    <p class="text-muted">Total: <?php echo $products ? count($products) : 0; ?> product(s)</p>
                </div>
                <a href="add.php" class="btn btn-primary">+ Add New Product</a>
            </div>

            <!-- SEARCH & FILTER -->
            <div style="margin: 20px 0; display: flex; gap: 10px; align-items: center;">
                <form method="GET" style="display: flex; gap: 10px; flex-grow: 1;">
                    <input 
                        type="text" 
                        name="search" 
                        placeholder="Search by product name or code..." 
                        value="<?php echo htmlspecialchars($searchTerm); ?>"
                        style="flex-grow: 1;"
                    >
                    <button type="submit" class="btn btn-primary">Search</button>
                    <?php if (!empty($searchTerm)): ?>
                        <a href="list.php" class="btn btn-secondary">Clear</a>
                    <?php endif; ?>
                </form>
            </div>

            <!-- PRODUCTS TABLE -->
            <?php if ($products): ?>
            <div style="overflow-x: auto;">
                <table>
                    <thead>
                        <tr>
                            <th>Product Name</th>
                            <th>Product Code</th>
                            <th>Category</th>
                            <th>Supplier</th>
                            <th>Unit Price</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($products as $prod): ?>
                        <tr>
                            <td>
                                <strong><?php echo htmlspecialchars($prod['product_name']); ?></strong>
                                <?php if (!empty($prod['description'])): ?>
                                    <br><span class="text-muted" style="font-size: 0.85em;">
                                        <?php echo substr(htmlspecialchars($prod['description']), 0, 50); ?>...
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td><code><?php echo htmlspecialchars($prod['product_code']); ?></code></td>
                            <td><?php echo htmlspecialchars($prod['category_name'] ?? 'N/A'); ?></td>
                            <td><?php echo htmlspecialchars($prod['supplier_name'] ?? 'N/A'); ?></td>
                            <td>$<?php echo number_format($prod['unit_price'], 2); ?></td>
                            <td>
                                <div class="action-buttons">
                                    <a href="view.php?id=<?php echo $prod['product_id']; ?>" class="btn btn-small btn-primary">View</a>
                                    <a href="edit.php?id=<?php echo $prod['product_id']; ?>" class="btn btn-small btn-warning">Edit</a>
                                    <a href="delete.php?id=<?php echo $prod['product_id']; ?>" class="btn btn-small btn-danger" onclick="return confirmDelete('<?php echo htmlspecialchars($prod['product_name']); ?>')">Delete</a>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php else: ?>
                <div class="alert alert-info">
                    <strong>No products found.</strong> 
                    <?php if (!empty($searchTerm)): ?>
                        Try a different search term or <a href="list.php">view all products</a>.
                    <?php else: ?>
                        <a href="add.php">Add your first product</a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
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
