<?php   
session_start();
// Check if user is logged in
if (!isset($_SESSION['user_id']) || !isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: login.php");
    exit();
}

require_once 'inc/db.php';

$user_id = $_SESSION['user_id'];

// Fetch all orders for the user
$ordersQuery = "SELECT o.order_id, o.order_number, o.order_date, o.order_status, o.total_amount,
                       COUNT(oi.order_item_id) as item_count
                FROM orders o
                LEFT JOIN order_items oi ON o.order_id = oi.order_id
                WHERE o.user_id = ?
                GROUP BY o.order_id
                ORDER BY o.order_date DESC";
$ordersStmt = $conn->prepare($ordersQuery);
$ordersStmt->bind_param("i", $user_id);
$ordersStmt->execute();
$ordersResult = $ordersStmt->get_result();
$orders = [];
while ($order = $ordersResult->fetch_assoc()) {
    // Fetch product images for this order
    $imagesQuery = "SELECT pi.image_url 
                    FROM order_items oi
                    JOIN products p ON oi.product_id = p.product_id
                    LEFT JOIN product_images pi ON p.product_id = pi.product_id AND pi.is_primary = 1
                    WHERE oi.order_id = ?
                    LIMIT 3";
    $imagesStmt = $conn->prepare($imagesQuery);
    $imagesStmt->bind_param("i", $order['order_id']);
    $imagesStmt->execute();
    $imagesResult = $imagesStmt->get_result();
    $order['images'] = [];
    while ($image = $imagesResult->fetch_assoc()) {
        $order['images'][] = str_replace('../images/', './images/', $image['image_url']);
    }
    $imagesStmt->close();
    $orders[] = $order;
}
$ordersStmt->close();
?>
    <?php include 'inc/header.php'; ?>

    <section class="profile-con">
        <!-- Mobile Menu Toggle Button -->
        <button class="profile-menu-toggle" id="profileMenuToggle">
            <i class='bx bx-menu'></i>
            <span>Menu</span>
        </button>

        <!-- Overlay for mobile sidebar -->
        <div class="sidebar-overlay" id="sidebarOverlay"></div>

        <div class="profile-layout">
            <?php include 'inc/sidebar.php'; ?>

            <!-- Main Content -->
            <main class="profile-main">
                
                <!-- Orders Page Container -->
                <div class="orders-page-container">
                
                <div class="orders-page-header">
                    <h1>My Orders</h1>
                </div>

                <!-- Orders List -->
                <div class="orders-list">
                    <?php if (empty($orders)): ?>
                        <div class="empty-state" style="text-align: center; padding: 60px 20px;">
                            <i class='bx bx-package' style="font-size: 80px; color: #D1D5DB;"></i>
                            <h3 style="color: #374151; margin: 20px 0 10px;">No Orders Yet</h3>
                            <p style="color: #6B7280; margin-bottom: 30px;">Start shopping to see your orders here!</p>
                            <a href="shop.php" style="display: inline-block; padding: 12px 30px; background: #3C91E6; color: white; border-radius: 10px; text-decoration: none; font-weight: 500;">
                                <i class='bx bx-shopping-bag'></i> Browse Products
                            </a>
                        </div>
                    <?php else: ?>
                        <?php foreach ($orders as $order): 
                            $order_number = 'VV-' . str_pad($order['order_id'], 4, '0', STR_PAD_LEFT);
                            $actual_order_number = $order['order_number']; // Actual order number from database
                            $order_date = date('F d, Y', strtotime($order['order_date']));
                            $status_class = strtolower($order['order_status']);
                            if ($order['order_status'] === 'shipped') {
                                $status_class = 'in-transit';
                            }
                        ?>
                        <div class="order-card">
                            <div class="order-header">
                                <div class="order-icon-wrapper">
                                    <i class='bx bx-package'></i>
                                </div>
                                <div class="order-info">
                                    <h3 class="order-number">Order <?php echo $order_number; ?></h3>
                                    <p class="order-date"><?php echo $order_date; ?></p>
                                </div>
                                <span class="order-status <?php echo $status_class; ?>"><?php echo ucfirst($order['order_status']); ?></span>
                            </div>

                            <div class="order-body">
                                <div class="order-products">
                                    <?php if (!empty($order['images'])): ?>
                                        <?php foreach (array_slice($order['images'], 0, 3) as $image): ?>
                                            <div class="order-product-item">
                                                <img src="<?php echo htmlspecialchars($image); ?>" alt="Product" onerror="this.src='./images/product1.jpg'">
                                            </div>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <div class="order-product-item">
                                            <img src="./images/product1.jpg" alt="Product">
                                        </div>
                                    <?php endif; ?>
                                    <?php if ($order['item_count'] > 3): ?>
                                        <div class="order-product-more">+<?php echo ($order['item_count'] - 3); ?> more</div>
                                    <?php endif; ?>
                                </div>

                                <div class="order-amount">
                                    <span class="amount-label">Total Amount</span>
                                    <span class="amount-value">$<?php echo number_format($order['total_amount'], 2); ?></span>
                                </div>
                            </div>

                            <div class="order-footer">
                                <?php if ($order['order_status'] === 'delivered'): ?>
                                    <button class="order-action-btn rate-btn" onclick="rateProduct('<?php echo $actual_order_number; ?>')">
                                        <i class='bx bx-star'></i>
                                        <span>Rate Product</span>
                                    </button>
                                <?php elseif ($order['order_status'] === 'shipped'): ?>
                                    <button class="order-action-btn track-btn" onclick="trackOrder('<?php echo $actual_order_number; ?>')">
                                        <i class='bx bx-map'></i>
                                        <span>Track</span>
                                    </button>
                                <?php elseif ($order['order_status'] === 'processing'): ?>
                                    <button class="order-action-btn cancel-btn" onclick="cancelOrder('<?php echo $actual_order_number; ?>')">
                                        <i class='bx bx-x-circle'></i>
                                        <span>Cancel</span>
                                    </button>
                                <?php endif; ?>
                                <button class="order-action-btn view-details-btn" onclick="viewOrderDetails('<?php echo $actual_order_number; ?>')">
                                    <i class='bx bx-show'></i>
                                    <span>View Details</span>
                                </button>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>

                </div>

            </main>

        </div>

    </section>
</main>
    <?php include 'inc/footer.php'; ?>

    <script>
        // Toggle Mobile Sidebar
        const profileMenuToggle = document.getElementById('profileMenuToggle');
        const sidebarOverlay = document.getElementById('sidebarOverlay');
        const profileSidebar = document.querySelector('.profile-sidebar');

        profileMenuToggle.addEventListener('click', function() {
            profileSidebar.classList.toggle('active');
            sidebarOverlay.classList.toggle('active');
        });

        sidebarOverlay.addEventListener('click', function() {
            profileSidebar.classList.remove('active');
            sidebarOverlay.classList.remove('active');
        });        // View Order Details
        function viewOrderDetails(orderNumber) {
            Swal.fire({
                title: 'Loading...',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
            
            const formData = new FormData();
            formData.append('order_number', orderNumber);
            
            fetch('get_order_details.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const order = data.order;
                    
                    // Build items HTML
                    let itemsHtml = '<div style="margin: 20px 0;"><strong>Order Items:</strong></div>';
                    itemsHtml += '<div style="max-height: 300px; overflow-y: auto;">';
                    order.items.forEach(item => {
                        itemsHtml += `
                            <div style="display: flex; gap: 10px; margin-bottom: 15px; padding: 10px; border: 1px solid #e0e0e0; border-radius: 5px;">
                                <img src="${item.image_url || './images/product1.jpg'}" alt="${item.product_name}" style="width: 60px; height: 60px; object-fit: cover; border-radius: 5px;">
                                <div style="flex: 1; text-align: left;">
                                    <div style="font-weight: 600;">${item.product_name}</div>
                                    ${item.size ? `<div style="font-size: 0.85rem; color: #666;">Size: ${item.size}</div>` : ''}
                                    ${item.color ? `<div style="font-size: 0.85rem; color: #666;">Color: ${item.color}</div>` : ''}
                                    <div style="font-size: 0.85rem; color: #666;">Qty: ${item.quantity}</div>
                                </div>
                                <div style="font-weight: 600;">$${parseFloat(item.subtotal).toFixed(2)}</div>
                            </div>
                        `;
                    });
                    itemsHtml += '</div>';
                    
                    Swal.fire({
                        title: `Order ${order.order_number}`,
                        html: `
                            <div style="text-align: left;">
                                <p><strong>Order Date:</strong> ${order.order_date}</p>
                                <p><strong>Status:</strong> <span style="color: ${order.order_status === 'Delivered' ? '#10B981' : order.order_status === 'Cancelled' ? '#EF4444' : '#3C91E6'};">${order.order_status}</span></p>
                                <p><strong>Payment Status:</strong> ${order.payment_status}</p>
                                <p><strong>Payment Method:</strong> ${order.payment_method}</p>
                                ${order.shipped_date ? `<p><strong>Shipped Date:</strong> ${order.shipped_date}</p>` : ''}
                                ${order.delivered_date ? `<p><strong>Delivered Date:</strong> ${order.delivered_date}</p>` : ''}
                                ${order.tracking_number ? `<p><strong>Tracking Number:</strong> ${order.tracking_number}</p>` : ''}
                                <hr style="margin: 15px 0;">
                                ${itemsHtml}
                                <hr style="margin: 15px 0;">
                                <p><strong>Subtotal:</strong> $${order.subtotal}</p>
                                <p><strong>Shipping:</strong> $${order.shipping_cost}</p>
                                <p><strong>Tax:</strong> $${order.tax_amount}</p>
                                <p style="font-size: 1.1rem;"><strong>Total Amount:</strong> $${order.total_amount}</p>
                                <hr style="margin: 15px 0;">
                                <p><strong>Shipping Address:</strong></p>
                                <p style="font-size: 0.9rem; line-height: 1.5;">${order.shipping_address}</p>
                            </div>
                        `,
                        icon: 'info',
                        confirmButtonText: 'Close',
                        confirmButtonColor: '#3C91E6',
                        width: '600px'
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: data.message || 'Failed to load order details',
                        confirmButtonColor: '#EF4444'
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'An error occurred while loading order details',
                    confirmButtonColor: '#EF4444'
                });
            });
        }

        // Track Order
        function trackOrder(orderNumber) {
            Swal.fire({
                title: `Track Order ${orderNumber}`,
                html: `
                    <div style="text-align: left;">
                        <div style="margin-bottom: 15px;">
                            <strong>✓ Order Placed</strong><br>
                            <small>January 20, 2024 - 10:30 AM</small>
                        </div>
                        <div style="margin-bottom: 15px;">
                            <strong>✓ Processing</strong><br>
                            <small>January 20, 2024 - 2:15 PM</small>
                        </div>
                        <div style="margin-bottom: 15px;">
                            <strong>✓ Shipped</strong><br>
                            <small>January 21, 2024 - 9:00 AM</small>
                        </div>
                        <div style="margin-bottom: 15px;">
                            <strong style="color: #3C91E6;">→ In Transit</strong><br>
                            <small>Expected delivery: January 25, 2024</small>
                        </div>
                    </div>
                `,
                icon: 'info',
                confirmButtonText: 'Close',
                confirmButtonColor: '#3C91E6'
            });
        }

        // Cancel Order
        function cancelOrder(orderNumber) {
            Swal.fire({
                title: 'Cancel Order?',
                text: `Are you sure you want to cancel order ${orderNumber}?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#EF4444',
                cancelButtonColor: '#6B7280',
                confirmButtonText: 'Yes, cancel it!',
                cancelButtonText: 'No, keep it'
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Order Cancelled!',
                        text: 'Your order has been cancelled successfully.',
                        icon: 'success',
                        confirmButtonColor: '#3C91E6'
                    });
                }
            });
        }

        // Rate Product
        function rateProduct(orderNumber) {
            Swal.fire({
                title: 'Rate Your Product',
                html: `
                    <div style="text-align: center; padding: 20px 0;">
                        <p style="margin-bottom: 20px; color: #6B7280; font-size: 15px;">How would you rate your purchase?</p>
                        <div class="rating-stars" style="margin-bottom: 25px; display: flex; justify-content: center; gap: 10px;">
                            <i class='bx bx-star rating-star' data-rating="1" style="font-size: 40px; color: #D1D5DB; cursor: pointer; transition: all 0.2s;"></i>
                            <i class='bx bx-star rating-star' data-rating="2" style="font-size: 40px; color: #D1D5DB; cursor: pointer; transition: all 0.2s;"></i>
                            <i class='bx bx-star rating-star' data-rating="3" style="font-size: 40px; color: #D1D5DB; cursor: pointer; transition: all 0.2s;"></i>
                            <i class='bx bx-star rating-star' data-rating="4" style="font-size: 40px; color: #D1D5DB; cursor: pointer; transition: all 0.2s;"></i>
                            <i class='bx bx-star rating-star' data-rating="5" style="font-size: 40px; color: #D1D5DB; cursor: pointer; transition: all 0.2s;"></i>
                        </div>
                        <textarea id="reviewText" placeholder="Write your review here (optional)..." style="width: 100%; min-height: 100px; padding: 12px; border: 2px solid #E5E7EB; border-radius: 10px; font-family: inherit; font-size: 14px; resize: vertical; margin-top: 10px;"></textarea>
                    </div>
                `,
                showCancelButton: true,
                confirmButtonText: 'Submit Rating',
                cancelButtonText: 'Cancel',
                confirmButtonColor: '#3C91E6',
                cancelButtonColor: '#6B7280',
                didOpen: () => {
                    let selectedRating = 0;
                    const stars = document.querySelectorAll('.rating-star');
                    
                    stars.forEach(star => {
                        star.addEventListener('mouseenter', function() {
                            const rating = parseInt(this.getAttribute('data-rating'));
                            highlightStars(rating);
                        });
                        
                        star.addEventListener('click', function() {
                            selectedRating = parseInt(this.getAttribute('data-rating'));
                            highlightStars(selectedRating);
                        });
                    });
                    
                    document.querySelector('.rating-stars').addEventListener('mouseleave', function() {
                        if (selectedRating > 0) {
                            highlightStars(selectedRating);
                        } else {
                            stars.forEach(s => {
                                s.classList.remove('bxs-star');
                                s.classList.add('bx-star');
                                s.style.color = '#D1D5DB';
                            });
                        }
                    });
                    
                    function highlightStars(rating) {
                        stars.forEach((s, index) => {
                            if (index < rating) {
                                s.classList.remove('bx-star');
                                s.classList.add('bxs-star');
                                s.style.color = '#FFDD67';
                            } else {
                                s.classList.remove('bxs-star');
                                s.classList.add('bx-star');
                                s.style.color = '#D1D5DB';
                            }
                        });
                    }
                    
                    // Store selected rating for submission
                    Swal.getConfirmButton().addEventListener('click', function() {
                        if (selectedRating === 0) {
                            Swal.showValidationMessage('Please select a rating');
                            return false;
                        }
                    });
                },
                preConfirm: () => {
                    const rating = document.querySelectorAll('.bxs-star').length;
                    const review = document.getElementById('reviewText').value;
                    
                    if (rating === 0) {
                        Swal.showValidationMessage('Please select a rating');
                        return false;
                    }
                    
                    return { rating: rating, review: review };
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Thank You!',
                        html: `Your ${result.value.rating}-star rating has been submitted successfully.<br><small>We appreciate your feedback!</small>`,
                        icon: 'success',
                        confirmButtonColor: '#3C91E6'
                    });
                }
            });
        }
    </script>


