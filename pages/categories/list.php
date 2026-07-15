<?php
require_once '../../config/database.php';
require_once '../../classes/Category.php';

$catObj = new Category($conn);
$categories = $catObj->getAll();

$pageTitle = 'Categories'; $pageSubtitle = 'Manage product categories';
$activeMenu = 'categories'; $cssPath = '../../assets/css/style.css';
$jsPath = '../../assets/js/script.js'; $basePath = '../../';
require_once '../../includes/layout.php';
?>

<div class="page-header">
    <div>
        <h1>Categories</h1>
        <p>Organize products by category</p>
    </div>
    <a href="add.php" class="btn btn-primary">+ New Category</a>
</div>

<?php if (empty($categories)): ?>
    <div class="card"><div class="card-body"><div class="alert alert-info">No categories found. <a href="add.php">Create one <?php echo icon('chevron-right', 13); ?></a></div></div></div>
<?php else: ?>
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr><th>Category Name</th><th>Description</th><th>Created</th><th>Actions</th></tr>
                    </thead>
                    <tbody>
                        <?php foreach ($categories as $c): ?>
                        <tr>
                            <td class="fw-600"><?php echo htmlspecialchars($c['category_name']); ?></td>
                            <td><?php echo nl2br(htmlspecialchars($c['description'] ?? '')); ?></td>
                            <td><?php echo htmlspecialchars($c['created_at'] ?? ''); ?></td>
                            <td>
                                <a href="edit.php?id=<?php echo $c['category_id']; ?>" class="btn btn-sm btn-warning">Edit</a>
                                <a href="delete.php?id=<?php echo $c['category_id']; ?>" class="btn btn-sm btn-danger">Delete</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
<?php endif; ?>

<?php require_once '../../includes/layout-end.php'; ?>
