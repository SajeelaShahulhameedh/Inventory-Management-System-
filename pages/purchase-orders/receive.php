<?php
/**
 * RECEIVE PURCHASE ORDER
 * Marks a pending order as received and adds the ordered quantity to
 * stock through Inventory::addTransaction(), so it also shows up in the
 * product's normal transaction history (type 'IN').
 */

require_once '../../config/database.php';
require_once '../../classes/PurchaseOrder.php';
require_once '../../classes/Inventory.php';

if (session_status() === PHP_SESSION_NONE) session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: list.php");
    exit;
}

if (!hash_equals($_SESSION['csrf_token'] ?? '', $_POST['csrf_token'] ?? '')) {
    header("Location: list.php?message=invalid_token");
    exit;
}

$order_id = (int)($_POST['order_id'] ?? 0);
$po = new PurchaseOrder($conn);
$order = $po->getById($order_id);

if (!$order || $order['status'] !== 'PENDING') {
    header("Location: list.php");
    exit;
}

// Only flip the status if it's still PENDING (guards against double-submits)
if ($po->updateStatus($order_id, 'RECEIVED')) {
    $inventory = new Inventory($conn);
    $inventory->addTransaction(
        $order['product_id'],
        'IN',
        (int)$order['quantity'],
        'Received from purchase order #' . $order_id . ' (' . $order['supplier_name'] . ')'
    );
    header("Location: list.php?message=received");
} else {
    header("Location: list.php");
}
exit;
