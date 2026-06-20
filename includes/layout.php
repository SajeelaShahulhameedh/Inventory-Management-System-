<?php
/**
 * SHARED LAYOUT - Sidebar + Topbar
 * Include at the top of every page
 * Required vars: $pageTitle, $activeMenu, $cssPath, $basePath
 */
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($pageTitle ?? 'Inventory System'); ?></title>
    <link rel="stylesheet" href="<?php echo $cssPath ?? '../assets/css/style.css'; ?>">
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
