<?php
require_once '../../config/database.php';
require_once '../../classes/Supplier.php';

$supplier = new Supplier($conn);
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';
$suppliers = !empty($searchTerm) ? $supplier->searchSuppliers($searchTerm) : $supplier->getAllSuppliers();

$pageTitle = 'Suppliers';
$pageSubtitle = 'Manage your supplier list';
$activeMenu = 'suppliers';
$cssPath = '../../assets/css/style.css';
$jsPath = '../../assets/js/script.js';
$basePath = '../../';
require_once '../../includes/layout.php';
?>

<div class="page-header">
    <div>
        <h1>Suppliers</h1>
        <p><?php echo $suppliers ? count($suppliers) : 0; ?> supplier(s) found</p>
    </div>
    <a href="add.php" class="btn btn-primary"><?php echo icon('plus', 15); ?> Add Supplier</a>
</div>

<div class="card">
    <div class="card-header">
        <h3>All Suppliers</h3>
        <form method="GET" style="display:flex; gap:8px;">
            <input type="text" name="search" placeholder="Search suppliers..." value="<?php echo htmlspecialchars($searchTerm); ?>" style="width:220px;">
            <button type="submit" class="btn btn-primary btn-sm">Search</button>
            <?php if (!empty($searchTerm)): ?><a href="list.php" class="btn btn-secondary btn-sm">Clear</a><?php endif; ?>
        </form>
    </div>
    <div class="table-responsive">
        <?php if ($suppliers): ?>
        <table>
            <thead><tr><th>Supplier Name</th><th>Contact Person</th><th>Email</th><th>Phone</th><th>City</th><th>Actions</th></tr></thead>
            <tbody>
                <?php foreach ($suppliers as $sup): ?>
                <tr>
                    <td><span class="fw-600"><?php echo htmlspecialchars($sup['supplier_name']); ?></span></td>
                    <td><?php echo htmlspecialchars($sup['contact_person'] ?? '—'); ?></td>
                    <td><?php echo htmlspecialchars($sup['email'] ?? '—'); ?></td>
                    <td><?php echo htmlspecialchars($sup['phone'] ?? '—'); ?></td>
                    <td><?php echo htmlspecialchars($sup['city'] ?? '—'); ?></td>
                    <td>
                        <div class="action-buttons">
                            <a href="view.php?id=<?php echo $sup['supplier_id']; ?>" class="btn btn-sm btn-secondary">View</a>
                            <a href="edit.php?id=<?php echo $sup['supplier_id']; ?>" class="btn btn-sm btn-primary">Edit</a>
                            <a href="delete.php?id=<?php echo $sup['supplier_id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete <?php echo htmlspecialchars($sup['supplier_name']); ?>?')">Delete</a>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php else: ?>
        <div class="card-body">
            <div class="alert alert-info">No suppliers found. <a href="add.php">Add your first supplier <?php echo icon('chevron-right', 13); ?></a></div>
        </div>
        <?php endif; ?>
    </div>
</div>

<?php require_once '../../includes/layout-end.php'; ?>
