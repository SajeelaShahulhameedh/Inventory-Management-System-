<?php
require_once '../../config/database.php';
require_once '../../classes/Supplier.php';

$supplier    = new Supplier($conn);
$supplier_id = $_GET['id'] ?? 0;
if ($supplier_id <= 0) { header("Location: list.php"); exit; }
$supplierData = $supplier->getSupplierById($supplier_id);
if (!$supplierData) { header("Location: list.php"); exit; }

$pageTitle = htmlspecialchars($supplierData['supplier_name']);
$pageSubtitle = 'Supplier Profile';
$activeMenu = 'suppliers'; $cssPath = '../../assets/css/style.css';
$jsPath = '../../assets/js/script.js'; $basePath = '../../';
require_once '../../includes/layout.php';
?>

<div class="page-header">
    <div><h1><?php echo htmlspecialchars($supplierData['supplier_name']); ?></h1><p>Supplier Profile</p></div>
    <div style="display:flex;gap:10px;">
        <a href="edit.php?id=<?php echo $supplierData['supplier_id']; ?>" class="btn btn-warning">Edit</a>
        <a href="list.php" class="btn btn-secondary">← Back</a>
    </div>
</div>

<div style="display:grid;grid-template-columns:1fr 1fr;gap:24px;">
    <div class="card">
        <div class="card-header"><h3>Contact Information</h3></div>
        <div class="card-body">
            <table style="margin:0;">
                <tbody>
                    <tr><td class="text-muted" style="width:140px;">Contact Person</td><td><?php echo htmlspecialchars($supplierData['contact_person'] ?? '—'); ?></td></tr>
                    <tr><td class="text-muted">Email</td><td><?php echo !empty($supplierData['email']) ? '<a href="mailto:'.htmlspecialchars($supplierData['email']).'">'.htmlspecialchars($supplierData['email']).'</a>' : '—'; ?></td></tr>
                    <tr><td class="text-muted">Phone</td><td><?php echo !empty($supplierData['phone']) ? '<a href="tel:'.htmlspecialchars($supplierData['phone']).'">'.htmlspecialchars($supplierData['phone']).'</a>' : '—'; ?></td></tr>
                </tbody>
            </table>
        </div>
    </div>
    <div class="card">
        <div class="card-header"><h3>Address</h3></div>
        <div class="card-body">
            <?php
            $parts = array_filter([
                $supplierData['address'] ?? '',
                $supplierData['city'] ?? '',
                $supplierData['state'] ?? '',
                $supplierData['zip_code'] ?? ''
            ]);
            echo !empty($parts) ? implode('<br>', array_map('htmlspecialchars', $parts)) : '<span class="text-muted">Not provided</span>';
            ?>
        </div>
    </div>
</div>

<div style="display:flex;gap:10px;margin-top:8px;">
    <a href="edit.php?id=<?php echo $supplierData['supplier_id']; ?>" class="btn btn-warning">Edit Supplier</a>
    <a href="delete.php?id=<?php echo $supplierData['supplier_id']; ?>" class="btn btn-danger" onclick="return confirm('Delete this supplier?')">Delete Supplier</a>
</div>

<?php require_once '../../includes/layout-end.php'; ?>
