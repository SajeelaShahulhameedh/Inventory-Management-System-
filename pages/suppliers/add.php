<?php
require_once '../../config/database.php';
require_once '../../classes/Supplier.php';

$supplier = new Supplier($conn);
$message = ''; $messageType = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $supplier->supplier_name  = trim($_POST['supplier_name']);
    $supplier->contact_person = trim($_POST['contact_person']);
    $supplier->email          = trim($_POST['email']);
    $supplier->phone          = trim($_POST['phone']);
    $supplier->address        = trim($_POST['address']);
    $supplier->city           = trim($_POST['city']);
    $supplier->state          = trim($_POST['state']);
    $supplier->zip_code       = trim($_POST['zip_code']);

    if (empty($supplier->supplier_name)) {
        $message = 'Supplier name is required.'; $messageType = 'danger';
    } elseif ($supplier->addSupplier()) {
        $message = 'Supplier added successfully!'; $messageType = 'success';
        $supplier->supplier_name = $supplier->contact_person = $supplier->email = '';
        $supplier->phone = $supplier->address = $supplier->city = $supplier->state = $supplier->zip_code = '';
    } else {
        $message = 'Failed to add supplier. Please try again.'; $messageType = 'danger';
    }
}

$pageTitle = 'Add Supplier'; $pageSubtitle = 'Add a new supplier to the system';
$activeMenu = 'suppliers'; $cssPath = '../../assets/css/style.css';
$jsPath = '../../assets/js/script.js'; $basePath = '../../';
require_once '../../includes/layout.php';
?>

<div class="page-header">
    <div><h1>➕ Add New Supplier</h1><p>Fill in the details below</p></div>
    <a href="list.php" class="btn btn-secondary">← Back to Suppliers</a>
</div>

<?php if (!empty($message)): ?>
<div class="alert alert-<?php echo $messageType; ?>">
    <?php echo $message; ?>
    <?php if ($messageType === 'success'): ?><a href="list.php" style="margin-left:10px;">View All →</a><?php endif; ?>
</div>
<?php endif; ?>

<div class="card">
    <div class="card-header"><h3>Supplier Details</h3></div>
    <div class="card-body">
        <form method="POST">
            <div class="form-row">
                <div class="form-group">
                    <label>Supplier Name <span class="text-danger">*</span></label>
                    <input type="text" name="supplier_name" value="<?php echo htmlspecialchars($supplier->supplier_name ?? ''); ?>" placeholder="Company name" required>
                </div>
                <div class="form-group">
                    <label>Contact Person</label>
                    <input type="text" name="contact_person" value="<?php echo htmlspecialchars($supplier->contact_person ?? ''); ?>" placeholder="Contact person name">
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label>Email Address</label>
                    <input type="email" name="email" value="<?php echo htmlspecialchars($supplier->email ?? ''); ?>" placeholder="email@example.com">
                </div>
                <div class="form-group">
                    <label>Phone Number</label>
                    <input type="text" name="phone" value="<?php echo htmlspecialchars($supplier->phone ?? ''); ?>" placeholder="+1 234 567 890">
                </div>
            </div>
            <div class="form-group">
                <label>Address</label>
                <textarea name="address" placeholder="Full address"><?php echo htmlspecialchars($supplier->address ?? ''); ?></textarea>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label>City</label>
                    <input type="text" name="city" value="<?php echo htmlspecialchars($supplier->city ?? ''); ?>" placeholder="City">
                </div>
                <div class="form-group">
                    <label>State / Province</label>
                    <input type="text" name="state" value="<?php echo htmlspecialchars($supplier->state ?? ''); ?>" placeholder="State">
                </div>
            </div>
            <div class="form-group" style="max-width:300px;">
                <label>ZIP / Postal Code</label>
                <input type="text" name="zip_code" value="<?php echo htmlspecialchars($supplier->zip_code ?? ''); ?>" placeholder="ZIP code">
            </div>
            <div style="display:flex; gap:10px; margin-top:8px;">
                <button type="submit" class="btn btn-primary">💾 Save Supplier</button>
                <a href="list.php" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>

<?php require_once '../../includes/layout-end.php'; ?>
