<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventory Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="navbar-styles.css">
    <style>
        :root {
            --primary-color: #2E8B57;
            --primary-hover: #3CB371;
            --light-green: #98FB98;
        }

        body {
            font-family: 'Poppins', sans-serif;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .bg-primary, .btn-primary {
            background-color: var(--primary-color) !important;
            border-color: var(--primary-color) !important;
        }

        .text-primary {
            color: var(--primary-color) !important;
        }

        .btn-primary:hover {
            background-color: var(--primary-hover) !important;
            border-color: var(--primary-hover) !important;
        }

        .nav-link.active {
            background-color: var(--light-green) !important;
            color: var(--primary-color) !important;
            font-weight: 500;
        }

        .navbar-nav .nav-link {
            color: rgba(255, 255, 255, 0.8);
            padding: 0.5rem 1rem;
            margin: 0 0.2rem;
            border-radius: 4px;
            transition: all 0.2s;
        }

        .navbar-nav .nav-link:hover {
            color: #fff;
            background-color: rgba(255, 255, 255, 0.1);
        }

        .navbar-brand i {
            margin-right: 0.5rem;
        }

        .main-content {
            flex: 1;
            padding: 2rem 0;
        }

        .dropdown-menu {
            border: none;
            box-shadow: 0 0.5rem 1rem rgba(0,0,0,0.15);
        }

        footer {
            margin-top: auto;
        }
    </style>
</head>

<body>
    <!-- Top Navigation Bar -->
    <header>
        <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
            <div class="container-fluid">
                <!-- Brand -->
                <a class="navbar-brand" href="index.php">
                    <i class="fas fa-box"></i> Inventory System
                </a>
                
                <!-- Toggle Button for Mobile -->
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent"
                    aria-controls="navbarContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                
                <!-- Navigation Links -->
                <div class="collapse navbar-collapse" id="navbarContent">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <li class="nav-item">
                            <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : ''; ?>" 
                               href="index.php">
                                <i class="fas fa-chart-line"></i> Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'banner.php' ? 'active' : ''; ?>" 
                               href="banner.php">
                                <i class="fas fa-image"></i> Banner
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'product.php' ? 'active' : ''; ?>" 
                               href="product.php">
                                <i class="fas fa-box"></i> Products
                            </a>
                        </li>
                        
                        <?php if (checkPermission('view_sales') || checkPermission('view_own_sales')): ?>
                        <li class="nav-item">
                            <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'sales.php' ? 'active' : ''; ?>" 
                               href="sales.php">
                                <i class="fas fa-shopping-cart"></i> Sales
                            </a>
                        </li>
                        <?php endif; ?>
                        
                        <?php if (checkPermission('view_stock')): ?>
                        <li class="nav-item">
                            <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'stock.php' ? 'active' : ''; ?>" 
                               href="stock.php">
                                <i class="fas fa-cubes"></i> Stock
                            </a>
                        </li>
                        <?php endif; ?>
                        
                        <?php if (checkPermission('view_suppliers')): ?>
                        <li class="nav-item">
                            <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'supplier.php' ? 'active' : ''; ?>" 
                               href="supplier.php">
                                <i class="fas fa-truck"></i> Suppliers
                            </a>
                        </li>
                        <?php endif; ?>
                        
                        <?php if (checkPermission('view_reports')): ?>
                        <li class="nav-item">
                            <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'reports.php' ? 'active' : ''; ?>" 
                               href="reports.php">
                                <i class="fas fa-chart-bar"></i> Reports
                            </a>
                        </li>
                        <?php endif; ?>
                        
                        <?php if (checkPermission('admin_access')): ?>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="adminDropdown" role="button"
                               data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-user-shield"></i> Admin
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="adminDropdown">
                                <?php if (checkPermission('manage_users')): ?>
                                <li>
                                    <a class="dropdown-item <?php echo basename($_SERVER['PHP_SELF']) == 'users.php' ? 'active' : ''; ?>" 
                                       href="users.php">
                                        <i class="fas fa-user"></i> Users
                                    </a>
                                </li>
                                <?php endif; ?>
                                
                                <?php if (checkPermission('manage_roles')): ?>
                                <li>
                                    <a class="dropdown-item <?php echo basename($_SERVER['PHP_SELF']) == 'roles.php' ? 'active' : ''; ?>" 
                                       href="roles.php">
                                        <i class="fas fa-shield-alt"></i> Roles & Permissions
                                    </a>
                                </li>
                                <?php endif; ?>
                                
                                <?php if (checkPermission('view_system_logs')): ?>
                                <li>
                                    <a class="dropdown-item <?php echo basename($_SERVER['PHP_SELF']) == 'logs.php' ? 'active' : ''; ?>" 
                                       href="logs.php">
                                        <i class="fas fa-file-alt"></i> System Logs
                                    </a>
                                </li>
                                <?php endif; ?>
                                
                                <?php if (checkPermission('system_settings')): ?>
                                <li>
                                    <a class="dropdown-item <?php echo basename($_SERVER['PHP_SELF']) == 'settings.php' ? 'active' : ''; ?>" 
                                       href="settings.php">
                                        <i class="fas fa-cog"></i> Settings
                                    </a>
                                </li>
                                <?php endif; ?>
                            </ul>
                        </li>
                        <?php endif; ?>
                    </ul>
                    
                    <!-- Right-aligned items -->
                    <div class="d-flex">
                        <a href="cart.php" class="btn btn-light position-relative me-2">
                            <i class="fas fa-shopping-cart"></i>
                            <?php if (isset($_SESSION['cart']) && count($_SESSION['cart']) > 0): 
                                $cartItemCount = 0;
                                foreach ($_SESSION['cart'] as $item) {
                                    $cartItemCount += $item['quantity'];
                                }
                            ?>
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                <?php echo $cartItemCount; ?>
                            </span>
                            <?php endif; ?>
                        </a>
                        <div class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle text-white" href="#" id="userDropdown" role="button"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-user-circle me-1"></i>
                                <?php echo isset($_SESSION['username']) ? htmlspecialchars($_SESSION['username']) : 'Account'; ?>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                                <li><a class="dropdown-item" href="profile.php"><i class="fas fa-user me-2"></i>Profile</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#logoutModal">
                                        <i class="fas fa-sign-out-alt me-2"></i>Sign out
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </nav>
    </header>

    <!-- Logout Modal -->
    <div class="modal fade" id="logoutModal" tabindex="-1" aria-labelledby="logoutModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-3 shadow" style="max-width: 320px; margin: 0 auto;">
                <div class="modal-body p-4">
                    <h4 class="mb-3 text-center text-primary fw-medium">Sign out?</h4>
                    <div class="border-bottom mb-3"></div>
                    <p class="text-center text-muted mb-4">Are you sure you want to sign out? You will need to log in
                        again to access the system.</p>

                    <div class="d-flex justify-content-between">
                        <button type="button" class="btn btn-light flex-grow-1 me-2 rounded-pill border"
                            data-bs-dismiss="modal">
                            No
                        </button>
                        <a href="logout.php" class="btn btn-primary flex-grow-1 ms-2 rounded-pill">
                            Yes
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>