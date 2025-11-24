<?php 
session_start();
require_once '../inc/db.php';

// Check if admin is logged in - This is now redundant due to head.php check
// Additional check here for direct access before head.php is included
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'admin') {
    header('Location: adminLogin.php');
    exit();
}

$pageTitle = 'Manage Orders';
$pageSubtitle = 'Track and manage customer orders';

// Handle order status update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $response = ['success' => false, 'message' => ''];
    
    if ($_POST['action'] === 'update_status') {
        $order_id = intval($_POST['order_id']);
        $new_status = $_POST['new_status'];
        
        // Validate status
        $valid_statuses = ['pending', 'processing', 'shipped', 'delivered', 'cancelled', 'refunded'];
        if (in_array($new_status, $valid_statuses)) {
            $update_fields = ['order_status = ?'];
            $params = [$new_status];
            $types = 's';
            
            // Update shipped_date if status is shipped
            if ($new_status === 'shipped') {
                $update_fields[] = 'shipped_date = NOW()';
            }
            
            // Update delivered_date if status is delivered
            if ($new_status === 'delivered') {
                $update_fields[] = 'delivered_date = NOW()';
            }
            
            $sql = "UPDATE orders SET " . implode(', ', $update_fields) . " WHERE order_id = ?";
            $params[] = $order_id;
            $types .= 'i';
            
            $stmt = $conn->prepare($sql);
            $stmt->bind_param($types, ...$params);
            
            if ($stmt->execute()) {
                $response['success'] = true;
                $response['message'] = 'Order status updated successfully!';
            } else {
                $response['message'] = 'Failed to update order status.';
            }
            $stmt->close();
        } else {
            $response['message'] = 'Invalid order status.';
        }
    }
    
    header('Content-Type: application/json');
    echo json_encode($response);
    exit();
}

// Fetch orders with user and address info
$sql = "SELECT o.*, 
        u.username, u.email as user_email, u.phone as user_phone,
        a.full_name, a.phone as shipping_phone, a.address_line1, a.address_line2, 
        a.city, a.state, a.zip_code, a.country,
        (SELECT COUNT(*) FROM order_items oi WHERE oi.order_id = o.order_id) as item_count
        FROM orders o
        JOIN users u ON o.user_id = u.user_id
        JOIN addresses a ON o.address_id = a.address_id
        ORDER BY o.order_date DESC";

$orders_result = $conn->query($sql);
$orders = [];
if ($orders_result) {
    while ($row = $orders_result->fetch_assoc()) {
        $orders[] = $row;
    }
}

// Calculate statistics
$total_orders = count($orders);
$pending_count = 0;
$processing_count = 0;
$shipped_count = 0;
$delivered_count = 0;
$cancelled_count = 0;
$total_revenue = 0;

foreach ($orders as $order) {
    switch ($order['order_status']) {
        case 'pending':
            $pending_count++;
            break;
        case 'processing':
            $processing_count++;
            break;
        case 'shipped':
            $shipped_count++;
            break;
        case 'delivered':
            $delivered_count++;
            break;
        case 'cancelled':
            $cancelled_count++;
            break;
    }
    
    // Add to revenue only for completed orders
    if (in_array($order['order_status'], ['delivered', 'processing', 'shipped'])) {
        $total_revenue += $order['total_amount'];
    }
}
?>
<?php include './inc/head.php'; ?>

<?php include './inc/sidbar.php'; ?>
    
<?php include './inc/topbar.php'; ?>

        <!-- Orders Section -->
        <section class="orders-section">
            
            <!-- Stats Cards -->
            <div class="order-stats-grid">
                <div class="order-stat-card">
                    <span class="stat-label">Total Orders</span>
                    <h3 class="stat-number"><?php echo $total_orders; ?></h3>
                </div>

                <div class="order-stat-card pending">
                    <span class="stat-label">Pending</span>
                    <h3 class="stat-number"><?php echo $pending_count; ?></h3>
                </div>

                <div class="order-stat-card processing">
                    <span class="stat-label">Processing</span>
                    <h3 class="stat-number"><?php echo $processing_count; ?></h3>
                </div>

                <div class="order-stat-card shipped">
                    <span class="stat-label">Shipped</span>
                    <h3 class="stat-number"><?php echo $shipped_count; ?></h3>
                </div>

                <div class="order-stat-card delivered">
                    <span class="stat-label">Delivered</span>
                    <h3 class="stat-number"><?php echo $delivered_count; ?></h3>
                </div>

                <div class="order-stat-card revenue">
                    <span class="stat-label">Revenue</span>
                    <h3 class="stat-number">$<?php echo number_format($total_revenue, 2); ?></h3>
                </div>
            </div>

            <!-- Search and Filter -->
            <div class="order-search-bar">
                <div class="search-box">
                    <i class='bx bx-search'></i>
                    <input type="text" placeholder="Search by order number or customer..." id="orderSearchInput">
                </div>
                <button class="btn-filters" id="orderFiltersBtn">
                    <i class='bx bx-filter-alt'></i>
                    <span>Filters</span>
                </button>
            </div>

            <!-- Order Status Tabs -->
            <div class="order-tabs">
                <button class="order-tab active" data-status="all">All Orders (<?php echo $total_orders; ?>)</button>
                <button class="order-tab" data-status="pending">Pending (<?php echo $pending_count; ?>)</button>
                <button class="order-tab" data-status="processing">Processing (<?php echo $processing_count; ?>)</button>
                <button class="order-tab" data-status="shipped">Shipped (<?php echo $shipped_count; ?>)</button>
                <button class="order-tab" data-status="delivered">Delivered (<?php echo $delivered_count; ?>)</button>
                <button class="order-tab" data-status="cancelled">Cancelled (<?php echo $cancelled_count; ?>)</button>
            </div>

            <!-- Orders List -->
            <div class="orders-list">
                
                <?php if (empty($orders)): ?>
                    <div style="text-align: center; padding: 40px; color: #666;">
                        <i class='bx bx-shopping-bag' style="font-size: 48px;"></i>
                        <p style="margin-top: 16px; font-size: 18px;">No orders found</p>
                    </div>
                <?php else: ?>
                    <?php foreach ($orders as $order): 
                        $order_date = date('M d, Y', strtotime($order['order_date']));
                        $status = htmlspecialchars($order['order_status']);
                        $status_text = ucfirst($status);
                    ?>
                    <!-- Order Card -->
                    <div class="order-card" data-status="<?php echo $status; ?>">
                        <div class="order-header">
                            <div class="order-info">
                                <h3 class="order-number"><?php echo htmlspecialchars($order['order_number']); ?></h3>
                                <p class="order-customer"><?php echo htmlspecialchars($order['full_name']); ?></p>
                            </div>
                            <span class="order-status-badge <?php echo $status; ?>"><?php echo $status_text; ?></span>
                        </div>
                        <div class="order-details">
                            <div class="order-meta">
                                <div class="meta-item">
                                    <span class="meta-label">Date</span>
                                    <span class="meta-value"><?php echo $order_date; ?></span>
                                </div>
                                <div class="meta-item">
                                    <span class="meta-label">Items</span>
                                    <span class="meta-value"><?php echo $order['item_count']; ?> items</span>
                                </div>
                                <div class="meta-item">
                                    <span class="meta-label">Total</span>
                                    <span class="meta-value">$<?php echo number_format($order['total_amount'], 2); ?></span>
                                </div>
                            </div>
                        </div>
                        <div class="order-actions">
                            <button class="btn-action view" onclick="viewOrderDetails(<?php echo $order['order_id']; ?>)">
                                <i class='bx bx-show'></i>
                                <span>View Details</span>
                            </button>
                            
                            <?php if ($status === 'pending'): ?>
                                <button class="btn-action process" onclick="updateOrderStatus(<?php echo $order['order_id']; ?>, 'processing')">
                                    <i class='bx bx-check-circle'></i>
                                    <span>Mark as Processing</span>
                                </button>
                                <button class="btn-action cancel" onclick="updateOrderStatus(<?php echo $order['order_id']; ?>, 'cancelled')">
                                    <span>Cancel</span>
                                </button>
                            <?php elseif ($status === 'processing'): ?>
                                <button class="btn-action ship" onclick="updateOrderStatus(<?php echo $order['order_id']; ?>, 'shipped')">
                                    <i class='bx bx-check-circle'></i>
                                    <span>Mark as Shipped</span>
                                </button>
                                <button class="btn-action cancel" onclick="updateOrderStatus(<?php echo $order['order_id']; ?>, 'cancelled')">
                                    <span>Cancel</span>
                                </button>
                            <?php elseif ($status === 'shipped'): ?>
                                <button class="btn-action deliver" onclick="updateOrderStatus(<?php echo $order['order_id']; ?>, 'delivered')">
                                    <i class='bx bx-check-circle'></i>
                                    <span>Mark as Delivered</span>
                                </button>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php endif; ?>

            </div>



        </section>

    </main>

    <!-- Order Details Modal -->
    <div class="modal-overlay" id="orderDetailsModal">
        <div class="modal-container">
            <div class="modal-header">
                <h2>Order Details</h2>
                <button class="modal-close" onclick="closeOrderModal()">
                    <i class='bx bx-x'></i>
                </button>
            </div>
            <div class="modal-body">
                <!-- Order Info -->
                <div class="order-detail-section">
                    <h3 class="detail-section-title">Order Information</h3>
                    <div class="detail-grid">
                        <div class="detail-item">
                            <span class="detail-label">Order Number</span>
                            <span class="detail-value" id="modalOrderNumber">#VV-10234</span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Order Date</span>
                            <span class="detail-value" id="modalOrderDate">Jan 15, 2024</span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Status</span>
                            <span class="order-status-badge pending" id="modalOrderStatus">Pending</span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Total Amount</span>
                            <span class="detail-value" id="modalOrderTotal">$789.00</span>
                        </div>
                    </div>
                </div>

                <!-- Customer Info -->
                <div class="order-detail-section">
                    <h3 class="detail-section-title">Customer Information</h3>
                    <div class="detail-grid">
                        <div class="detail-item">
                            <span class="detail-label">Name</span>
                            <span class="detail-value" id="modalCustomerName">Emma Thompson</span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Email</span>
                            <span class="detail-value" id="modalCustomerEmail">emma.thompson@email.com</span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Phone</span>
                            <span class="detail-value" id="modalCustomerPhone">+1 (555) 123-4567</span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Shipping Address</span>
                            <span class="detail-value" id="modalShippingAddress">123 Main St, New York, NY 10001</span>
                        </div>
                    </div>
                </div>

                <!-- Order Items -->
                <div class="order-detail-section">
                    <h3 class="detail-section-title">Order Items</h3>
                    <div class="order-items-list" id="modalOrderItems">
                        <div class="order-item">
                            <div class="item-image">
                                <img src="../images/hero-img.png" alt="Product">
                            </div>
                            <div class="item-details">
                                <h4 class="item-name">Velvet Evening Dress</h4>
                                <p class="item-meta">Size: M | Color: Burgundy</p>
                                <p class="item-price">$350.00 × 1</p>
                            </div>
                            <div class="item-total">
                                <span class="item-total-price">$350.00</span>
                            </div>
                        </div>
                        <div class="order-item">
                            <div class="item-image">
                                <img src="../images/hero-img.png" alt="Product">
                            </div>
                            <div class="item-details">
                                <h4 class="item-name">Silk Blouse</h4>
                                <p class="item-meta">Size: S | Color: White</p>
                                <p class="item-price">$189.00 × 2</p>
                            </div>
                            <div class="item-total">
                                <span class="item-total-price">$378.00</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Order Summary -->
                <div class="order-detail-section">
                    <h3 class="detail-section-title">Order Summary</h3>
                    <div class="order-summary">
                        <div class="summary-row">
                            <span>Subtotal</span>
                            <span id="modalSubtotal">$728.00</span>
                        </div>
                        <div class="summary-row">
                            <span>Shipping</span>
                            <span id="modalShipping">$45.00</span>
                        </div>
                        <div class="summary-row">
                            <span>Tax</span>
                            <span id="modalTax">$16.00</span>
                        </div>
                        <div class="summary-row total">
                            <span>Total</span>
                            <span id="modalSummaryTotal">$789.00</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn-secondary" onclick="closeOrderModal()">Close</button>
                <button class="btn-primary" onclick="printOrder()">
                    <i class='bx bx-printer'></i>
                    Print Order
                </button>
            </div>
        </div>
    </div>

    <script>
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

        // Order Status Tabs
        const orderTabs = document.querySelectorAll('.order-tab');
        const orderCards = document.querySelectorAll('.order-card');

        orderTabs.forEach(tab => {
            tab.addEventListener('click', function() {
                const status = this.getAttribute('data-status');
                
                // Update active tab
                orderTabs.forEach(t => t.classList.remove('active'));
                this.classList.add('active');
                
                // Filter orders
                orderCards.forEach(card => {
                    if (status === 'all') {
                        card.style.display = 'block';
                    } else {
                        if (card.getAttribute('data-status') === status) {
                            card.style.display = 'block';
                        } else {
                            card.style.display = 'none';
                        }
                    }
                });

                Toast.fire({
                    icon: 'info',
                    title: `Showing ${this.textContent} orders`
                });
            });
        });

        // Search Functionality
        const orderSearchInput = document.getElementById('orderSearchInput');
        let searchTimeout;

        orderSearchInput.addEventListener('input', function(e) {
            clearTimeout(searchTimeout);
            
            searchTimeout = setTimeout(() => {
                const searchTerm = e.target.value.toLowerCase();
                let foundCount = 0;
                
                orderCards.forEach(card => {
                    const orderNumber = card.querySelector('.order-number').textContent.toLowerCase();
                    const customerName = card.querySelector('.order-customer').textContent.toLowerCase();
                    
                    if (orderNumber.includes(searchTerm) || customerName.includes(searchTerm)) {
                        card.style.display = 'block';
                        foundCount++;
                    } else {
                        card.style.display = 'none';
                    }
                });
                
                if (searchTerm && foundCount === 0) {
                    Toast.fire({
                        icon: 'info',
                        title: 'No orders found'
                    });
                }
            }, 500);
        });

        // Filters Button
        document.getElementById('orderFiltersBtn').addEventListener('click', function() {
            Toast.fire({
                icon: 'info',
                title: 'Advanced filters coming soon'
            });
        });

        // View Order Details
        function viewOrderDetails(orderId) {
            // Fetch order details from API
            fetch(`get_order.php?id=${orderId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const order = data.order;
                        
                        // Populate modal
                        document.getElementById('modalOrderNumber').textContent = order.order_number;
                        document.getElementById('modalOrderDate').textContent = order.order_date;
                        document.getElementById('modalOrderStatus').textContent = order.order_status.charAt(0).toUpperCase() + order.order_status.slice(1);
                        document.getElementById('modalOrderStatus').className = `order-status-badge ${order.order_status}`;
                        document.getElementById('modalOrderTotal').textContent = '$' + order.total_amount;
                        document.getElementById('modalCustomerName').textContent = order.customer_name;
                        document.getElementById('modalCustomerEmail').textContent = order.customer_email;
                        document.getElementById('modalCustomerPhone').textContent = order.customer_phone;
                        document.getElementById('modalShippingAddress').textContent = order.shipping_address_full;
                        document.getElementById('modalSubtotal').textContent = '$' + order.subtotal;
                        document.getElementById('modalShipping').textContent = '$' + order.shipping_cost;
                        document.getElementById('modalTax').textContent = '$' + order.tax_amount;
                        document.getElementById('modalSummaryTotal').textContent = '$' + order.total_amount;

                        // Populate items
                        const itemsList = document.getElementById('modalOrderItems');
                        itemsList.innerHTML = order.items.map(item => {
                            const imagePath = item.image_url ? `../${item.image_url}` : '../images/hero-img.png';
                            const itemName = item.product_name || 'Product';
                            const size = item.size || 'N/A';
                            const color = item.color || 'N/A';
                            const unitPrice = parseFloat(item.unit_price).toFixed(2);
                            const subtotal = parseFloat(item.subtotal).toFixed(2);
                            
                            return `
                                <div class="order-item">
                                    <div class="item-image">
                                        <img src="${imagePath}" alt="${itemName}">
                                    </div>
                                    <div class="item-details">
                                        <h4 class="item-name">${itemName}</h4>
                                        <p class="item-meta">Size: ${size} | Color: ${color}</p>
                                        <p class="item-price">$${unitPrice} × ${item.quantity}</p>
                                    </div>
                                    <div class="item-total">
                                        <span class="item-total-price">$${subtotal}</span>
                                    </div>
                                </div>
                            `;
                        }).join('');

                        // Show modal
                        document.getElementById('orderDetailsModal').classList.add('active');
                    } else {
                        Toast.fire({
                            icon: 'error',
                            title: data.message || 'Failed to load order details'
                        });
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    Toast.fire({
                        icon: 'error',
                        title: 'Failed to load order details'
                    });
                });
        }

        // Close Order Modal
        function closeOrderModal() {
            document.getElementById('orderDetailsModal').classList.remove('active');
        }

        // Print Order
        function printOrder() {
            Toast.fire({
                icon: 'info',
                title: 'Preparing order for printing...'
            });
            // Here you would implement print functionality
        }

        // Update Order Status
        function updateOrderStatus(orderId, newStatus) {
            const statusText = newStatus.charAt(0).toUpperCase() + newStatus.slice(1);
            
            Swal.fire({
                title: 'Update Order Status?',
                text: `Mark this order as ${statusText}?`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3C91E6',
                cancelButtonColor: '#6B7280',
                confirmButtonText: 'Yes, update it!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Show loading
                    Swal.fire({
                        title: 'Updating...',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    // Send update to backend
                    const formData = new FormData();
                    formData.append('action', 'update_status');
                    formData.append('order_id', orderId);
                    formData.append('new_status', newStatus);

                    fetch('manageOrder.php', {
                        method: 'POST',
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        Swal.close();
                        
                        if (data.success) {
                            Toast.fire({
                                icon: 'success',
                                title: data.message
                            });
                            
                            // Reload page to refresh order list
                            setTimeout(() => {
                                location.reload();
                            }, 1500);
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Update Failed',
                                text: data.message
                            });
                        }
                    })
                    .catch(error => {
                        Swal.close();
                        console.error('Error:', error);
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Failed to update order status'
                        });
                    });
                }
            });
        }

        // Cancel Order (using updateOrderStatus)
        function cancelOrder(orderId) {
            updateOrderStatus(orderId, 'cancelled');
        }
    </script>