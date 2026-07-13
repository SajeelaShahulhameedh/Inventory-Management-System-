<?php
require_once '../../config/database.php';
require_once '../../classes/Category.php';

if (session_status() === PHP_SESSION_NONE) session_start();

$cat = new Category($conn);
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // CSRF
    if (!hash_equals($_SESSION['csrf_token'] ?? '', $_POST['csrf_token'] ?? '')) {
        $message = '<div class="alert alert-danger">Invalid CSRF token. Please reload and try again.</div>';
    } else {
        $cat->category_name = trim($_POST['category_name'] ?? '');
        $cat->description = trim($_POST['description'] ?? '');
        if ($cat->category_name === '') {
            $message = '<div class="alert alert-danger">Category name is required.</div>';
        } elseif ($cat->add()) {
            $message = '<div class="alert alert-success">Category created. <a href="list.php">Back to list</a></div>';
            header('Refresh:2; url=list.php');
            exit;
        } else {
            $message = '<div class="alert alert-danger">Error creating category (duplicate or DB error).</div>';
        }
    }
}

$pageTitle = 'Add Category'; $pageSubtitle = 'Create a new category';
$activeMenu = 'categories'; $cssPath = '../../assets/css/style.css';
$jsPath = '../../assets/js/script.js'; $basePath = '../../';
require_once '../../includes/layout.php';
?>

<div class="page-header">
  <div><h1>Add Category</h1><p>Create a new product category</p></div>
  <a href="list.php" class="btn btn-secondary">← Back</a>
</div>

<?php echo $message; ?>

<div class="card">
  <div class="card-body">
    <form method="POST">
      <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token'] ?? ''); ?>">
      <div class="form-group">
        <label>Category Name <span class="text-danger">*</span></label>
        <input type="text" name="category_name" required>
      </div>
      <div class="form-group">
        <label>Description</label>
        <textarea name="description"></textarea>
      </div>
      <div style="display:flex;gap:10px;">
        <button class="btn btn-primary" type="submit">Create</button>
        <a href="list.php" class="btn btn-secondary">Cancel</a>
      </div>
    </form>
  </div>
</div>

<?php require_once '../../includes/layout-end.php'; ?>
