<?php 
session_start();
require_once './inc/db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id']) || !isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: login.php?redirect=wishlist.php');
    exit();
}

$user_id = $_SESSION['user_id'];

// Handle remove from wishlist
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'remove') {
    $wishlist_id = intval($_POST['wishlist_id']);
    $delete_sql = "DELETE FROM wishlist WHERE wishlist_id = ? AND user_id = ?";
    $delete_stmt = $conn->prepare($delete_sql);
    $delete_stmt->bind_param('ii', $wishlist_id, $user_id);
    $delete_stmt->execute();
    $delete_stmt->close();
    
    header('Location: wishlist.php');
    exit();
}

// Fetch wishlist items
$sql = "SELECT w.wishlist_id, w.product_id, p.product_name, p.price, p.original_price, p.discount_percentage, 
        pi.image_url, p.stock_quantity
        FROM wishlist w
        JOIN products p ON w.product_id = p.product_id
        LEFT JOIN product_images pi ON p.product_id = pi.product_id AND pi.is_primary = 1
        WHERE w.user_id = ?
        ORDER BY w.added_at DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $user_id);
$stmt->execute();
$result = $stmt->get_result();
$wishlist_items = [];
while ($row = $result->fetch_assoc()) {
    $wishlist_items[] = $row;
}
$stmt->close();
?>
<?php include 'inc/header.php'; ?>
    </main>
    
    <!-- Wishlist page content outside body-con wrapper -->
    <section class="wishlist-con">
        <h2 class="wishlist-title">My Wishlist</h2>
        <p class="wishlist-subtext">Here are the items you've added to your wishlist.</p>
        <div class="wishlist-items">
            <?php if (empty($wishlist_items)): ?>
                <div style="text-align: center; padding: 60px 20px; grid-column: 1/-1;">
                    <i class='bx bx-heart' style="font-size: 80px; color: #ccc;"></i>
                    <h3 style="margin-top: 20px; color: #666;">Your wishlist is empty</h3>
                    <p style="margin-top: 10px; color: #999;">Start adding items you love!</p>
                    <a href="shop.php" style="display: inline-block; margin-top: 20px; padding: 12px 30px; background: var(--primary-btn); color: white; text-decoration: none; border-radius: 8px;">Browse Products</a>
                </div>
            <?php else: ?>
                <?php foreach ($wishlist_items as $item): 
                    // Handle image URL path conversion
                    $image_url = $item['image_url'] ?? './images/hero-img.png';
                    if (strpos($image_url, '../images/') === 0) {
                        $image_url = str_replace('../images/', './images/', $image_url);
                    }
                    $image_path = !empty($image_url) ? $image_url : './images/hero-img.png';
                    $display_price = $item['discount_percentage'] > 0 ? $item['price'] : $item['original_price'];
                    $in_stock = $item['stock_quantity'] > 0;
                ?>
                <div class="wishlist-item">
                    <img src="<?php echo htmlspecialchars($image_path); ?>" alt="<?php echo htmlspecialchars($item['product_name']); ?>">
                    <div class="item-details">
                        <h4><?php echo htmlspecialchars($item['product_name']); ?></h4>
                        <p>$<?php echo number_format($display_price, 2); ?></p>
                        <?php if (!$in_stock): ?>
                            <p style="color: #ef4444; font-size: 14px; margin-top: 5px;">Out of Stock</p>
                        <?php endif; ?>
                        <div class="button-group">
                            <form method="POST" style="display: inline;">
                                <input type="hidden" name="action" value="remove">
                                <input type="hidden" name="wishlist_id" value="<?php echo $item['wishlist_id']; ?>">
                                <button type="submit" class="remove-btn">Remove</button>
                            </form>
                            <button class="add-to-cart-btn" data-product-id="<?php echo $item['product_id']; ?>" <?php echo !$in_stock ? 'disabled style="opacity: 0.5; cursor: not-allowed;"' : ''; ?>>
                                <i class='bx bx-cart'></i> <?php echo $in_stock ? 'Add to Cart' : 'Out of Stock'; ?>
                            </button>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </section>

    <script>
        // Add to Cart functionality
        document.querySelectorAll('.add-to-cart-btn').forEach(button => {
            button.addEventListener('click', function() {
                if (this.disabled) return;
                
                const productId = this.getAttribute('data-product-id');
                
                Swal.fire({
                    icon: 'success',
                    title: 'Added to Cart!',
                    text: 'Product has been added to your cart',
                    showConfirmButton: false,
                    timer: 1500,
                    toast: true,
                    position: 'top-end'
                });
            });
        });
    </script>

<?php include 'inc/footer.php'; ?>