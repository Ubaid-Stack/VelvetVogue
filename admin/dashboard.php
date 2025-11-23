<?php 
$pageTitle = 'Dashboard';
$pageSubtitle = 'Overview of your store performance';
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
                        <h3 class="stat-value">$124,590</h3>
                        <div class="stat-trend positive">
                            <i class='bx bx-trending-up'></i>
                            <span>+12.5%</span>
                        </div>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon orders">
                        <i class='bx bx-receipt'></i>
                    </div>
                    <div class="stat-content">
                        <span class="stat-label">Orders</span>
                        <h3 class="stat-value">1,247</h3>
                        <div class="stat-trend positive">
                            <i class='bx bx-trending-up'></i>
                            <span>+8.2%</span>
                        </div>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon customers">
                        <i class='bx bx-group'></i>
                    </div>
                    <div class="stat-content">
                        <span class="stat-label">Customers</span>
                        <h3 class="stat-value">8,432</h3>
                        <div class="stat-trend positive">
                            <i class='bx bx-trending-up'></i>
                            <span>+15.7%</span>
                        </div>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon avg-order">
                        <i class='bx bx-package'></i>
                    </div>
                    <div class="stat-content">
                        <span class="stat-label">Avg Order</span>
                        <h3 class="stat-value">$99.83</h3>
                        <div class="stat-trend negative">
                            <i class='bx bx-trending-down'></i>
                            <span>-2.3%</span>
                        </div>
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
                        <div class="category-bar">
                            <div class="category-info">
                                <span class="category-name">Dresses</span>
                                <span class="category-value">$43,606</span>
                            </div>
                            <div class="progress-bar">
                                <div class="progress-fill" style="width: 85%;"></div>
                            </div>
                        </div>
                        <div class="category-bar">
                            <div class="category-info">
                                <span class="category-name">Outerwear</span>
                                <span class="category-value">$34,885</span>
                            </div>
                            <div class="progress-bar">
                                <div class="progress-fill" style="width: 68%;"></div>
                            </div>
                        </div>
                        <div class="category-bar">
                            <div class="category-info">
                                <span class="category-name">Accessories</span>
                                <span class="category-value">$27,410</span>
                            </div>
                            <div class="progress-bar">
                                <div class="progress-fill" style="width: 53%;"></div>
                            </div>
                        </div>
                        <div class="category-bar">
                            <div class="category-info">
                                <span class="category-name">Jewelry</span>
                                <span class="category-value">$18,689</span>
                            </div>
                            <div class="progress-bar">
                                <div class="progress-fill" style="width: 36%;"></div>
                            </div>
                        </div>
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
                            <tr>
                                <td>Silk Evening Dress</td>
                                <td>145</td>
                                <td>$43,355</td>
                                <td>
                                    <div class="performance-bar">
                                        <div class="performance-fill" style="width: 92%;"></div>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>Cashmere Coat</td>
                                <td>98</td>
                                <td>$44,100</td>
                                <td>
                                    <div class="performance-bar">
                                        <div class="performance-fill" style="width: 78%;"></div>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>Leather Handbag</td>
                                <td>87</td>
                                <td>$24,360</td>
                                <td>
                                    <div class="performance-bar">
                                        <div class="performance-fill" style="width: 65%;"></div>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>Designer Sunglasses</td>
                                <td>76</td>
                                <td>$18,240</td>
                                <td>
                                    <div class="performance-bar">
                                        <div class="performance-fill" style="width: 58%;"></div>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>Gold Necklace</td>
                                <td>54</td>
                                <td>$21,600</td>
                                <td>
                                    <div class="performance-bar">
                                        <div class="performance-fill" style="width: 45%;"></div>
                                    </div>
                                </td>
                            </tr>
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

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['J', 'F', 'M', 'A', 'M', 'J', 'J', 'A', 'S', 'O', 'N', 'D'],
                datasets: [{
                    label: 'Revenue',
                    data: [45000, 52000, 48000, 61000, 58000, 67000, 73000, 69000, 78000, 82000, 88000, 94000],
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