<?php
/**
 * DELETE CATEGORY PAGE (uses shared layout)
 * Consistent UI, CSRF-protected, blocks deletion when products still
 * reference this category (see Category::delete()).
 */

require_once '../../config/database.php';
require_once '../../classes/Category.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$cat = new Category($conn);
$category_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($category_id <= 0) {
    header("Location: list.php");
    exit;
}

$categoryData = $cat->getById($category_id);
if (!$categoryData) {
    header("Location: list.php");
    exit;
}

$errorMsg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirm_delete'])) {
    if (!hash_equals($_SESSION['csrf_token'] ?? '', $_POST['csrf_token'] ?? '')) {
        $errorMsg = 'Invalid CSRF token. Please reload the page and try again.';
    } else {
        if ($cat->delete($category_id)) {
            header("Location: list.php?message=deleted");
            exit;
        } else {
            $errorMsg = 'This category could not be deleted. It likely still has products assigned to it — move or delete those products first.';
        }
    }
}

$pageTitle = 'Delete Category';
$pageSubtitle = htmlspecialchars($categoryData['category_name']);
$activeMenu = 'categories';
$cssPath = '../../assets/css/style.css';
$jsPath = '../../assets/js/script.js';
$basePath = '../../';
require_once '../../includes/layout.php';
?>

<div class="page-header">
    <div>
        <h1>Delete Category</h1>
        <p>Are you sure you want to remove this category permanently?</p>
    </div>
    <a href="list.php" class="btn btn-secondary"><?php echo icon('arrow-left', 15); ?> Back</a>
</div>

<?php if ($errorMsg): ?>
    <div class="alert alert-danger"><?php echo icon('alert-circle', 16); ?> <?php echo htmlspecialchars($errorMsg); ?></div>
<?php endif; ?>

<div class="card">
    <div class="card-header"><h3>Category to Delete</h3></div>
    <div class="card-body">
        <p><strong>Name:</strong> <?php echo htmlspecialchars($categoryData['category_name']); ?></p>
        <p><strong>Description:</strong> <?php echo nl2br(htmlspecialchars($categoryData['description'] ?? 'N/A')); ?></p>

        <div class="alert alert-warning" style="margin-top:16px;">
            <?php echo icon('alert-triangle', 16); ?>
            <span>Deleting this category cannot be undone. If any products still belong to it, the deletion will be blocked.</span>
        </div>

        <div style="margin-top:18px;">
            <form method="POST">
                <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token'] ?? ''); ?>">
                <div style="display:flex;gap:10px;align-items:center;">
                    <button type="submit" name="confirm_delete" value="1" class="btn btn-danger">Yes, delete category</button>
                    <a href="list.php" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require_once '../../includes/layout-end.php'; ?>
