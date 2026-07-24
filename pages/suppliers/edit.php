<?php
require_once '../../config/database.php';
require_once '../../classes/Supplier.php';

if (session_status() === PHP_SESSION_NONE) session_start();

$supplier = new Supplier($conn);
$supplier_id = $_GET['id'] ?? 0;
$message = '';
if ($supplier_id <= 0) { header("Location: list.php"); exit; }
$supplierData = $supplier->getSupplierById($supplier_id);
if (!$supplierData) { header("Location: list.php"); exit; }

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!hash_equals($_SESSION['csrf_token'] ?? '', $_POST['csrf_token'] ?? '')) {
        $message = '<div class="alert alert-danger">Invalid CSRF token. Please reload and try again.</div>';
    } else {
    $supplier->supplier_id = $supplier_id;
    $supplier->supplier_name = $_POST['supplier_name'] ?? '';
    $supplier->contact_person = $_POST['contact_person'] ?? '';
    $supplier->email = $_POST['email'] ?? '';
    $supplier->phone = $_POST['phone'] ?? '';
    $supplier->address = $_POST['address'] ?? '';
    $supplier->city = $_POST['city'] ?? '';
    $supplier->state = $_POST['state'] ?? '';
    $supplier->zip_code = $_POST['zip_code'] ?? '';
    if ($supplier->updateSupplier()) {
        $message = '<div class="alert alert-success">Supplier updated successfully!</div>';
        $supplierData = $supplier->getSupplierById($supplier_id);
    } else {
        $message = '<div class="alert alert-danger">Error updating supplier. Please try again.</div>';
    }
    }
}

$pageTitle = 'Edit Supplier'; $pageSubtitle = htmlspecialchars($supplierData['supplier_name']);
$activeMenu = 'suppliers'; $cssPath = '../../assets/css/style.css';
$jsPath = '../../assets/js/script.js'; $basePath = '../../';
require_once '../../includes/layout.php';
?>

<div class="page-header">
    <div><h1>Edit Supplier</h1><p><?php echo htmlspecialchars($supplierData['supplier_name']); ?></p></div>
    <a href="view.php?id=<?php echo $supplier_id; ?>" class="btn btn-secondary"><?php echo icon('arrow-left', 15); ?> Back</a>
</div>

<?php echo $message; ?>

<div class="card">
    <div class="card-header"><h3>Supplier Details</h3></div>
    <div class="card-body">
        <form method="POST">
            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token'] ?? ''); ?>">
            <div class="form-row">
                <div class="form-group">
                    <label>Supplier Name <span class="text-danger">*</span></label>
                    <input type="text" id="supplier_name" name="supplier_name" value="<?php echo htmlspecialchars($supplierData['supplier_name']); ?>" required>
                </div>
                <div class="form-group">
                    <label>Contact Person</label>
                    <input type="text" name="contact_person" value="<?php echo htmlspecialchars($supplierData['contact_person'] ?? ''); ?>">
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($supplierData['email'] ?? ''); ?>">
                </div>
                <div class="form-group">
                    <label>Phone</label>
                    <input type="text" id="phone" name="phone" value="<?php echo htmlspecialchars($supplierData['phone'] ?? ''); ?>">
                </div>
            </div>
            <div class="form-group">
                <label>Street Address</label>
                <input type="text" name="address" value="<?php echo htmlspecialchars($supplierData['address'] ?? ''); ?>">
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label>City</label>
                    <input type="text" name="city" value="<?php echo htmlspecialchars($supplierData['city'] ?? ''); ?>">
                </div>
                <div class="form-group">
                    <label>State / Province</label>
                    <input type="text" name="state" value="<?php echo htmlspecialchars($supplierData['state'] ?? ''); ?>">
                </div>
            </div>
            <div class="form-group" style="max-width:300px;">
                <label>ZIP / Postal Code</label>
                <input type="text" name="zip_code" value="<?php echo htmlspecialchars($supplierData['zip_code'] ?? ''); ?>">
            </div>
            <div style="display:flex;gap:10px;margin-top:8px;">
                <button type="submit" class="btn btn-primary">Update Supplier</button>
                <a href="view.php?id=<?php echo $supplier_id; ?>" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>

<?php require_once '../../includes/layout-end.php'; ?>
