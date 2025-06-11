<?php
include 'models/authentication.php';
// Include required files
require_once "config/database.php";
require_once "models/product.php";
require_once "helpers/permissions.php";
include "views/header.php";

// Initialize database connection
$database = new Database();
$db = $database->getConnection();

// Initialize Product object
$product = new Product($db);

// Start session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Initialize cart if it doesn't exist
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Set page parameters for pagination
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$records_per_page = 12; // Increased for grid view
$from_record_num = ($records_per_page * $page) - $records_per_page;

// Handle search and filter
$search = isset($_GET['search']) ? $_GET['search'] : '';
$category = isset($_GET['category']) ? $_GET['category'] : '';

// Read products with pagination and filters
$stmt = $product->readAll($from_record_num, $records_per_page, $search, $category);
$num = $stmt->rowCount();

// Get total count for pagination
$total_rows = $product->countAll($search, $category);
$total_pages = ceil($total_rows / $records_per_page);

// Check if the current user is an admin
$isAdmin = checkPermission('manage_products');

// Count items in cart
$cartItemCount = 0;
foreach ($_SESSION['cart'] as $item) {
    $cartItemCount += $item['quantity'];
}

// Get all product categories
$categories = $product->getCategories();

// Define base URL for assets
$asset_base_url = 'assets/';
?>

<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 text-primary">Products</h1>
        <div>
            <?php if($isAdmin): ?>
            <a href="product_create.php" class="btn btn-primary">
                <i class="fas fa-plus"></i> Add New Product
            </a>
            <?php endif; ?>
        </div>
    </div>
    
    <!-- Search and filter options -->
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <form action="product.php" method="GET" class="row g-3">
                <div class="col-md-6">
                    <div class="input-group">
                        <input type="text" name="search" class="form-control" placeholder="Search products..." 
                            value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>
                <div class="col-md-4">
                    <select name="category" class="form-select" onchange="this.form.submit()">
                        <option value="">All Categories</option>
                        <?php
                        foreach($categories as $cat) {
                            $selected = (isset($_GET['category']) && $_GET['category'] == $cat['category']) ? 'selected' : '';
                            echo "<option value='{$cat['category']}' {$selected}>{$cat['category']}</option>";
                        }
                        ?>
                    </select>
                </div>
                <?php if(isset($_GET['search']) || isset($_GET['category']) && $_GET['category'] != ''): ?>
                <div class="col-md-2">
                    <a href="product.php" class="btn btn-outline-secondary w-100">Clear Filters</a>
                </div>
                <?php endif; ?>
            </form>
        </div>
    </div>
    
    <!-- Products Grid View -->
    <div class="row row-cols-1 row-cols-md-3 row-cols-lg-4 g-4">
        <?php
        if($num > 0) {
            while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                extract($row);
                
                // Check if stock is low
                $stock_badge = '';
                if($stock_level <= $minimum_stock_level && $stock_level > 0) {
                    $stock_badge = '<span class="position-absolute top-0 end-0 badge bg-warning m-2">Low Stock</span>';
                } elseif($stock_level == 0) {
                    $stock_badge = '<span class="position-absolute top-0 end-0 badge bg-danger m-2">Out of Stock</span>';
                }
                
                // Create a proper image path with fallback to default product image
                if(isset($image) && !empty($image) && file_exists('uploads/products/' . $image)) {
                    $image_url = 'uploads/products/' . $image;
                } else {
                    // Use a local default product image instead of placeholder service
                    $image_url = $asset_base_url . 'img/product-default.jpg';
                }
                
                echo "<div class='col'>
                    <div class='card h-100 shadow-sm position-relative'>
                        {$stock_badge}
                        <img src='{$image_url}' class='card-img-top' alt='{$name}' style='height: 200px; object-fit: cover;'>
                        <div class='card-body'>
                            <h5 class='card-title'>{$name}</h5>
                            <p class='card-text text-muted small mb-1'>{$category}</p>
                            <p class='card-text fw-bold mb-1'>\${$price}</p>
                            <p class='card-text small'>SKU: {$sku}</p>
                            <div class='d-flex justify-content-between align-items-center'>
                                <div class='btn-group'>
                                    <a href='product_view.php?id={$product_id}' class='btn btn-sm btn-outline-primary'>
                                        <i class='fas fa-eye'></i> Details
                                    </a>";
                                    
                            if($isAdmin) {
                                echo "<a href='product_edit.php?id={$product_id}' class='btn btn-sm btn-outline-secondary'>
                                        <i class='fas fa-edit'></i> Edit
                                    </a>";
                            }
                            
                            echo "</div>";
                                
                            if($stock_level > 0) {
                                echo "<a href='add_to_cart.php?id={$product_id}' class='btn btn-sm btn-success'>
                                        <i class='fas fa-shopping-cart'></i> Add
                                    </a>";
                            } else {
                                echo "<button class='btn btn-sm btn-secondary' disabled>
                                        <i class='fas fa-ban'></i> Out of Stock
                                    </button>";
                            }
                                
                            echo "</div>
                        </div>
                        <div class='card-footer bg-transparent'>
                            <small class='text-muted'>Stock: <span class='" . ($stock_level <= $minimum_stock_level ? 'text-danger' : '') . "'>{$stock_level}</span> units</small>
                        </div>
                    </div>
                </div>";
            }
        } else {
            echo "<div class='col-12'>
                <div class='alert alert-info'>
                    <i class='fas fa-info-circle me-2'></i> No products found. Try adjusting your search or filters.
                </div>
            </div>";
        }
        ?>
    </div>
    
    <!-- Pagination -->
    <?php if($total_pages > 1): ?>
    <nav aria-label="Page navigation" class="mt-4">
        <ul class="pagination justify-content-center">
            <?php 
            // Previous page link
            if($page > 1) {
                echo "<li class='page-item'>
                        <a class='page-link' href='product.php?page=".($page-1)."&search={$search}&category={$category}'>
                            <i class='fas fa-chevron-left'></i>
                        </a>
                      </li>";
            } else {
                echo "<li class='page-item disabled'>
                        <a class='page-link' href='#'>
                            <i class='fas fa-chevron-left'></i>
                        </a>
                      </li>";
            }
            
            // Page numbers
            $start_page = max(1, $page - 2);
            $end_page = min($total_pages, $page + 2);
            
            if ($start_page > 1) {
                echo "<li class='page-item'><a class='page-link' href='product.php?page=1&search={$search}&category={$category}'>1</a></li>";
                if ($start_page > 2) {
                    echo "<li class='page-item disabled'><a class='page-link' href='#'>...</a></li>";
                }
            }
            
            for($i = $start_page; $i <= $end_page; $i++) {
                if($i == $page) {
                    echo "<li class='page-item active'><a class='page-link' href='#'>{$i}</a></li>";
                } else {
                    echo "<li class='page-item'><a class='page-link' href='product.php?page={$i}&search={$search}&category={$category}'>{$i}</a></li>";
                }
            }
            
            if ($end_page < $total_pages) {
                if ($end_page < $total_pages - 1) {
                    echo "<li class='page-item disabled'><a class='page-link' href='#'>...</a></li>";
                }
                echo "<li class='page-item'><a class='page-link' href='product.php?page={$total_pages}&search={$search}&category={$category}'>{$total_pages}</a></li>";
            }
            
            // Next page link
            if($page < $total_pages) {
                echo "<li class='page-item'>
                        <a class='page-link' href='product.php?page=".($page+1)."&search={$search}&category={$category}'>
                            <i class='fas fa-chevron-right'></i>
                        </a>
                      </li>";
            } else {
                echo "<li class='page-item disabled'>
                        <a class='page-link' href='#'>
                            <i class='fas fa-chevron-right'></i>
                        </a>
                      </li>";
            }
            ?>
        </ul>
    </nav>
    <?php endif; ?>
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
<!-- Font Awesome for icons -->
<script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
<script>
    // Initialize any scripts
    document.addEventListener('DOMContentLoaded', function() {
        // You can add any initialization code here
    });
</script>