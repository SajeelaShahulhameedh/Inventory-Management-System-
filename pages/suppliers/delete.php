<?php
/**
 * DELETE SUPPLIER PAGE (uses shared layout)
 * Consistent UI, CSRF-protected, shows a clear error if the supplier
 * still has products assigned to it (blocked by the FK constraint).
 */

require_once '../../config/database.php';
require_once '../../classes/Supplier.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$supplier = new Supplier($conn);
$supplier_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($supplier_id <= 0) {
    header("Location: list.php");
    exit;
}

$supplierData = $supplier->getSupplierById($supplier_id);
if (!$supplierData) {
    header("Location: list.php");
    exit;
}

$errorMsg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirm_delete'])) {
    if (!hash_equals($_SESSION['csrf_token'] ?? '', $_POST['csrf_token'] ?? '')) {
        $errorMsg = 'Invalid CSRF token. Please reload the page and try again.';
    } else {
        if ($supplier->deleteSupplier($supplier_id)) {
            header("Location: list.php?message=deleted");
            exit;
        } else {
            $errorMsg = 'This supplier could not be deleted. It likely still has products assigned to it — reassign or remove those products first.';
        }
    }
}

$pageTitle = 'Delete Supplier';
$pageSubtitle = htmlspecialchars($supplierData['supplier_name']);
$activeMenu = 'suppliers';
$cssPath = '../../assets/css/style.css';
$jsPath = '../../assets/js/script.js';
$basePath = '../../';
require_once '../../includes/layout.php';
?>

<div class="page-header">
    <div>
        <h1>Delete Supplier</h1>
        <p>Are you sure you want to remove this supplier permanently?</p>
    </div>
    <a href="list.php" class="btn btn-secondary"><?php echo icon('arrow-left', 15); ?> Back</a>
</div>

<?php if ($errorMsg): ?>
    <div class="alert alert-danger"><?php echo icon('alert-circle', 16); ?> <?php echo htmlspecialchars($errorMsg); ?></div>
<?php endif; ?>

<div class="card">
    <div class="card-header"><h3>Supplier to Delete</h3></div>
    <div class="card-body">
        <p><strong>Name:</strong> <?php echo htmlspecialchars($supplierData['supplier_name']); ?></p>
        <p><strong>Contact:</strong> <?php echo htmlspecialchars($supplierData['contact_person'] ?? 'N/A'); ?></p>
        <p><strong>Email:</strong> <?php echo htmlspecialchars($supplierData['email'] ?? 'N/A'); ?></p>
        <p><strong>Phone:</strong> <?php echo htmlspecialchars($supplierData['phone'] ?? 'N/A'); ?></p>

        <div class="alert alert-warning" style="margin-top:16px;">
            <?php echo icon('alert-triangle', 16); ?>
            <span>Deleting this supplier cannot be undone. If any products are still linked to it, the deletion will be blocked.</span>
        </div>

        <div style="margin-top:18px;">
            <form method="POST">
                <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token'] ?? ''); ?>">
                <div style="display:flex;gap:10px;align-items:center;">
                    <button type="submit" name="confirm_delete" value="1" class="btn btn-danger">Yes, delete supplier</button>
                    <a href="list.php" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require_once '../../includes/layout-end.php'; ?>
