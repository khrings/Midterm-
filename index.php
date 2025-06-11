<?php
include 'models/authentication.php';
require_once "helpers/permissions.php";
require_once "config/database.php";
require_once "config/connect.php";

// Initialize database connection
$database = new Database();
$db = $database->getConnection();

// Function to get total products count
function getTotalProducts($db) {
    $query = "SELECT COUNT(*) as total FROM products WHERE is_active = 1";
    $stmt = $db->prepare($query);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    return $row['total'] ?? 0;
}

// Function to get total sales amount
function getTotalSales($db) {
    $query = "SELECT SUM(total_amount) as total FROM sales";
    $stmt = $db->prepare($query);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    return $row['total'] ?? 0;
}

// Function to get low stock items count
function getLowStockCount($db) {
    $query = "SELECT COUNT(*) as total FROM products p 
              INNER JOIN stock s ON p.product_id = s.product_id 
              WHERE s.current_quantity <= p.minimum_stock_level 
              AND s.current_quantity > 0 
              AND p.is_active = 1";
    $stmt = $db->prepare($query);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    return $row['total'] ?? 0;
}

// Function to get recent sales
function getRecentSales($db, $limit = 5) {
    $query = "SELECT s.sale_id, s.invoice_number, s.sale_date, s.total_amount, c.name as customer_name, u.username 
              FROM sales s 
              LEFT JOIN customers c ON s.customer_id = c.customer_id
              INNER JOIN users u ON s.user_id = u.user_id
              ORDER BY s.sale_date DESC 
              LIMIT :limit";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Function to get low stock items
function getLowStockItems($db, $limit = 5) {
    $query = "SELECT p.product_id, p.name, p.sku, s.current_quantity, p.minimum_stock_level 
              FROM products p 
              INNER JOIN stock s ON p.product_id = s.product_id 
              WHERE s.current_quantity <= p.minimum_stock_level 
              AND p.is_active = 1 
              ORDER BY (p.minimum_stock_level - s.current_quantity) DESC 
              LIMIT :limit";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Get data for dashboard
$totalProducts = getTotalProducts($db);
$totalSales = getTotalSales($db);
$lowStockCount = getLowStockCount($db);
$recentSales = getRecentSales($db);
$lowStockItems = getLowStockItems($db);

include "views/header.php";
?>

<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col">
            <h1 class="h3 text-primary">Dashboard</h1>
            <p class="text-muted">Welcome to the Inventory Management System</p>
        </div>
    </div>
    
    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body d-flex align-items-center">
                    <div class="flex-shrink-0 bg-primary bg-opacity-10 p-3 rounded-3 me-3">
                        <i class="fas fa-box fa-2x text-primary"></i>
                    </div>
                    <div>
                        <h6 class="text-muted mb-1">Total Products</h6>
                        <h2 class="mb-0"><?php echo number_format($totalProducts); ?></h2>
                    </div>
                </div>
                <div class="card-footer border-0 bg-transparent">
                    <a href="product.php" class="text-primary text-decoration-none small">
                        View all products <i class="fas fa-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body d-flex align-items-center">
                    <div class="flex-shrink-0 bg-success bg-opacity-10 p-3 rounded-3 me-3">
                        <i class="fas fa-dollar-sign fa-2x text-success"></i>
                    </div>
                    <div>
                        <h6 class="text-muted mb-1">Total Sales</h6>
                        <h2 class="mb-0">$<?php echo number_format($totalSales, 2); ?></h2>
                    </div>
                </div>
                <div class="card-footer border-0 bg-transparent">
                    <a href="sales.php" class="text-success text-decoration-none small">
                        View all sales <i class="fas fa-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body d-flex align-items-center">
                    <div class="flex-shrink-0 bg-warning bg-opacity-10 p-3 rounded-3 me-3">
                        <i class="fas fa-exclamation-triangle fa-2x text-warning"></i>
                    </div>
                    <div>
                        <h6 class="text-muted mb-1">Low Stock Items</h6>
                        <h2 class="mb-0"><?php echo number_format($lowStockCount); ?></h2>
                    </div>
                </div>
                <div class="card-footer border-0 bg-transparent">
                    <a href="stock.php?filter=low" class="text-warning text-decoration-none small">
                        View low stock items <i class="fas fa-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Main Content Row -->
    <div class="row mb-4">
        <!-- Recent Sales -->
        <div class="col-lg-7 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="card-title m-0">Recent Sales</h5>
                    <?php if (checkPermission('view_sales') || checkPermission('view_own_sales')): ?>
                    <a href="sales.php" class="btn btn-sm btn-outline-primary">View All</a>
                    <?php endif; ?>
                </div>
                <div class="card-body">
                    <?php if (!empty($recentSales)): ?>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Invoice #</th>
                                    <th>Date</th>
                                    <th>Customer</th>
                                    <th>Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($recentSales as $sale): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($sale['invoice_number']); ?></td>
                                    <td><?php echo date('M d, Y', strtotime($sale['sale_date'])); ?></td>
                                    <td><?php echo htmlspecialchars($sale['customer_name'] ?? 'Walk-in Customer'); ?></td>
                                    <td>$<?php echo number_format($sale['total_amount'], 2); ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <?php else: ?>
                    <div class="text-center py-4">
                        <i class="fas fa-receipt fa-3x text-muted mb-3"></i>
                        <p>No recent sales found.</p>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <!-- Low Stock Items -->
        <div class="col-lg-5 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="card-title m-0">Low Stock Alert</h5>
                    <?php if (checkPermission('view_stock')): ?>
                    <a href="stock.php?filter=low" class="btn btn-sm btn-outline-primary">View All</a>
                    <?php endif; ?>
                </div>
                <div class="card-body">
                    <?php if (!empty($lowStockItems)): ?>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>SKU</th>
                                    <th>Stock</th>
                                    <th>Min Level</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($lowStockItems as $item): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($item['name']); ?></td>
                                    <td><?php echo htmlspecialchars($item['sku']); ?></td>
                                    <td class="text-danger fw-bold"><?php echo $item['current_quantity']; ?></td>
                                    <td><?php echo $item['minimum_stock_level']; ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <?php else: ?>
                    <div class="text-center py-4">
                        <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
                        <p>All stock levels are adequate.</p>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Quick Actions -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="card-title m-0">Quick Actions</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <a href="product.php" class="btn btn-outline-primary btn-lg w-100 py-3">
                                <i class="fas fa-box mb-2 d-block"></i>
                                View Products
                            </a>
                        </div>
                        <?php if (checkPermission('create_sales')): ?>
                        <div class="col-md-3">
                            <a href="sales_create.php" class="btn btn-outline-success btn-lg w-100 py-3">
                                <i class="fas fa-cart-plus mb-2 d-block"></i>
                                New Sale
                            </a>
                        </div>
                        <?php endif; ?>
                        <?php if (checkPermission('manage_stock')): ?>
                        <div class="col-md-3">
                            <a href="stock_adjust.php" class="btn btn-outline-warning btn-lg w-100 py-3">
                                <i class="fas fa-cubes mb-2 d-block"></i>
                                Adjust Stock
                            </a>
                        </div>
                        <?php endif; ?>
                        <?php if (checkPermission('view_reports')): ?>
                        <div class="col-md-3">
                            <a href="reports.php" class="btn btn-outline-info btn-lg w-100 py-3">
                                <i class="fas fa-chart-bar mb-2 d-block"></i>
                                Generate Reports
                            </a>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Footer -->
<footer class="py-3 bg-primary text-white mt-auto">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center">
            <span>Inventory Management System &copy; <?php echo date('Y'); ?></span>
            <span>Version 1.0</span>
        </div>
    </div>
</footer>

<!-- Bootstrap JS Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<!-- Feather Icons -->
<script src="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js"></script>
<script>
    // Initialize Feather icons
    document.addEventListener('DOMContentLoaded', function() {
        feather.replace();
    });
</script>