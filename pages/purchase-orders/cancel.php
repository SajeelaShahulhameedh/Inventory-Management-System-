<?php
/**
 * CANCEL PURCHASE ORDER
 * Marks a pending order as cancelled. No stock changes happen since
 * nothing was ever received.
 */

require_once '../../config/database.php';
require_once '../../classes/PurchaseOrder.php';

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

$po->updateStatus($order_id, 'CANCELLED');
header("Location: list.php?message=cancelled");
exit;
