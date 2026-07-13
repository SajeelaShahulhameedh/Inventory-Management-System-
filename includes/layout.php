<?php
/**
 * SHARED LAYOUT - Sidebar + Topbar
 * Include at the top of every page
 * Required vars: $pageTitle, $activeMenu, $cssPath, $basePath
 */
// Ensure session is started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Generate CSRF token for forms
if (empty($_SESSION['csrf_token'])) {
    try {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    } catch (Exception $e) {
        // Fallback
        $_SESSION['csrf_token'] = bin2hex(openssl_random_pseudo_bytes(32));
    }
}

// Resolve asset base path safely
$assetBase = '';
if (isset($basePath) && $basePath !== '') {
    // ensure trailing slash
    $assetBase = rtrim($basePath, '/') . '/';
} else {
    // default to project-root relative path
    $assetBase = '../';
}

// Allow pages to override full paths by setting $cssPath or $jsPath
$resolvedCss = isset($cssPath) ? $cssPath : ($assetBase . 'assets/css/style.css');
$resolvedJs  = isset($jsPath)  ? $jsPath  : ($assetBase . 'assets/js/script.js');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($pageTitle ?? 'Inventory System'); ?></title>
    <link rel="stylesheet" href="<?php echo htmlspecialchars($resolvedCss); ?>">
</head>
<body>

<!-- SIDEBAR -->
<aside class="sidebar">
    <div class="sidebar-brand">
        <h2>📦 InvenTrack</h2>
        <p>Inventory Management</p>
    </div>

    <ul class="sidebar-menu">
        <li class="sidebar-section-title">Main Menu</li>

        <li>
            <a href="<?php echo $basePath ?? '../'; ?>index.php"
               class="<?php echo ($activeMenu === 'dashboard') ? 'active' : ''; ?>">
                <span class="menu-icon">🏠</span> Dashboard
            </a>
        </li>

        <li class="sidebar-section-title">Inventory</li>

        <li>
            <a href="<?php echo $basePath ?? '../'; ?>pages/products/list.php"
               class="<?php echo ($activeMenu === 'products') ? 'active' : ''; ?>">
                <span class="menu-icon">📦</span> Products
            </a>
        </li>

        <li>
            <a href="<?php echo $basePath ?? '../'; ?>pages/inventory/list.php"
               class="<?php echo ($activeMenu === 'inventory') ? 'active' : ''; ?>">
                <span class="menu-icon">🗂️</span> Stock Levels
            </a>
        </li>

        <li>
            <a href="<?php echo $basePath ?? '../'; ?>pages/inventory/add-transaction.php"
               class="<?php echo ($activeMenu === 'transaction') ? 'active' : ''; ?>">
                <span class="menu-icon">🔄</span> Stock Transaction
            </a>
        </li>

        <li>
            <a href="<?php echo $basePath ?? '../'; ?>pages/inventory/low-stock.php"
               class="<?php echo ($activeMenu === 'lowstock') ? 'active' : ''; ?>">
                <span class="menu-icon">⚠️</span> Low Stock Alert
            </a>
        </li>

        <li class="sidebar-section-title">Management</li>

        <li>
            <a href="<?php echo $basePath ?? '../'; ?>pages/suppliers/list.php"
               class="<?php echo ($activeMenu === 'suppliers') ? 'active' : ''; ?>">
                <span class="menu-icon">🏢</span> Suppliers
            </a>
        </li>

        <li>
            <a href="<?php echo $basePath ?? '../'; ?>pages/reports/index.php"
               class="<?php echo ($activeMenu === 'reports') ? 'active' : ''; ?>">
                <span class="menu-icon">📊</span> Reports
            </a>
        </li>

        <li>
            <a href="<?php echo $basePath ?? '../'; ?>pages/categories/list.php"
               class="<?php echo ($activeMenu === 'categories') ? 'active' : ''; ?>">
                <span class="menu-icon">🗂️</span> Categories
            </a>
        </li>
    </ul>

    <div class="sidebar-footer">
        &copy; 2024 InvenTrack System
    </div>
</aside>

<!-- MAIN WRAPPER -->
<div class="main-wrapper">

    <!-- TOPBAR -->
    <div class="topbar">
        <div>
            <div class="topbar-title"><?php echo htmlspecialchars($pageTitle ?? 'Dashboard'); ?></div>
            <div class="topbar-subtitle"><?php echo htmlspecialchars($pageSubtitle ?? 'Inventory Management System'); ?></div>
        </div>
        <div class="topbar-right">
            <span class="topbar-date">📅 <?php echo date('D, d M Y'); ?></span>
        </div>
    </div>

    <!-- PAGE CONTENT STARTS -->
    <div class="page-content">
