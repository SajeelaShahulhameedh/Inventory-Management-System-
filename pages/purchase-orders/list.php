<?php
require_once '../../config/database.php';
require_once '../../classes/PurchaseOrder.php';

if (session_status() === PHP_SESSION_NONE) session_start();

$po = new PurchaseOrder($conn);
$orders = $po->getAll();

$pageTitle = 'Purchase Orders'; $pageSubtitle = 'Restock orders placed with suppliers';
$activeMenu = 'purchase-orders'; $cssPath = '../../assets/css/style.css';
$jsPath = '../../assets/js/script.js'; $basePath = '../../';
require_once '../../includes/layout.php';

$statusBadge = [
    'PENDING'   => 'badge-warning',
    'RECEIVED'  => 'badge-success',
    'CANCELLED' => 'badge-danger',
];
?>

<div class="page-header">
    <div><h1>Purchase Orders</h1><p>Track restock orders placed with your suppliers</p></div>
    <a href="add.php" class="btn btn-primary"><?php echo icon('plus', 15); ?> New Purchase Order</a>
</div>

<?php if (isset($_GET['message']) && $_GET['message'] === 'received'): ?>
    <div class="alert alert-success"><?php echo icon('check-circle', 16); ?> Order marked as received, stock has been updated.</div>
<?php elseif (isset($_GET['message']) && $_GET['message'] === 'cancelled'): ?>
    <div class="alert alert-info"><?php echo icon('info', 16); ?> Order cancelled.</div>
<?php endif; ?>

<div class="card">
    <div class="card-header"><h3>All Orders</h3></div>
    <div class="card-body">
        <?php if (!$orders): ?>
            <p class="text-muted">No purchase orders yet. <a href="add.php">Place your first order</a> <?php echo icon('chevron-right', 13); ?></p>
        <?php else: ?>
        <div style="overflow-x:auto;">
        <table>
            <thead>
                <tr>
                    <th>Order #</th>
                    <th>Product</th>
                    <th>Supplier</th>
                    <th>Quantity</th>
                    <th>Order Date</th>
                    <th>Expected Delivery</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($orders as $o): ?>
                <tr>
                    <td>#<?php echo (int)$o['order_id']; ?></td>
                    <td><?php echo htmlspecialchars($o['product_name']); ?> <span class="text-muted">(<?php echo htmlspecialchars($o['product_code']); ?>)</span></td>
                    <td><?php echo htmlspecialchars($o['supplier_name']); ?></td>
                    <td><?php echo (int)$o['quantity']; ?></td>
                    <td><?php echo date('d M Y', strtotime($o['order_date'])); ?></td>
                    <td><?php echo !empty($o['expected_delivery']) ? date('d M Y', strtotime($o['expected_delivery'])) : '—'; ?></td>
                    <td><span class="badge <?php echo $statusBadge[$o['status']] ?? 'badge-gray'; ?>"><?php echo htmlspecialchars($o['status']); ?></span></td>
                    <td>
                        <?php if ($o['status'] === 'PENDING'): ?>
                            <form method="POST" action="receive.php" style="display:inline-block;" onsubmit="return confirm('Mark this order as received? This will add <?php echo (int)$o['quantity']; ?> units to stock.');">
                                <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token'] ?? ''); ?>">
                                <input type="hidden" name="order_id" value="<?php echo (int)$o['order_id']; ?>">
                                <button type="submit" class="btn btn-sm btn-primary"><?php echo icon('check-circle', 13); ?> Receive</button>
                            </form>
                            <form method="POST" action="cancel.php" style="display:inline-block;" onsubmit="return confirm('Cancel this purchase order?');">
                                <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token'] ?? ''); ?>">
                                <input type="hidden" name="order_id" value="<?php echo (int)$o['order_id']; ?>">
                                <button type="submit" class="btn btn-sm btn-secondary">Cancel</button>
                            </form>
                        <?php else: ?>
                            <span class="text-muted">—</span>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        </div>
        <?php endif; ?>
    </div>
</div>

<?php require_once '../../includes/layout-end.php'; ?>
