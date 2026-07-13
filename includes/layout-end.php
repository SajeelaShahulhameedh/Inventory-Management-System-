<?php
$assetBase = isset($basePath) && $basePath !== '' ? rtrim($basePath, '/') . '/' : '../';
$resolvedJs = isset($jsPath) ? $jsPath : ($assetBase . 'assets/js/script.js');
?>
</div><!-- end page-content -->

    <footer>
        <p>&copy; 2026 InvenTrack — Inventory Management System. All rights reserved.</p>
    </footer>

</div><!-- end main-wrapper -->

<script defer src="<?php echo htmlspecialchars($resolvedJs); ?>"></script>
</body>
</html>
