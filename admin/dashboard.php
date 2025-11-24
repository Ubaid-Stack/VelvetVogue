<?php   
session_start();
require_once '../inc/db.php';

// Check if admin is logged in
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'admin') {
    header('Location: adminLogin.php');
    exit();
}

$pageTitle = 'Dashboard';
$pageSubtitle = 'Overview of your store performance';

// Fetch total revenue from delivered/processing/shipped orders
$revenue_sql = "SELECT SUM(total_amount) as total_revenue FROM orders 
                WHERE order_status IN ('delivered', 'processing', 'shipped')";
$revenue_result = $conn->query($revenue_sql);
$total_revenue = $revenue_result->fetch_assoc()['total_revenue'] ?? 0;

// Fetch total orders
$orders_sql = "SELECT COUNT(*) as total_orders FROM orders";
$orders_result = $conn->query($orders_sql);
$total_orders = $orders_result->fetch_assoc()['total_orders'] ?? 0;

// Fetch total customers
$customers_sql = "SELECT COUNT(*) as total_customers FROM users WHERE user_type = 'customer'";
$customers_result = $conn->query($customers_sql);
$total_customers = $customers_result->fetch_assoc()['total_customers'] ?? 0;

// Calculate average order value
$avg_order = $total_orders > 0 ? $total_revenue / $total_orders : 0;

// Fetch sales by category
$category_sql = "SELECT c.category_name, 
                 SUM(oi.subtotal) as total_sales,
                 COUNT(DISTINCT oi.order_id) as order_count
                 FROM order_items oi
                 JOIN products p ON oi.product_id = p.product_id
                 JOIN categories c ON p.category_id = c.category_id
                 JOIN orders o ON oi.order_id = o.order_id
                 WHERE o.order_status IN ('delivered', 'processing', 'shipped')
                 GROUP BY c.category_id, c.category_name
                 ORDER BY total_sales DESC
                 LIMIT 4";
$category_result = $conn->query($category_sql);
$categories = [];
$max_category_sales = 0;
if ($category_result) {
    while ($row = $category_result->fetch_assoc()) {
        $categories[] = $row;
        if ($row['total_sales'] > $max_category_sales) {
            $max_category_sales = $row['total_sales'];
        }
    }
}

// Fetch top products
$top_products_sql = "SELECT p.product_name,
                     COUNT(oi.order_item_id) as sales_count,
                     SUM(oi.subtotal) as revenue,
                     SUM(oi.quantity) as total_quantity
                     FROM order_items oi
                     JOIN products p ON oi.product_id = p.product_id
                     JOIN orders o ON oi.order_id = o.order_id
                     WHERE o.order_status IN ('delivered', 'processing', 'shipped')
                     GROUP BY p.product_id, p.product_name
                     ORDER BY revenue DESC
                     LIMIT 5";
$top_products_result = $conn->query($top_products_sql);
$top_products = [];
$max_product_revenue = 0;
if ($top_products_result) {
    while ($row = $top_products_result->fetch_assoc()) {
        $top_products[] = $row;
        if ($row['revenue'] > $max_product_revenue) {
            $max_product_revenue = $row['revenue'];
        }
    }
}

// Fetch monthly revenue for chart (last 12 months)
$monthly_revenue_sql = "SELECT 
                        DATE_FORMAT(order_date, '%Y-%m') as month,
                        DATE_FORMAT(order_date, '%b') as month_name,
                        SUM(total_amount) as revenue
                        FROM orders
                        WHERE order_status IN ('delivered', 'processing', 'shipped')
                        AND order_date >= DATE_SUB(CURDATE(), INTERVAL 12 MONTH)
                        GROUP BY DATE_FORMAT(order_date, '%Y-%m'), DATE_FORMAT(order_date, '%b')
                        ORDER BY month ASC";
$monthly_revenue_result = $conn->query($monthly_revenue_sql);
$monthly_data = [];
if ($monthly_revenue_result) {
    while ($row = $monthly_revenue_result->fetch_assoc()) {
        $monthly_data[] = $row;
    }
}
?>
<?php include './inc/head.php'; ?>

<?php include './inc/sidbar.php'; ?>
    
<?php include './inc/topbar.php'; ?>
        <!-- Analytics Section -->
        <section class="analytics-section">
            <div class="section-header">
                <div class="section-title-group">
                    <h2>Analytics</h2>
                    <p>Detailed insights and performance metrics</p>
                </div>
                
                <div class="time-filter">
                    <button class="filter-btn active">Today</button>
                    <button class="filter-btn">Week</button>
                    <button class="filter-btn">Month</button>
                    <button class="filter-btn">Year</button>
                </div>
            </div>

            <!-- Stats Cards -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon revenue">
                        <i class='bx bx-dollar-circle'></i>
                    </div>
                    <div class="stat-content">
                        <span class="stat-label">Revenue</span>
                        <h3 class="stat-value">$<?php echo number_format($total_revenue, 2); ?></h3>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon orders">
                        <i class='bx bx-receipt'></i>
                    </div>
                    <div class="stat-content">
                        <span class="stat-label">Orders</span>
                        <h3 class="stat-value"><?php echo number_format($total_orders); ?></h3>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon customers">
                        <i class='bx bx-group'></i>
                    </div>
                    <div class="stat-content">
                        <span class="stat-label">Customers</span>
                        <h3 class="stat-value"><?php echo number_format($total_customers); ?></h3>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon avg-order">
                        <i class='bx bx-package'></i>
                    </div>
                    <div class="stat-content">
                        <span class="stat-label">Avg Order</span>
                        <h3 class="stat-value">$<?php echo number_format($avg_order, 2); ?></h3>
                    </div>
                </div>
            </div>

            <!-- Charts Section -->
            <div class="charts-grid">
                <div class="chart-card">
                    <div class="chart-header">
                        <h3>Revenue Trend</h3>
                    </div>
                    <div class="chart-container">
                        <canvas id="revenueChart"></canvas>
                    </div>
                </div>

                <div class="chart-card">
                    <div class="chart-header">
                        <h3>Sales by Category</h3>
                    </div>
                    <div class="chart-container">
                        <?php if (empty($categories)): ?>
                            <div style="text-align: center; padding: 20px; color: #666;">
                                <p>No category data available</p>
                            </div>
                        <?php else: ?>
                            <?php foreach ($categories as $category): 
                                $percentage = $max_category_sales > 0 ? ($category['total_sales'] / $max_category_sales) * 100 : 0;
                            ?>
                            <div class="category-bar">
                                <div class="category-info">
                                    <span class="category-name"><?php echo htmlspecialchars($category['category_name']); ?></span>
                                    <span class="category-value">$<?php echo number_format($category['total_sales'], 2); ?></span>
                                </div>
                                <div class="progress-bar">
                                    <div class="progress-fill" style="width: <?php echo round($percentage); ?>%;"></div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Top Products Table -->
            <div class="table-card">
                <div class="table-header">
                    <h3>Top Products</h3>
                </div>
                <div class="table-container">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Sales</th>
                                <th>Revenue</th>
                                <th>Performance</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($top_products)): ?>
                                <tr>
                                    <td colspan="4" style="text-align: center; padding: 20px; color: #666;">
                                        No product data available
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($top_products as $product): 
                                    $performance = $max_product_revenue > 0 ? ($product['revenue'] / $max_product_revenue) * 100 : 0;
                                ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($product['product_name']); ?></td>
                                    <td><?php echo number_format($product['sales_count']); ?></td>
                                    <td>$<?php echo number_format($product['revenue'], 2); ?></td>
                                    <td>
                                        <div class="performance-bar">
                                            <div class="performance-fill" style="width: <?php echo round($performance); ?>%;"></div>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

        </section>

    </main>

    <script>
        // Mobile Menu Toggle
        const mobileMenuBtn = document.getElementById('mobileMenuBtn');
        const adminSidebar = document.getElementById('adminSidebar');
        const sidebarOverlay = document.getElementById('sidebarOverlay');

        mobileMenuBtn.addEventListener('click', function() {
            adminSidebar.classList.toggle('active');
            sidebarOverlay.classList.toggle('active');
        });

        sidebarOverlay.addEventListener('click', function() {
            adminSidebar.classList.remove('active');
            sidebarOverlay.classList.remove('active');
        });

        // Revenue Chart
        const ctx = document.getElementById('revenueChart').getContext('2d');
        const gradient = ctx.createLinearGradient(0, 0, 0, 300);
        gradient.addColorStop(0, 'rgba(60, 145, 230, 0.3)');
        gradient.addColorStop(1, 'rgba(60, 145, 230, 0)');

        <?php
        // Prepare chart data
        $chart_labels = [];
        $chart_data = [];
        
        if (!empty($monthly_data)) {
            foreach ($monthly_data as $month) {
                $chart_labels[] = $month['month_name'];
                $chart_data[] = floatval($month['revenue']);
            }
        } else {
            // Default empty data
            $chart_labels = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
            $chart_data = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
        }
        ?>

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: <?php echo json_encode($chart_labels); ?>,
                datasets: [{
                    label: 'Revenue',
                    data: <?php echo json_encode($chart_data); ?>,
                    borderColor: '#3C91E6',
                    backgroundColor: gradient,
                    borderWidth: 3,
                    tension: 0.4,
                    fill: true,
                    pointRadius: 4,
                    pointHoverRadius: 6,
                    pointBackgroundColor: '#3C91E6',
                    pointBorderColor: '#FFFFFF',
                    pointBorderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: '#1F2937',
                        padding: 12,
                        titleColor: '#FFFFFF',
                        bodyColor: '#FFFFFF',
                        borderColor: '#374151',
                        borderWidth: 1,
                        displayColors: false,
                        callbacks: {
                            label: function(context) {
                                return '$' + context.parsed.y.toLocaleString();
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            color: '#6B7280',
                            font: {
                                size: 11
                            }
                        }
                    },
                    y: {
                        grid: {
                            color: '#F3F4F6',
                            drawBorder: false
                        },
                        ticks: {
                            color: '#6B7280',
                            font: {
                                size: 11
                            },
                            callback: function(value) {
                                return '$' + (value / 1000) + 'k';
                            }
                        }
                    }
                }
            }
        });

        // SweetAlert2 Toast Configuration
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
            }
        });

        // Filter Buttons
        const filterBtns = document.querySelectorAll('.filter-btn');
        filterBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                const filterType = this.textContent;
                
                filterBtns.forEach(b => b.classList.remove('active'));
                this.classList.add('active');
                
                Toast.fire({
                    icon: 'info',
                    title: `Showing ${filterType} data`
                });
            });
        });
    </script>