<?php 
session_start();
require_once './inc/db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id']) || !isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: login.php?redirect=cart.php');
    exit();
}

$user_id = $_SESSION['user_id'];

// Handle remove from cart
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    if ($_POST['action'] === 'remove' && isset($_POST['cart_id'])) {
        $cart_id = intval($_POST['cart_id']);
        $delete_sql = "DELETE FROM cart WHERE cart_id = ? AND user_id = ?";
        $delete_stmt = $conn->prepare($delete_sql);
        $delete_stmt->bind_param('ii', $cart_id, $user_id);
        $delete_stmt->execute();
        $delete_stmt->close();
        header('Location: cart.php');
        exit();
    } elseif ($_POST['action'] === 'update_quantity' && isset($_POST['cart_id']) && isset($_POST['quantity'])) {
        $cart_id = intval($_POST['cart_id']);
        $quantity = max(1, intval($_POST['quantity'])); // Minimum quantity is 1
        
        $update_sql = "UPDATE cart SET quantity = ? WHERE cart_id = ? AND user_id = ?";
        $update_stmt = $conn->prepare($update_sql);
        $update_stmt->bind_param('iii', $quantity, $cart_id, $user_id);
        $update_stmt->execute();
        $update_stmt->close();
        header('Location: cart.php');
        exit();
    }
}

// Fetch cart items
$cart_sql = "SELECT c.cart_id, c.quantity, c.product_id,
             p.product_name, p.price, p.stock_quantity,
             pi.image_url
             FROM cart c
             LEFT JOIN products p ON c.product_id = p.product_id
             LEFT JOIN product_images pi ON p.product_id = pi.product_id AND pi.is_primary = 1
             WHERE c.user_id = ? AND p.status = 'active'
             ORDER BY c.added_at DESC";

$cart_stmt = $conn->prepare($cart_sql);
$cart_stmt->bind_param('i', $user_id);
$cart_stmt->execute();
$cart_result = $cart_stmt->get_result();
$cart_items = [];
$subtotal = 0;
$total_items = 0;

while ($item = $cart_result->fetch_assoc()) {
    $cart_items[] = $item;
    $subtotal += $item['price'] * $item['quantity'];
    $total_items += $item['quantity'];
}
$cart_stmt->close();

include 'inc/header.php'; 
?>
    </main>

    <!-- Cart page content outside body-con wrapper -->
    <section class="cart-con">
        <div class="about-title">
            <h2 class="cart-title">My Shopping Cart</h2>
            <p class="cart-subtext">Review the items in your cart before proceeding to checkout.</p>
        </div>
        
        <?php if (empty($cart_items)): ?>
            <div class="empty-cart" style="text-align: center; padding: 60px 20px;">
                <i class='bx bx-cart' style="font-size: 80px; color: #ccc;"></i>
                <h3 style="margin-top: 20px; color: #666;">Your cart is empty</h3>
                <p style="margin-top: 10px; color: #999;">Add some products to get started!</p>
                <a href="shop.php" style="display: inline-block; margin-top: 20px; padding: 12px 30px; background: var(--primary-btn); color: white; text-decoration: none; border-radius: 5px;">Browse Products</a>
            </div>
        <?php else: ?>
            <div class="cart-items">
                <?php foreach ($cart_items as $item): 
                    // Handle image URL path conversion
                    $image_url = $item['image_url'] ?? './images/hero-img.png';
                    if (strpos($image_url, '../images/') === 0) {
                        $image_url = str_replace('../images/', './images/', $image_url);
                    }
                    $image_path = !empty($image_url) ? $image_url : './images/hero-img.png';
                    $item_total = $item['price'] * $item['quantity'];
                    $is_out_of_stock = $item['stock_quantity'] < 1;
                    $max_quantity = min($item['stock_quantity'], 10); // Limit to stock or 10, whichever is lower
                ?>
                <div class="cart-item" data-cart-id="<?php echo $item['cart_id']; ?>">
                    <div class="cart-item-header">
                        <img src="<?php echo htmlspecialchars($image_path); ?>" alt="<?php echo htmlspecialchars($item['product_name']); ?>">
                        <div class="item-info">
                            <h4><?php echo htmlspecialchars($item['product_name']); ?></h4>
                            <?php if ($is_out_of_stock): ?>
                                <p class="item-meta" style="color: #ef4444;">Out of Stock</p>
                            <?php else: ?>
                                <p class="item-meta">In Stock: <?php echo $item['stock_quantity']; ?> available</p>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="cart-item-actions">
                        <div class="qty-controls">
                            <button class="dec-btn" data-cart-id="<?php echo $item['cart_id']; ?>" data-quantity="<?php echo $item['quantity']; ?>" <?php echo $is_out_of_stock || $item['quantity'] <= 1 ? 'disabled' : ''; ?>>-</button>
                            <span class="item-quantity"><?php echo $item['quantity']; ?></span>
                            <button class="inc-btn" data-cart-id="<?php echo $item['cart_id']; ?>" data-quantity="<?php echo $item['quantity']; ?>" data-max="<?php echo $max_quantity; ?>" <?php echo $is_out_of_stock || $item['quantity'] >= $max_quantity ? 'disabled' : ''; ?>>+</button>
                        </div>
                        <div class="item-pricing">
                            <p class="unit-price">$<?php echo number_format($item['price'], 2); ?></p>
                            <p class="total-price">$<?php echo number_format($item_total, 2); ?></p>
                        </div>
                        <button class="remove-btn" data-cart-id="<?php echo $item['cart_id']; ?>"><i class='bx bx-trash'></i></button>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <div class="cart-summary">
                <h3>Cart Summary</h3>
                <p>Total Items: <span id="totalItems"><?php echo $total_items; ?></span></p>
                <p>Subtotal: <span id="subtotal">$<?php echo number_format($subtotal, 2); ?></span></p>
                <p>Shipping: <span>Free</span></p>
                <p>Total Price: <span id="totalPrice">$<?php echo number_format($subtotal, 2); ?></span></p>
                <a href="checkout.php"><button class="checkout-btn">Proceed to Checkout</button></a>
            </div>
        <?php endif; ?>
    </section>

    <script>
        // Remove from cart
        document.querySelectorAll('.remove-btn').forEach(button => {
            button.addEventListener('click', function() {
                const cartId = this.getAttribute('data-cart-id');
                
                Swal.fire({
                    icon: 'warning',
                    title: 'Remove Item?',
                    text: 'Are you sure you want to remove this item from your cart?',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, remove it',
                    cancelButtonText: 'Cancel',
                    confirmButtonColor: '#ef4444'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Submit form to remove item
                        const form = document.createElement('form');
                        form.method = 'POST';
                        form.innerHTML = `
                            <input type="hidden" name="action" value="remove">
                            <input type="hidden" name="cart_id" value="${cartId}">
                        `;
                        document.body.appendChild(form);
                        form.submit();
                    }
                });
            });
        });

        // Decrease quantity
        document.querySelectorAll('.dec-btn').forEach(button => {
            button.addEventListener('click', function() {
                const cartId = this.getAttribute('data-cart-id');
                const currentQty = parseInt(this.getAttribute('data-quantity'));
                const newQty = Math.max(1, currentQty - 1);
                
                if (newQty > 0) {
                    updateQuantity(cartId, newQty);
                }
            });
        });

        // Increase quantity
        document.querySelectorAll('.inc-btn').forEach(button => {
            button.addEventListener('click', function() {
                const cartId = this.getAttribute('data-cart-id');
                const currentQty = parseInt(this.getAttribute('data-quantity'));
                const maxQty = parseInt(this.getAttribute('data-max'));
                const newQty = Math.min(maxQty, currentQty + 1);
                
                if (newQty <= maxQty) {
                    updateQuantity(cartId, newQty);
                } else {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Stock Limit',
                        text: 'Maximum available quantity reached',
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 2000
                    });
                }
            });
        });

        function updateQuantity(cartId, quantity) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.innerHTML = `
                <input type="hidden" name="action" value="update_quantity">
                <input type="hidden" name="cart_id" value="${cartId}">
                <input type="hidden" name="quantity" value="${quantity}">
            `;
            document.body.appendChild(form);
            form.submit();
        }
    </script>
<?php include 'inc/footer.php'; ?>