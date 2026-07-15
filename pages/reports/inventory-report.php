<?php
require_once '../../config/database.php';
require_once '../../classes/Inventory.php';

$inventory = new Inventory($conn);
$allInventory = $inventory->getAllInventory();
$totalValue = 0; $totalUnits = 0;
if ($allInventory) foreach ($allInventory as $i) { $totalValue += $i['current_stock'] * $i['unit_price']; $totalUnits += $i['current_stock']; }

$pageTitle = 'Inventory Report'; $pageSubtitle = 'Generated on ' . date('d M Y');
$activeMenu = 'reports'; $cssPath = '../../assets/css/style.css';
$jsPath = '../../assets/js/script.js'; $basePath = '../../';
require_once '../../includes/layout.php';
?>

<div class="page-header no-print">
    <div><h1>Inventory Stock Report</h1><p>Generated: <?php echo date('d F Y, g:i A'); ?></p></div>
    <div style="display:flex;gap:10px;">
        <button onclick="window.print()" class="btn btn-primary no-print"><?php echo icon('printer', 15); ?> Print</button>
        <a href="index.php" class="btn btn-secondary no-print"><?php echo icon('arrow-left', 15); ?> Back</a>
    </div>
</div>

<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon blue"><?php echo icon('box', 22); ?></div>
        <div class="stat-info"><div class="stat-label">Total Products</div><div class="stat-number"><?php echo $allInventory ? count($allInventory) : 0; ?></div></div>
    </div>
    <div class="stat-card">
        <div class="stat-icon green"><?php echo icon('building', 22); ?></div>
        <div class="stat-info"><div class="stat-label">Total Units</div><div class="stat-number"><?php echo number_format($totalUnits); ?></div></div>
    </div>
    <div class="stat-card">
        <div class="stat-icon yellow"><?php echo icon('dollar', 22); ?></div>
        <div class="stat-info"><div class="stat-label">Total Value</div><div class="stat-number" style="font-size:18px;">Rs. <?php echo number_format($totalValue, 2); ?></div></div>
    </div>
</div>

<div class="card">
    <div class="card-header"><h3>Complete Stock List</h3></div>
    <div class="table-responsive">
        <?php if ($allInventory): ?>
        <table>
            <thead><tr><th>#</th><th>Product</th><th>Code</th><th>Stock</th><th>Min</th><th>Max</th><th>Unit Price</th><th>Stock Value</th><th>Status</th></tr></thead>
            <tbody>
                <?php $i=1; foreach ($allInventory as $item):
                    $val = $item['current_stock'] * $item['unit_price'];
                    if ($item['current_stock'] <= 0) { $badge='badge-danger'; $status='Out of Stock'; }
                    elseif ($item['current_stock'] <= $item['minimum_stock']) { $badge='badge-warning'; $status='Low Stock'; }
                    elseif ($item['current_stock'] >= $item['maximum_stock']) { $badge='badge-info'; $status='Overstocked'; }
                    else { $badge='badge-success'; $status='Normal'; }
                ?>
                <tr>
                    <td><?php echo $i++; ?></td>
                    <td><span class="fw-600"><?php echo htmlspecialchars($item['product_name']); ?></span></td>
                    <td><code><?php echo htmlspecialchars($item['product_code']); ?></code></td>
                    <td><span class="fw-bold"><?php echo $item['current_stock']; ?></span></td>
                    <td><?php echo $item['minimum_stock']; ?></td>
                    <td><?php echo $item['maximum_stock']; ?></td>
                    <td>Rs. <?php echo number_format($item['unit_price'], 2); ?></td>
                    <td>Rs. <?php echo number_format($val, 2); ?></td>
                    <td><span class="badge <?php echo $badge; ?>"><?php echo $status; ?></span></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr style="background:#1e2a3a; color:white; font-weight:700;">
                    <td colspan="3">TOTAL</td>
                    <td><?php echo number_format($totalUnits); ?> units</td>
                    <td colspan="3"></td>
                    <td>Rs. <?php echo number_format($totalValue, 2); ?></td>
                    <td></td>
                </tr>
            </tfoot>
        </table>
        <?php else: ?>
        <div class="card-body"><div class="alert alert-info">No inventory data found.</div></div>
        <?php endif; ?>
    </div>
</div>

<?php require_once '../../includes/layout-end.php'; ?>
