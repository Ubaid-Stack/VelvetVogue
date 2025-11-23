
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

                    <!-- Order Card 1 - Delivered -->
                    <div class="order-card">
                        <div class="order-header">
                            <div class="order-icon-wrapper">
                                <i class='bx bx-package'></i>
                            </div>
                            <div class="order-info">
                                <h3 class="order-number">Order VV-2024-001</h3>
                                <p class="order-date">January 15, 2024</p>
                            </div>
                            <span class="order-status delivered">Delivered</span>
                        </div>

                        <div class="order-body">
                            <div class="order-products">
                                <div class="order-product-item">
                                    <img src="https://via.placeholder.com/80/E5E7EB/6B7280?text=Product" alt="Product">
                                </div>
                                <div class="order-product-item">
                                    <img src="https://via.placeholder.com/80/E5E7EB/6B7280?text=Product" alt="Product">
                                </div>
                            </div>

                            <div class="order-amount">
                                <span class="amount-label">Total Amount</span>
                                <span class="amount-value">$289.99</span>
                            </div>
                        </div>

                        <div class="order-footer">
                            <button class="order-action-btn rate-btn" onclick="rateProduct('VV-2024-001')">
                                <i class='bx bx-star'></i>
                                <span>Rate Product</span>
                            </button>
                            <button class="order-action-btn view-details-btn" onclick="viewOrderDetails('VV-2024-001')">
                                <i class='bx bx-show'></i>
                                <span>View Details</span>
                            </button>
                        </div>
                    </div>

                    <!-- Order Card 2 - In Transit -->
                    <div class="order-card">
                        <div class="order-header">
                            <div class="order-icon-wrapper">
                                <i class='bx bx-package'></i>
                            </div>
                            <div class="order-info">
                                <h3 class="order-number">Order VV-2024-002</h3>
                                <p class="order-date">January 20, 2024</p>
                            </div>
                            <span class="order-status in-transit">In Transit</span>
                        </div>

                        <div class="order-body">
                            <div class="order-products">
                                <div class="order-product-item">
                                    <img src="https://via.placeholder.com/80/E5E7EB/6B7280?text=Product" alt="Product">
                                </div>
                            </div>

                            <div class="order-amount">
                                <span class="amount-label">Total Amount</span>
                                <span class="amount-value">$199.99</span>
                            </div>
                        </div>

                        <div class="order-footer">
                            <button class="order-action-btn track-btn" onclick="trackOrder('VV-2024-002')">
                                <i class='bx bx-map'></i>
                                <span>Track</span>
                            </button>
                            <button class="order-action-btn view-details-btn" onclick="viewOrderDetails('VV-2024-002')">
                                <i class='bx bx-show'></i>
                                <span>View Details</span>
                            </button>
                        </div>
                    </div>

                    <!-- Order Card 3 - Processing -->
                    <div class="order-card">
                        <div class="order-header">
                            <div class="order-icon-wrapper">
                                <i class='bx bx-package'></i>
                            </div>
                            <div class="order-info">
                                <h3 class="order-number">Order VV-2024-003</h3>
                                <p class="order-date">January 22, 2024</p>
                            </div>
                            <span class="order-status processing">Processing</span>
                        </div>

                        <div class="order-body">
                            <div class="order-products">
                                <div class="order-product-item">
                                    <img src="https://via.placeholder.com/80/E5E7EB/6B7280?text=Product" alt="Product">
                                </div>
                            </div>

                            <div class="order-amount">
                                <span class="amount-label">Total Amount</span>
                                <span class="amount-value">$159.99</span>
                            </div>
                        </div>

                        <div class="order-footer">
                            <button class="order-action-btn view-details-btn" onclick="viewOrderDetails('VV-2024-003')">
                                <i class='bx bx-show'></i>
                                <span>View Details</span>
                            </button>
                            <button class="order-action-btn cancel-btn" onclick="cancelOrder('VV-2024-003')">
                                <i class='bx bx-x-circle'></i>
                                <span>Cancel</span>
                            </button>
                        </div>
                    </div>

                    <!-- Order Card 4 - Cancelled -->
                    <div class="order-card">
                        <div class="order-header">
                            <div class="order-icon-wrapper">
                                <i class='bx bx-package'></i>
                            </div>
                            <div class="order-info">
                                <h3 class="order-number">Order VV-2024-004</h3>
                                <p class="order-date">January 10, 2024</p>
                            </div>
                            <span class="order-status cancelled">Cancelled</span>
                        </div>

                        <div class="order-body">
                            <div class="order-products">
                                <div class="order-product-item">
                                    <img src="https://via.placeholder.com/80/E5E7EB/6B7280?text=Product" alt="Product">
                                </div>
                                <div class="order-product-item">
                                    <img src="https://via.placeholder.com/80/E5E7EB/6B7280?text=Product" alt="Product">
                                </div>
                            </div>

                            <div class="order-amount">
                                <span class="amount-label">Total Amount</span>
                                <span class="amount-value">$349.99</span>
                            </div>
                        </div>

                        <div class="order-footer">
                            <button class="order-action-btn view-details-btn" onclick="viewOrderDetails('VV-2024-004')">
                                <i class='bx bx-show'></i>
                                <span>View Details</span>
                            </button>
                        </div>
                    </div>

                </div>

                </div>

            </main>

        </div>

    </section>

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
                title: `Order ${orderNumber}`,
                html: `
                    <div style="text-align: left;">
                        <p><strong>Order Date:</strong> January 15, 2024</p>
                        <p><strong>Status:</strong> Delivered</p>
                        <p><strong>Total Amount:</strong> $289.99</p>
                        <p><strong>Shipping Address:</strong> 123 Fashion Street, New York, NY 10001</p>
                        <p><strong>Payment Method:</strong> Credit Card (****1234)</p>
                    </div>
                `,
                icon: 'info',
                confirmButtonText: 'Close',
                confirmButtonColor: '#3C91E6'
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


