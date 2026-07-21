<?php
/**
 * EDIT CATEGORY PAGE (uses shared layout)
 * Consistent UI, CSRF-protected
 */

require_once '../../config/database.php';
require_once '../../classes/Category.php';

if (session_status() === PHP_SESSION_NONE) session_start();

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

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!hash_equals($_SESSION['csrf_token'] ?? '', $_POST['csrf_token'] ?? '')) {
        $message = '<div class="alert alert-danger">Invalid CSRF token. Please reload and try again.</div>';
    } else {
        $cat->category_id = $category_id;
        $cat->category_name = trim($_POST['category_name'] ?? '');
        $cat->description = trim($_POST['description'] ?? '');

        if ($cat->category_name === '') {
            $message = '<div class="alert alert-danger">Category name is required.</div>';
        } elseif ($cat->update()) {
            $message = '<div class="alert alert-success">Category updated successfully!</div>';
            $categoryData = $cat->getById($category_id);
        } else {
            $message = '<div class="alert alert-danger">Error updating category. Please try again.</div>';
        }
    }
}

$pageTitle = 'Edit Category'; $pageSubtitle = htmlspecialchars($categoryData['category_name']);
$activeMenu = 'categories'; $cssPath = '../../assets/css/style.css';
$jsPath = '../../assets/js/script.js'; $basePath = '../../';
require_once '../../includes/layout.php';
?>

<div class="page-header">
    <div><h1>Edit Category</h1><p><?php echo htmlspecialchars($categoryData['category_name']); ?></p></div>
    <a href="list.php" class="btn btn-secondary"><?php echo icon('arrow-left', 15); ?> Back</a>
</div>

<?php echo $message; ?>

<div class="card">
    <div class="card-header"><h3>Category Details</h3></div>
    <div class="card-body">
        <form method="POST">
            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token'] ?? ''); ?>">
            <div class="form-group">
                <label>Category Name <span class="text-danger">*</span></label>
                <input type="text" name="category_name" value="<?php echo htmlspecialchars($categoryData['category_name']); ?>" required>
            </div>
            <div class="form-group">
                <label>Description</label>
                <textarea name="description"><?php echo htmlspecialchars($categoryData['description'] ?? ''); ?></textarea>
            </div>
            <div style="display:flex;gap:10px;margin-top:8px;">
                <button type="submit" class="btn btn-primary"><?php echo icon('save', 15); ?> Update Category</button>
                <a href="list.php" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>

<?php require_once '../../includes/layout-end.php'; ?>
