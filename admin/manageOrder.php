<?php 
$pageTitle = 'Manage Orders';
$pageSubtitle = 'Track and manage customer orders';
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
                    <h3 class="stat-number">8</h3>
                </div>

                <div class="order-stat-card pending">
                    <span class="stat-label">Pending</span>
                    <h3 class="stat-number">1</h3>
                </div>

                <div class="order-stat-card processing">
                    <span class="stat-label">Processing</span>
                    <h3 class="stat-number">2</h3>
                </div>

                <div class="order-stat-card shipped">
                    <span class="stat-label">Shipped</span>
                    <h3 class="stat-number">2</h3>
                </div>

                <div class="order-stat-card delivered">
                    <span class="stat-label">Delivered</span>
                    <h3 class="stat-number">2</h3>
                </div>

                <div class="order-stat-card revenue">
                    <span class="stat-label">Revenue</span>
                    <h3 class="stat-number">$3,939</h3>
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
                <button class="order-tab active" data-status="all">All Orders</button>
                <button class="order-tab" data-status="pending">Pending (1)</button>
                <button class="order-tab" data-status="processing">Processing (2)</button>
                <button class="order-tab" data-status="shipped">Shipped (2)</button>
                <button class="order-tab" data-status="delivered">Delivered (2)</button>
                <button class="order-tab" data-status="cancelled">Cancelled (1)</button>
            </div>

            <!-- Orders List -->
            <div class="orders-list">
                
                <!-- Order Card 1 -->
                <div class="order-card" data-status="pending">
                    <div class="order-header">
                        <div class="order-info">
                            <h3 class="order-number">#VV-10234</h3>
                            <p class="order-customer">Emma Thompson</p>
                        </div>
                        <span class="order-status-badge pending">Pending</span>
                    </div>
                    <div class="order-details">
                        <div class="order-meta">
                            <div class="meta-item">
                                <span class="meta-label">Date</span>
                                <span class="meta-value">Jan 15, 2024</span>
                            </div>
                            <div class="meta-item">
                                <span class="meta-label">Items</span>
                                <span class="meta-value">3 items</span>
                            </div>
                            <div class="meta-item">
                                <span class="meta-label">Total</span>
                                <span class="meta-value">$789.00</span>
                            </div>
                        </div>
                    </div>
                    <div class="order-actions">
                        <button class="btn-action view" onclick="viewOrderDetails('10234')">
                            <i class='bx bx-show'></i>
                            <span>View Details</span>
                        </button>
                        <button class="btn-action process" onclick="updateOrderStatus('10234', 'processing')">
                            <i class='bx bx-check-circle'></i>
                            <span>Mark as Processing</span>
                        </button>
                        <button class="btn-action cancel" onclick="cancelOrder('10234')">
                            <span>Cancel</span>
                        </button>
                    </div>
                </div>

                <!-- Order Card 2 -->
                <div class="order-card" data-status="processing">
                    <div class="order-header">
                        <div class="order-info">
                            <h3 class="order-number">#VV-10233</h3>
                            <p class="order-customer">James Wilson</p>
                        </div>
                        <span class="order-status-badge processing">Processing</span>
                    </div>
                    <div class="order-details">
                        <div class="order-meta">
                            <div class="meta-item">
                                <span class="meta-label">Date</span>
                                <span class="meta-value">Jan 14, 2024</span>
                            </div>
                            <div class="meta-item">
                                <span class="meta-label">Items</span>
                                <span class="meta-value">2 items</span>
                            </div>
                            <div class="meta-item">
                                <span class="meta-label">Total</span>
                                <span class="meta-value">$630.00</span>
                            </div>
                        </div>
                    </div>
                    <div class="order-actions">
                        <button class="btn-action view" onclick="viewOrderDetails('10233')">
                            <i class='bx bx-show'></i>
                            <span>View Details</span>
                        </button>
                        <button class="btn-action ship" onclick="updateOrderStatus('10233', 'shipped')">
                            <i class='bx bx-check-circle'></i>
                            <span>Mark as Shipped</span>
                        </button>
                        <button class="btn-action cancel" onclick="cancelOrder('10233')">
                            <span>Cancel</span>
                        </button>
                    </div>
                </div>

                <!-- Order Card 3 -->
                <div class="order-card" data-status="shipped">
                    <div class="order-header">
                        <div class="order-info">
                            <h3 class="order-number">#VV-10232</h3>
                            <p class="order-customer">Sophia Martinez</p>
                        </div>
                        <span class="order-status-badge shipped">Shipped</span>
                    </div>
                    <div class="order-details">
                        <div class="order-meta">
                            <div class="meta-item">
                                <span class="meta-label">Date</span>
                                <span class="meta-value">Jan 13, 2024</span>
                            </div>
                            <div class="meta-item">
                                <span class="meta-label">Items</span>
                                <span class="meta-value">1 items</span>
                            </div>
                            <div class="meta-item">
                                <span class="meta-label">Total</span>
                                <span class="meta-value">$320.00</span>
                            </div>
                        </div>
                    </div>
                    <div class="order-actions">
                        <button class="btn-action view" onclick="viewOrderDetails('10232')">
                            <i class='bx bx-show'></i>
                            <span>View Details</span>
                        </button>
                        <button class="btn-action deliver" onclick="updateOrderStatus('10232', 'delivered')">
                            <i class='bx bx-check-circle'></i>
                            <span>Mark as Delivered</span>
                        </button>
                        <button class="btn-action cancel" onclick="cancelOrder('10232')">
                            <span>Cancel</span>
                        </button>
                    </div>
                </div>

                <!-- Order Card 4 -->
                <div class="order-card" data-status="delivered">
                    <div class="order-header">
                        <div class="order-info">
                            <h3 class="order-number">#VV-10231</h3>
                            <p class="order-customer">Ava Davis</p>
                        </div>
                        <span class="order-status-badge delivered">Delivered</span>
                    </div>
                    <div class="order-details">
                        <div class="order-meta">
                            <div class="meta-item">
                                <span class="meta-label">Date</span>
                                <span class="meta-value">Jan 12, 2024</span>
                            </div>
                            <div class="meta-item">
                                <span class="meta-label">Items</span>
                                <span class="meta-value">2 items</span>
                            </div>
                            <div class="meta-item">
                                <span class="meta-label">Total</span>
                                <span class="meta-value">$400.00</span>
                            </div>
                        </div>
                    </div>
                    <div class="order-actions">
                        <button class="btn-action view" onclick="viewOrderDetails('10231')">
                            <i class='bx bx-show'></i>
                            <span>View Details</span>
                        </button>
                    </div>
                </div>

                <!-- Order Card 5 -->
                <div class="order-card" data-status="processing">
                    <div class="order-header">
                        <div class="order-info">
                            <h3 class="order-number">#VV-10230</h3>
                            <p class="order-customer">Liam Johnson</p>
                        </div>
                        <span class="order-status-badge processing">Processing</span>
                    </div>
                    <div class="order-details">
                        <div class="order-meta">
                            <div class="meta-item">
                                <span class="meta-label">Date</span>
                                <span class="meta-value">Jan 11, 2024</span>
                            </div>
                            <div class="meta-item">
                                <span class="meta-label">Items</span>
                                <span class="meta-value">4 items</span>
                            </div>
                            <div class="meta-item">
                                <span class="meta-label">Total</span>
                                <span class="meta-value">$920.00</span>
                            </div>
                        </div>
                    </div>
                    <div class="order-actions">
                        <button class="btn-action view" onclick="viewOrderDetails('10230')">
                            <i class='bx bx-show'></i>
                            <span>View Details</span>
                        </button>
                        <button class="btn-action ship" onclick="updateOrderStatus('10230', 'shipped')">
                            <i class='bx bx-check-circle'></i>
                            <span>Mark as Shipped</span>
                        </button>
                        <button class="btn-action cancel" onclick="cancelOrder('10230')">
                            <span>Cancel</span>
                        </button>
                    </div>
                </div>

                <!-- Order Card 6 -->
                <div class="order-card" data-status="shipped">
                    <div class="order-header">
                        <div class="order-info">
                            <h3 class="order-number">#VV-10229</h3>
                            <p class="order-customer">Olivia Brown</p>
                        </div>
                        <span class="order-status-badge shipped">Shipped</span>
                    </div>
                    <div class="order-details">
                        <div class="order-meta">
                            <div class="meta-item">
                                <span class="meta-label">Date</span>
                                <span class="meta-value">Jan 10, 2024</span>
                            </div>
                            <div class="meta-item">
                                <span class="meta-label">Items</span>
                                <span class="meta-value">1 items</span>
                            </div>
                            <div class="meta-item">
                                <span class="meta-label">Total</span>
                                <span class="meta-value">$280.00</span>
                            </div>
                        </div>
                    </div>
                    <div class="order-actions">
                        <button class="btn-action view" onclick="viewOrderDetails('10229')">
                            <i class='bx bx-show'></i>
                            <span>View Details</span>
                        </button>
                        <button class="btn-action deliver" onclick="updateOrderStatus('10229', 'delivered')">
                            <i class='bx bx-check-circle'></i>
                            <span>Mark as Delivered</span>
                        </button>
                        <button class="btn-action cancel" onclick="cancelOrder('10229')">
                            <span>Cancel</span>
                        </button>
                    </div>
                </div>

                <!-- Order Card 7 -->
                <div class="order-card" data-status="delivered">
                    <div class="order-header">
                        <div class="order-info">
                            <h3 class="order-number">#VV-10228</h3>
                            <p class="order-customer">Noah Garcia</p>
                        </div>
                        <span class="order-status-badge delivered">Delivered</span>
                    </div>
                    <div class="order-details">
                        <div class="order-meta">
                            <div class="meta-item">
                                <span class="meta-label">Date</span>
                                <span class="meta-value">Jan 09, 2024</span>
                            </div>
                            <div class="meta-item">
                                <span class="meta-label">Items</span>
                                <span class="meta-value">3 items</span>
                            </div>
                            <div class="meta-item">
                                <span class="meta-label">Total</span>
                                <span class="meta-value">$560.00</span>
                            </div>
                        </div>
                    </div>
                    <div class="order-actions">
                        <button class="btn-action view" onclick="viewOrderDetails('10228')">
                            <i class='bx bx-show'></i>
                            <span>View Details</span>
                        </button>
                    </div>
                </div>

                <!-- Order Card 8 -->
                <div class="order-card" data-status="cancelled">
                    <div class="order-header">
                        <div class="order-info">
                            <h3 class="order-number">#VV-10227</h3>
                            <p class="order-customer">Isabella Lee</p>
                        </div>
                        <span class="order-status-badge cancelled">Cancelled</span>
                    </div>
                    <div class="order-details">
                        <div class="order-meta">
                            <div class="meta-item">
                                <span class="meta-label">Date</span>
                                <span class="meta-value">Jan 08, 2024</span>
                            </div>
                            <div class="meta-item">
                                <span class="meta-label">Items</span>
                                <span class="meta-value">2 items</span>
                            </div>
                            <div class="meta-item">
                                <span class="meta-label">Total</span>
                                <span class="meta-value">$440.00</span>
                            </div>
                        </div>
                    </div>
                    <div class="order-actions">
                        <button class="btn-action view" onclick="viewOrderDetails('10227')">
                            <i class='bx bx-show'></i>
                            <span>View Details</span>
                        </button>
                    </div>
                </div>

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
            // Sample order data - replace with actual API call
            const orderData = {
                '10234': {
                    number: '#VV-10234',
                    date: 'Jan 15, 2024',
                    status: 'pending',
                    statusText: 'Pending',
                    total: '$789.00',
                    customer: 'Emma Thompson',
                    email: 'emma.thompson@email.com',
                    phone: '+1 (555) 123-4567',
                    address: '123 Main St, New York, NY 10001',
                    subtotal: '$728.00',
                    shipping: '$45.00',
                    tax: '$16.00',
                    items: [
                        { name: 'Velvet Evening Dress', size: 'M', color: 'Burgundy', price: '$350.00', qty: 1, total: '$350.00' },
                        { name: 'Silk Blouse', size: 'S', color: 'White', price: '$189.00', qty: 2, total: '$378.00' }
                    ]
                },
                '10233': {
                    number: '#VV-10233',
                    date: 'Jan 14, 2024',
                    status: 'processing',
                    statusText: 'Processing',
                    total: '$630.00',
                    customer: 'James Wilson',
                    email: 'james.wilson@email.com',
                    phone: '+1 (555) 234-5678',
                    address: '456 Oak Ave, Los Angeles, CA 90001',
                    subtotal: '$580.00',
                    shipping: '$35.00',
                    tax: '$15.00',
                    items: [
                        { name: 'Designer Handbag', size: 'One Size', color: 'Black', price: '$420.00', qty: 1, total: '$420.00' },
                        { name: 'Leather Belt', size: 'M', color: 'Brown', price: '$160.00', qty: 1, total: '$160.00' }
                    ]
                }
            };

            // Get order data or use default
            const order = orderData[orderId] || orderData['10234'];

            // Populate modal
            document.getElementById('modalOrderNumber').textContent = order.number;
            document.getElementById('modalOrderDate').textContent = order.date;
            document.getElementById('modalOrderStatus').textContent = order.statusText;
            document.getElementById('modalOrderStatus').className = `order-status-badge ${order.status}`;
            document.getElementById('modalOrderTotal').textContent = order.total;
            document.getElementById('modalCustomerName').textContent = order.customer;
            document.getElementById('modalCustomerEmail').textContent = order.email;
            document.getElementById('modalCustomerPhone').textContent = order.phone;
            document.getElementById('modalShippingAddress').textContent = order.address;
            document.getElementById('modalSubtotal').textContent = order.subtotal;
            document.getElementById('modalShipping').textContent = order.shipping;
            document.getElementById('modalTax').textContent = order.tax;
            document.getElementById('modalSummaryTotal').textContent = order.total;

            // Populate items
            const itemsList = document.getElementById('modalOrderItems');
            itemsList.innerHTML = order.items.map(item => `
                <div class="order-item">
                    <div class="item-image">
                        <img src="../images/hero-img.png" alt="${item.name}">
                    </div>
                    <div class="item-details">
                        <h4 class="item-name">${item.name}</h4>
                        <p class="item-meta">Size: ${item.size} | Color: ${item.color}</p>
                        <p class="item-price">${item.price} × ${item.qty}</p>
                    </div>
                    <div class="item-total">
                        <span class="item-total-price">${item.total}</span>
                    </div>
                </div>
            `).join('');

            // Show modal
            document.getElementById('orderDetailsModal').classList.add('active');
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
                text: `Mark order #VV-${orderId} as ${statusText}?`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3C91E6',
                cancelButtonColor: '#6B7280',
                confirmButtonText: 'Yes, update it!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Here you would send the update to your backend
                    
                    Toast.fire({
                        icon: 'success',
                        title: `Order #VV-${orderId} marked as ${statusText}!`
                    });
                }
            });
        }

        // Cancel Order
        function cancelOrder(orderId) {
            Swal.fire({
                title: 'Cancel Order?',
                text: `Are you sure you want to cancel order #VV-${orderId}?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#EF4444',
                cancelButtonColor: '#6B7280',
                confirmButtonText: 'Yes, cancel it!',
                cancelButtonText: 'Go Back'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Here you would send the cancellation to your backend
                    
                    Toast.fire({
                        icon: 'success',
                        title: `Order #VV-${orderId} cancelled successfully!`
                    });
                }
            });
        }
    </script>