<link rel="stylesheet" href="sidebarhover.css">
<nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block bg-primary sidebar collapse">
    <div class="position-sticky pt-3">
        <?php
        // Check if user is logged in and is not an admin
        if (isset($_SESSION["user_id"]) && (!isset($_SESSION["is_admin"]) || $_SESSION["is_admin"] !== true)):
            ?>
            <!-- User Profile Section - Only shown for regular users -->
          
            <hr class="text-white">
        <?php endif; ?>

        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : ''; ?>"
                    href="index.php">
                    <i class="fas fa-chart-line"></i>
                    Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'banner.php' ? 'active' : ''; ?>"
                    href="banner.php">
                    <i class="fas fa-image"></i>
                    Banner
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'product.php' ? 'active' : ''; ?>"
                    href="product.php">
                    <i class="fas fa-box"></i>
                    Products
                </a>
            </li>


            <?php if (checkPermission('view_sales') || checkPermission('view_own_sales')): ?>
                <li class="nav-item">
                    <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'sales.php' ? 'active' : ''; ?>"
                        href="sales.php">
                        <i class="fas fa-shopping-cart"></i>
                        Sales
                    </a>
                </li>
            <?php endif; ?>

            <?php if (checkPermission('view_stock')): ?>
                <li class="nav-item">
                    <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'stock.php' ? 'active' : ''; ?>"
                        href="stock.php">
                        <i class="fas fa-cubes"></i>
                        Stock
                    </a>
                </li>
            <?php endif; ?>

            <?php if (checkPermission('view_suppliers')): ?>
                <li class="nav-item">
                    <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'supplier.php' ? 'active' : ''; ?>"
                        href="supplier.php">
                        <i class="fas fa-truck"></i>
                        Suppliers
                    </a>
                </li>
            <?php endif; ?>

            <?php if (checkPermission('view_reports')): ?>
                <li class="nav-item">
                    <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'reports.php' ? 'active' : ''; ?>"
                        href="reports.php">
                        <i class="fas fa-chart-bar"></i>
                        Reports
                    </a>
                </li>
            <?php endif; ?>
        </ul>

        <!-- <?php if (checkPermission('admin_access')): ?>
            <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted">
                <span>Administration</span>
            </h6>
            <ul class="nav flex-column mb-2">
                <?php if (checkPermission('manage_users')): ?>
                    <li class="nav-item">
                        <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'users.php' ? 'active' : ''; ?>"
                            href="users.php">
                            <i class="fas fa-user"></i>
                            Users
                        </a>
                    </li>
                <?php endif; ?>

                <?php if (checkPermission('manage_roles')): ?>
                    <li class="nav-item">
                        <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'roles.php' ? 'active' : ''; ?>"
                            href="roles.php">
                            <i class="fas fa-shield-alt"></i>
                            Roles & Permissions
                        </a>
                    </li>
                <?php endif; ?>

                <?php if (checkPermission('view_system_logs')): ?>
                    <li class="nav-item">
                        <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'logs.php' ? 'active' : ''; ?>"
                            href="logs.php">
                            <i class="fas fa-file-alt"></i>
                            System Logs
                        </a>
                    </li>
                <?php endif; ?>

                <?php if (checkPermission('system_settings')): ?>
                    <li class="nav-item">
                        <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'settings.php' ? 'active' : ''; ?>"
                            href="settings.php">
                            <i class="fas fa-cog"></i>
                            Settings
                        </a>
                    </li>
                <?php endif; ?> -->
            </ul>
        <?php endif; ?>
    </div>
</nav>