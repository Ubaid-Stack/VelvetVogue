<?php 
$pageTitle = 'Manage Products';
$pageSubtitle = 'Add, edit, and organize your product catalog';
?>
<?php include './inc/head.php'; ?>

<?php include './inc/sidbar.php'; ?>
    
<?php include '../inc/db.php'; ?>

<?php include './inc/topbar.php'; ?>
<?php  
// Handle Add Product
if (isset($_POST['add_product'])) {
    $product_name = $conn->real_escape_string($_POST['product_name']);
    $category_id = intval($_POST['category_id']);
    $sku = $conn->real_escape_string($_POST['sku']);
    $price = floatval($_POST['price']);
    $original_price = isset($_POST['original_price']) && $_POST['original_price'] ? floatval($_POST['original_price']) : NULL;
    $stock_quantity = intval($_POST['stock_quantity']);
    $low_stock_threshold = isset($_POST['low_stock_threshold']) ? intval($_POST['low_stock_threshold']) : 10;
    $short_description = $conn->real_escape_string($_POST['short_description']);
    $description = $conn->real_escape_string($_POST['description']);
    $status = $conn->real_escape_string($_POST['status']);
    
    // Handle checkboxes
    $is_featured = isset($_POST['is_featured']) ? 1 : 0;
    $is_new_arrival = isset($_POST['is_new_arrival']) ? 1 : 0;
    $is_on_sale = isset($_POST['is_on_sale']) ? 1 : 0;
    
    // Calculate discount percentage if original price exists
    $discount_percentage = 0;
    if ($original_price && $original_price > $price) {
        $discount_percentage = round((($original_price - $price) / $original_price) * 100);
    }
    
    // Generate slug
    $product_slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $product_name)));
    
    // Handle image upload
    $image_url = NULL;
    if (isset($_FILES['productImage']) && $_FILES['productImage']['error'] == 0) {
        $allowed = ['jpg', 'jpeg', 'png', 'webp'];
        $filename = $_FILES['productImage']['name'];
        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        
        if (in_array($ext, $allowed)) {
            $new_filename = uniqid() . '_' . time() . '.' . $ext;
            $upload_path = '../images/products/' . $new_filename;
            
            if (move_uploaded_file($_FILES['productImage']['tmp_name'], $upload_path)) {
                $image_url = '../images/products/' . $new_filename;
            }
        }
    }
    
    $stmt = $conn->prepare("INSERT INTO products (product_name, product_slug, sku, category_id, description, short_description, price, original_price, discount_percentage, stock_quantity, low_stock_threshold, is_featured, is_new_arrival, is_on_sale, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssissddiiiiiis", $product_name, $product_slug, $sku, $category_id, $description, $short_description, $price, $original_price, $discount_percentage, $stock_quantity, $low_stock_threshold, $is_featured, $is_new_arrival, $is_on_sale, $status);
    
    if ($stmt->execute()) {
        $product_id = $stmt->insert_id;
        
        // Insert image into product_images table if uploaded
        if ($image_url) {
            $imgStmt = $conn->prepare("INSERT INTO product_images (product_id, image_url, is_primary) VALUES (?, ?, TRUE)");
            $imgStmt->bind_param("is", $product_id, $image_url);
            $imgStmt->execute();
            $imgStmt->close();
        }
        
        // Handle product variants
        if (isset($_POST['variants']) && is_array($_POST['variants'])) {
            // Check if color_hex column exists
            $checkColumn = $conn->query("SHOW COLUMNS FROM product_variants LIKE 'color_hex'");
            $hasColorHex = ($checkColumn && $checkColumn->num_rows > 0);
            
            if ($hasColorHex) {
                $variantStmt = $conn->prepare("INSERT INTO product_variants (product_id, size, color, color_hex, additional_price, stock_quantity, sku, is_available) VALUES (?, ?, ?, ?, ?, ?, ?, 1)");
            } else {
                $variantStmt = $conn->prepare("INSERT INTO product_variants (product_id, size, color, additional_price, stock_quantity, sku, is_available) VALUES (?, ?, ?, ?, ?, ?, 1)");
            }
            
            foreach ($_POST['variants'] as $variant) {
                if (!empty($variant['size']) || !empty($variant['color'])) {
                    $size = $conn->real_escape_string($variant['size']);
                    $color = $conn->real_escape_string($variant['color']);
                    $additional_price = floatval($variant['additional_price']);
                    $variant_stock = intval($variant['stock']);
                    $variant_sku = !empty($variant['sku']) ? $conn->real_escape_string($variant['sku']) : NULL;
                    
                    if ($hasColorHex) {
                        $color_hex = !empty($variant['color_hex']) ? $conn->real_escape_string($variant['color_hex']) : '#000000';
                        $variantStmt->bind_param("isssdis", $product_id, $size, $color, $color_hex, $additional_price, $variant_stock, $variant_sku);
                    } else {
                        $variantStmt->bind_param("issdis", $product_id, $size, $color, $additional_price, $variant_stock, $variant_sku);
                    }
                    
                    $variantStmt->execute();
                }
            }
            $variantStmt->close();
        }
        
        $toastMsg = "Product added successfully.";
        $toastType = "success";
    } else {
        $toastMsg = "Failed to add product: " . $stmt->error;
        $toastType = "danger";
    }
    $stmt->close();
}

// Handle Edit Product
if (isset($_POST['edit_product'])) {
    $product_id = intval($_POST['product_id']);
    $product_name = $conn->real_escape_string($_POST['product_name']);
    $category_id = intval($_POST['category_id']);
    $sku = $conn->real_escape_string($_POST['sku']);
    $price = floatval($_POST['price']);
    $original_price = isset($_POST['original_price']) && $_POST['original_price'] ? floatval($_POST['original_price']) : NULL;
    $stock_quantity = intval($_POST['stock_quantity']);
    $low_stock_threshold = isset($_POST['low_stock_threshold']) ? intval($_POST['low_stock_threshold']) : 10;
    $short_description = $conn->real_escape_string($_POST['short_description']);
    $description = $conn->real_escape_string($_POST['description']);
    $status = $conn->real_escape_string($_POST['status']);
    
    // Handle checkboxes
    $is_featured = isset($_POST['is_featured']) ? 1 : 0;
    $is_new_arrival = isset($_POST['is_new_arrival']) ? 1 : 0;
    $is_on_sale = isset($_POST['is_on_sale']) ? 1 : 0;
    
    // Calculate discount percentage
    $discount_percentage = 0;
    if ($original_price && $original_price > $price) {
        $discount_percentage = round((($original_price - $price) / $original_price) * 100);
    }
    
    // Generate slug
    $product_slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $product_name)));
    
    // Handle image upload
    if (isset($_FILES['productImage']) && $_FILES['productImage']['error'] == 0) {
        $allowed = ['jpg', 'jpeg', 'png', 'webp'];
        $filename = $_FILES['productImage']['name'];
        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        
        if (in_array($ext, $allowed)) {
            $new_filename = uniqid() . '_' . time() . '.' . $ext;
            $upload_path = '../images/products/' . $new_filename;
            
            if (move_uploaded_file($_FILES['productImage']['tmp_name'], $upload_path)) {
                $image_url = '../images/products/' . $new_filename;
                
                // Delete old image from product_images table
                $conn->query("DELETE FROM product_images WHERE product_id=$product_id");
                
                // Insert new image
                $imgStmt = $conn->prepare("INSERT INTO product_images (product_id, image_url, is_primary) VALUES (?, ?, TRUE)");
                $imgStmt->bind_param("is", $product_id, $image_url);
                $imgStmt->execute();
                $imgStmt->close();
            }
        }
    }
    
    $stmt = $conn->prepare("UPDATE products SET product_name=?, product_slug=?, sku=?, category_id=?, description=?, short_description=?, price=?, original_price=?, discount_percentage=?, stock_quantity=?, low_stock_threshold=?, is_featured=?, is_new_arrival=?, is_on_sale=?, status=? WHERE product_id=?");
    $stmt->bind_param("sssissddiiiiiisi", $product_name, $product_slug, $sku, $category_id, $description, $short_description, $price, $original_price, $discount_percentage, $stock_quantity, $low_stock_threshold, $is_featured, $is_new_arrival, $is_on_sale, $status, $product_id);
    
    if ($stmt->execute()) {
        // Handle product variants - delete old and insert new
        if (isset($_POST['variants']) && is_array($_POST['variants'])) {
            // Delete existing variants
            $conn->query("DELETE FROM product_variants WHERE product_id=$product_id");
            
            // Check if color_hex column exists
            $checkColumn = $conn->query("SHOW COLUMNS FROM product_variants LIKE 'color_hex'");
            $hasColorHex = ($checkColumn && $checkColumn->num_rows > 0);
            
            if ($hasColorHex) {
                $variantStmt = $conn->prepare("INSERT INTO product_variants (product_id, size, color, color_hex, additional_price, stock_quantity, sku, is_available) VALUES (?, ?, ?, ?, ?, ?, ?, 1)");
            } else {
                $variantStmt = $conn->prepare("INSERT INTO product_variants (product_id, size, color, additional_price, stock_quantity, sku, is_available) VALUES (?, ?, ?, ?, ?, ?, 1)");
            }
            
            foreach ($_POST['variants'] as $variant) {
                if (!empty($variant['size']) || !empty($variant['color'])) {
                    $size = $conn->real_escape_string($variant['size']);
                    $color = $conn->real_escape_string($variant['color']);
                    $additional_price = floatval($variant['additional_price']);
                    $variant_stock = intval($variant['stock']);
                    $variant_sku = !empty($variant['sku']) ? $conn->real_escape_string($variant['sku']) : NULL;
                    
                    if ($hasColorHex) {
                        $color_hex = !empty($variant['color_hex']) ? $conn->real_escape_string($variant['color_hex']) : '#000000';
                        $variantStmt->bind_param("isssdis", $product_id, $size, $color, $color_hex, $additional_price, $variant_stock, $variant_sku);
                    } else {
                        $variantStmt->bind_param("issdis", $product_id, $size, $color, $additional_price, $variant_stock, $variant_sku);
                    }
                    
                    $variantStmt->execute();
                }
            }
            $variantStmt->close();
        }
        
        $toastMsg = "Product updated successfully.";
        $toastType = "success";
    } else {
        $toastMsg = "Failed to update product: " . $stmt->error;
        $toastType = "danger";
    }
    $stmt->close();
}

// Handle Delete Product
if (isset($_POST['delete_product'])) {
    $product_id = intval($_POST['product_id']);
    
    $stmt = $conn->prepare("DELETE FROM products WHERE product_id=?");
    $stmt->bind_param("i", $product_id);
    
    if ($stmt->execute()) {
        $toastMsg = "Product deleted successfully.";
        $toastType = "success";
    } else {
        $toastMsg = "Failed to delete product: " . $stmt->error;
        $toastType = "danger";
    }
    $stmt->close();
}

// Handle Toggle Featured Status
if (isset($_POST['toggle_featured'])) {
    $product_id = intval($_POST['product_id']);
    
    $stmt = $conn->prepare("UPDATE products SET is_featured = NOT is_featured WHERE product_id=?");
    $stmt->bind_param("i", $product_id);
    
    if ($stmt->execute()) {
        $toastMsg = "Featured status updated successfully.";
        $toastType = "success";
    } else {
        $toastMsg = "Failed to update featured status.";
        $toastType = "danger";
    }
    $stmt->close();
}
?>
        <!-- Products Section -->
        <section class="products-section">
            
            <?php if (isset($toastMsg)): ?>
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        Swal.fire({
                            icon: '<?php echo $toastType == "success" ? "success" : "error"; ?>',
                            title: '<?php echo $toastType == "success" ? "Success!" : "Error!"; ?>',
                            text: '<?php echo $toastMsg; ?>',
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 3000,
                            timerProgressBar: true
                        });
                    });
                </script>
            <?php endif; ?>
            
            <!-- Page Header with Add Button -->
            <div class="page-header">
                <button class="btn-add-product" id="addProductBtn">
                    <i class='bx bx-plus'></i>
                    <span>Add Product</span>
                </button>
            </div>

            <!-- Stats Cards -->
            <?php
            // Calculate product statistics
            $total_products_query = "SELECT COUNT(*) as total FROM products";
            $total_result = $conn->query($total_products_query);
            $total_products = $total_result->fetch_assoc()['total'];
            
            $in_stock_query = "SELECT COUNT(*) as count FROM products WHERE stock_quantity > low_stock_threshold AND status = 'active'";
            $in_stock_result = $conn->query($in_stock_query);
            $in_stock = $in_stock_result->fetch_assoc()['count'];
            
            $low_stock_query = "SELECT COUNT(*) as count FROM products WHERE stock_quantity > 0 AND stock_quantity <= low_stock_threshold AND status = 'active'";
            $low_stock_result = $conn->query($low_stock_query);
            $low_stock = $low_stock_result->fetch_assoc()['count'];
            
            $out_stock_query = "SELECT COUNT(*) as count FROM products WHERE stock_quantity = 0 AND status = 'active'";
            $out_stock_result = $conn->query($out_stock_query);
            $out_stock = $out_stock_result->fetch_assoc()['count'];
            ?>
            <div class="product-stats-grid">
                <div class="product-stat-card">
                    <div class="stat-info">
                        <span class="stat-label">Total Products</span>
                        <h3 class="stat-number"><?php echo $total_products; ?></h3>
                    </div>
                </div>

                <div class="product-stat-card in-stock">
                    <div class="stat-info">
                        <span class="stat-label">In Stock</span>
                        <h3 class="stat-number"><?php echo $in_stock; ?></h3>
                    </div>
                </div>

                <div class="product-stat-card low-stock">
                    <div class="stat-info">
                        <span class="stat-label">Low Stock</span>
                        <h3 class="stat-number"><?php echo $low_stock; ?></h3>
                    </div>
                </div>

                <div class="product-stat-card out-stock">
                    <div class="stat-info">
                        <span class="stat-label">Out of Stock</span>
                        <h3 class="stat-number"><?php echo $out_stock; ?></h3>
                    </div>
                </div>
            </div>

            <!-- Search and Filter -->
            <div class="search-filter-bar">
                <div class="search-box">
                    <i class='bx bx-search'></i>
                    <input type="text" placeholder="Search products..." id="searchInput">
                </div>
                <button class="btn-filters" id="filtersBtn">
                    <i class='bx bx-filter-alt'></i>
                    <span>Filters</span>
                </button>
            </div>

            <!-- Products Grid -->
            <div class="products-grid">
                <?php
                require_once '../inc/db.php';
                
                // Fetch all products from database
                $query = "SELECT p.*, pi.image_url, c.category_name 
                          FROM products p 
                          LEFT JOIN product_images pi ON p.product_id = pi.product_id AND pi.is_primary = TRUE
                          LEFT JOIN categories c ON p.category_id = c.category_id
                          ORDER BY p.created_at DESC";
                
                $result = $conn->query($query);
                
                if ($result && $result->num_rows > 0) {
                    while($product = $result->fetch_assoc()) {
                        $productId = $product['product_id'];
                        $productName = htmlspecialchars($product['product_name']);
                        $productPrice = number_format($product['price'], 2);
                        
                        // Handle image path - works from admin folder
                        $imageUrl = $product['image_url'] ?? null;
                        if ($imageUrl) {
                            // If path starts with ./ change it to ../
                            if (strpos($imageUrl, './images/') === 0) {
                                $imageUrl = str_replace('./images/', '../images/', $imageUrl);
                            }
                            $productImage = htmlspecialchars($imageUrl);
                        } else {
                            $productImage = 'https://via.placeholder.com/300x350/666/FFFFFF?text=No+Image';
                        }
                        
                        $categoryName = htmlspecialchars($product['category_name']);
                        $stockQty = $product['stock_quantity'];
                        $isTrending = $product['is_featured'] ?? 0; // Using is_featured instead
                        
                        // Determine stock badge
                        $stockBadge = 'in-stock';
                        $stockText = 'In Stock';
                        if ($stockQty == 0) {
                            $stockBadge = 'out-stock';
                            $stockText = 'Out of Stock';
                        } elseif ($stockQty <= $product['low_stock_threshold']) {
                            $stockBadge = 'low-stock';
                            $stockText = 'Low Stock';
                        }
                ?>
                
                <!-- Product Card -->
                <div class="product-card" data-product-id="<?php echo $productId; ?>">
                    <div class="product-image">
                        <img src="<?php echo $productImage; ?>" alt="<?php echo $productName; ?>">
                        <span class="stock-badge <?php echo $stockBadge; ?>"><?php echo $stockText; ?></span>
                        <?php if ($isTrending): ?>
                        <span class="trending-badge">🔥 Trending</span>
                        <?php endif; ?>
                    </div>
                    <div class="product-details">
                        <h3 class="product-name"><?php echo $productName; ?></h3>
                        <p class="product-category"><?php echo $categoryName; ?></p>
                        <div class="product-meta">
                            <div class="meta-item">
                                <span class="meta-label">Price</span>
                                <span class="meta-value">$<?php echo $productPrice; ?></span>
                            </div>
                            <div class="meta-item">
                                <span class="meta-label">Stock</span>
                                <span class="meta-value"><?php echo $stockQty; ?> units</span>
                            </div>
                        </div>
                        <div class="product-actions">
                            <button class="btn-action edit" title="Edit" data-id="<?php echo $productId; ?>">
                                <i class='bx bx-edit'></i>
                            </button>
                            <button class="btn-action trending-toggle <?php echo $isTrending ? 'active' : ''; ?>" 
                                    title="<?php echo $isTrending ? 'Remove from Trending' : 'Mark as Trending'; ?>" 
                                    data-id="<?php echo $productId; ?>"
                                    data-trending="<?php echo $isTrending ? '1' : '0'; ?>">
                                <i class='bx bxs-hot'></i>
                            </button>
                            <button class="btn-action delete" title="Delete" data-id="<?php echo $productId; ?>">
                                <i class='bx bx-trash'></i>
                            </button>
                        </div>
                    </div>
                </div>
                
                <?php
                    }
                } else {
                    echo '<p style="grid-column: 1/-1; text-align: center; padding: 2rem;">No products found. Add your first product!</p>';
                }
                ?>

            </div>

        </section>

    </main>

    <!-- Add Product Modal -->
    <div class="modal-overlay" id="addProductModal">
        <div class="modal-container">
            <div class="modal-header">
                <h2>Add New Product</h2>
                <button class="modal-close" id="closeAddModal">
                    <i class='bx bx-x'></i>
                </button>
            </div>
            <div class="modal-body">
                <form id="addProductForm" method="POST" action="" enctype="multipart/form-data">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="productName">Product Name *</label>
                            <input type="text" id="productName" name="product_name" required placeholder="Enter product name">
                        </div>
                    </div>

                    <div class="form-row two-col">
                        <div class="form-group">
                            <label for="productCategory">Category *</label>
                            <select id="productCategory" name="category_id" required>
                                <option value="">Select category</option>
                                <?php
                                $catQuery = "SELECT category_id, category_name FROM categories WHERE is_active = TRUE ORDER BY category_name";
                                $catResult = $conn->query($catQuery);
                                if ($catResult && $catResult->num_rows > 0) {
                                    while($cat = $catResult->fetch_assoc()) {
                                        echo '<option value="' . $cat['category_id'] . '">' . htmlspecialchars($cat['category_name']) . '</option>';
                                    }
                                }
                                ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="productSKU">SKU *</label>
                            <input type="text" id="productSKU" name="sku" required placeholder="Product SKU">
                        </div>
                    </div>

                    <div class="form-row two-col">
                        <div class="form-group">
                            <label for="productPrice">Price ($) *</label>
                            <input type="number" id="productPrice" name="price" required placeholder="0.00" step="0.01" min="0">
                        </div>
                        <div class="form-group">
                            <label for="productOriginalPrice">Original Price ($)</label>
                            <input type="number" id="productOriginalPrice" name="original_price" placeholder="0.00" step="0.01" min="0">
                        </div>
                    </div>

                    <div class="form-row two-col">
                        <div class="form-group">
                            <label for="productStock">Stock Quantity *</label>
                            <input type="number" id="productStock" name="stock_quantity" required placeholder="0" min="0">
                        </div>
                        <div class="form-group">
                            <label for="productLowStock">Low Stock Threshold</label>
                            <input type="number" id="productLowStock" name="low_stock_threshold" placeholder="10" min="0" value="10">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="productShortDesc">Short Description</label>
                            <textarea id="productShortDesc" name="short_description" rows="2" placeholder="Brief product description (max 500 characters)" maxlength="500"></textarea>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="productDescription">Full Description</label>
                            <textarea id="productDescription" name="description" rows="4" placeholder="Enter detailed product description"></textarea>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="productImage">Product Image *</label>
                            <div class="file-upload">
                                <input type="file" id="productImage" name="productImage" accept="image/*" required>
                                <label for="productImage" class="file-upload-label">
                                    <i class='bx bx-cloud-upload'></i>
                                    <span>Choose image or drag here</span>
                                </label>
                                <div class="image-preview" id="imagePreview"></div>
                            </div>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label>Product Tags</label>
                            <div class="checkbox-group">
                                <label class="checkbox-label">
                                    <input type="checkbox" name="is_featured" value="1">
                                    <span>Featured</span>
                                </label>
                                <label class="checkbox-label">
                                    <input type="checkbox" name="is_new_arrival" value="1">
                                    <span>New Arrival</span>
                                </label>
                                <label class="checkbox-label">
                                    <input type="checkbox" name="is_on_sale" value="1">
                                    <span>On Sale</span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label>Product Variants (Optional)</label>
                            <p style="font-size: 13px; color: #666; margin-bottom: 12px;">Add size and color options for this product. Colors will be displayed as color swatches.</p>
                            
                            <div class="variant-info-box">
                                <i class='bx bx-info-circle'></i>
                                <strong>How variants appear on product page:</strong>
                                Colors show as clickable color swatches (⚫ 🔴 🔵), and sizes display as buttons (XS S M L XL)
                            </div>
                            
                            <div id="variantsContainer">
                                <!-- Variant rows will be added here -->
                            </div>
                            
                            <button type="button" class="btn-add-variant" id="addVariantBtn">
                                <i class='bx bx-plus'></i> Add Variant
                            </button>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label>Status</label>
                            <div class="radio-group">
                                <label class="radio-label">
                                    <input type="radio" name="status" value="active" checked>
                                    <span>Active</span>
                                </label>
                                <label class="radio-label">
                                    <input type="radio" name="status" value="draft">
                                    <span>Draft</span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn-secondary" id="cancelAddBtn">Cancel</button>
                        <button type="submit" name="add_product" class="btn-primary">Add Product</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Product Modal -->
    <div class="modal-overlay" id="editProductModal">
        <div class="modal-container">
            <div class="modal-header">
                <h2>Edit Product</h2>
                <button class="modal-close" id="closeEditModal">
                    <i class='bx bx-x'></i>
                </button>
            </div>
            <div class="modal-body">
                <form id="editProductForm" method="POST" action="" enctype="multipart/form-data">
                    <input type="hidden" id="editProductId" name="product_id">
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="editProductName">Product Name *</label>
                            <input type="text" id="editProductName" name="product_name" required placeholder="Enter product name">
                        </div>
                    </div>

                    <div class="form-row two-col">
                        <div class="form-group">
                            <label for="editProductCategory">Category *</label>
                            <select id="editProductCategory" name="category_id" required>
                                <option value="">Select category</option>
                                <?php
                                $catQuery = "SELECT category_id, category_name FROM categories WHERE is_active = TRUE ORDER BY category_name";
                                $catResult = $conn->query($catQuery);
                                if ($catResult && $catResult->num_rows > 0) {
                                    while($cat = $catResult->fetch_assoc()) {
                                        echo '<option value="' . $cat['category_id'] . '">' . htmlspecialchars($cat['category_name']) . '</option>';
                                    }
                                }
                                ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="editProductSKU">SKU *</label>
                            <input type="text" id="editProductSKU" name="sku" required placeholder="Product SKU">
                        </div>
                    </div>

                    <div class="form-row two-col">
                        <div class="form-group">
                            <label for="editProductPrice">Price ($) *</label>
                            <input type="number" id="editProductPrice" name="price" required placeholder="0.00" step="0.01" min="0">
                        </div>
                        <div class="form-group">
                            <label for="editProductOriginalPrice">Original Price ($)</label>
                            <input type="number" id="editProductOriginalPrice" name="original_price" placeholder="0.00" step="0.01" min="0">
                        </div>
                    </div>

                    <div class="form-row two-col">
                        <div class="form-group">
                            <label for="editProductStock">Stock Quantity *</label>
                            <input type="number" id="editProductStock" name="stock_quantity" required placeholder="0" min="0">
                        </div>
                        <div class="form-group">
                            <label for="editProductLowStock">Low Stock Threshold</label>
                            <input type="number" id="editProductLowStock" name="low_stock_threshold" placeholder="10" min="0">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="editProductShortDesc">Short Description</label>
                            <textarea id="editProductShortDesc" name="short_description" rows="2" placeholder="Brief product description (max 500 characters)" maxlength="500"></textarea>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="editProductDescription">Full Description</label>
                            <textarea id="editProductDescription" name="description" rows="4" placeholder="Enter detailed product description"></textarea>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="editProductImage">Product Image</label>
                            <div class="file-upload">
                                <input type="file" id="editProductImage" name="productImage" accept="image/*">
                                <label for="editProductImage" class="file-upload-label">
                                    <i class='bx bx-cloud-upload'></i>
                                    <span>Choose new image or drag here</span>
                                </label>
                                <div class="image-preview" id="editImagePreview"></div>
                            </div>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label>Product Tags</label>
                            <div class="checkbox-group">
                                <label class="checkbox-label">
                                    <input type="checkbox" id="editIsFeatured" name="is_featured" value="1">
                                    <span>Featured</span>
                                </label>
                                <label class="checkbox-label">
                                    <input type="checkbox" id="editIsNewArrival" name="is_new_arrival" value="1">
                                    <span>New Arrival</span>
                                </label>
                                <label class="checkbox-label">
                                    <input type="checkbox" id="editIsOnSale" name="is_on_sale" value="1">
                                    <span>On Sale</span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label>Product Variants (Optional)</label>
                            <p style="font-size: 13px; color: #666; margin-bottom: 12px;">Manage size and color options. Colors will be displayed as color swatches on product page.</p>
                            
                            <div class="variant-info-box">
                                <i class='bx bx-info-circle'></i>
                                <strong>How variants appear on product page:</strong>
                                Colors show as clickable color swatches (⚫ 🔴 🔵), and sizes display as buttons (XS S M L XL)
                            </div>
                            
                            <div id="editVariantsContainer">
                                <!-- Variant rows will be loaded here -->
                            </div>
                            
                            <button type="button" class="btn-add-variant" id="editAddVariantBtn">
                                <i class='bx bx-plus'></i> Add Variant
                            </button>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label>Status</label>
                            <div class="radio-group">
                                <label class="radio-label">
                                    <input type="radio" id="editStatusActive" name="status" value="active" checked>
                                    <span>Active</span>
                                </label>
                                <label class="radio-label">
                                    <input type="radio" id="editStatusDraft" name="status" value="draft">
                                    <span>Draft</span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn-secondary" id="cancelEditBtn">Cancel</button>
                        <button type="submit" name="edit_product" class="btn-primary">Update Product</button>
                    </div>
                </form>
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

        // Variant counter for unique IDs
        let variantCounter = 0;
        let editVariantCounter = 0;

        // Add Variant Row Function
        function addVariantRow(container, isEdit = false) {
            const counter = isEdit ? editVariantCounter++ : variantCounter++;
            
            const variantRow = document.createElement('div');
            variantRow.className = 'variant-row';
            variantRow.dataset.variantId = counter;
            
            variantRow.innerHTML = `
                <div class="variant-fields">
                    <div class="variant-field">
                        <label>Size</label>
                        <select name="variants[${counter}][size]">
                            <option value="">Select Size</option>
                            <option value="XS">XS</option>
                            <option value="S">S</option>
                            <option value="M">M</option>
                            <option value="L">L</option>
                            <option value="XL">XL</option>
                            <option value="XXL">XXL</option>
                            <option value="One Size">One Size</option>
                        </select>
                    </div>
                    <div class="variant-field">
                        <label>Color Name</label>
                        <input type="text" name="variants[${counter}][color]" placeholder="e.g., Black, Navy" class="color-name-input">
                    </div>
                    <div class="variant-field">
                        <label>Color Code (Hex)</label>
                        <input type="color" name="variants[${counter}][color_hex]" value="#000000" class="color-picker">
                    </div>
                    <div class="variant-field">
                        <label>Stock</label>
                        <input type="number" name="variants[${counter}][stock]" value="0" min="0" placeholder="0">
                    </div>
                    <div class="variant-field">
                        <label>SKU</label>
                        <input type="text" name="variants[${counter}][sku]" placeholder="Optional">
                    </div>
                    <div class="variant-field">
                        <label>Extra Price ($)</label>
                        <input type="number" name="variants[${counter}][additional_price]" value="0" step="0.01" min="0" placeholder="0.00">
                    </div>
                    <div class="variant-field">
                        <button type="button" class="btn-remove-variant" onclick="removeVariant(this)">
                            <i class='bx bx-trash'></i>
                        </button>
                    </div>
                </div>
            `;
            
            container.appendChild(variantRow);
        }

        // Remove Variant Row
        function removeVariant(button) {
            const variantRow = button.closest('.variant-row');
            variantRow.remove();
            
            Toast.fire({
                icon: 'info',
                title: 'Variant removed'
            });
        }

        // Add Variant Button - Add Product
        document.getElementById('addVariantBtn').addEventListener('click', function() {
            const container = document.getElementById('variantsContainer');
            addVariantRow(container, false);
        });

        // Add Variant Button - Edit Product
        document.getElementById('editAddVariantBtn').addEventListener('click', function() {
            const container = document.getElementById('editVariantsContainer');
            addVariantRow(container, true);
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

        // Modal Controls
        const addProductModal = document.getElementById('addProductModal');
        const editProductModal = document.getElementById('editProductModal');
        const addProductBtn = document.getElementById('addProductBtn');
        const closeAddModal = document.getElementById('closeAddModal');
        const closeEditModal = document.getElementById('closeEditModal');
        const cancelAddBtn = document.getElementById('cancelAddBtn');
        const cancelEditBtn = document.getElementById('cancelEditBtn');

        // Open Add Product Modal
        addProductBtn.addEventListener('click', function() {
            addProductModal.classList.add('active');
            document.body.style.overflow = 'hidden';
        });

        // Close Add Product Modal
        function closeAddProductModal() {
            addProductModal.classList.remove('active');
            document.body.style.overflow = 'auto';
            document.getElementById('addProductForm').reset();
            document.getElementById('imagePreview').innerHTML = '';
        }

        closeAddModal.addEventListener('click', closeAddProductModal);
        cancelAddBtn.addEventListener('click', closeAddProductModal);

        addProductModal.addEventListener('click', function(e) {
            if (e.target === addProductModal) {
                closeAddProductModal();
            }
        });

        // Close Edit Product Modal
        function closeEditProductModal() {
            editProductModal.classList.remove('active');
            document.body.style.overflow = 'auto';
            document.getElementById('editProductForm').reset();
            document.getElementById('editImagePreview').innerHTML = '';
            document.getElementById('editVariantsContainer').innerHTML = '';
            editVariantCounter = 0;
        }

        closeEditModal.addEventListener('click', closeEditProductModal);
        cancelEditBtn.addEventListener('click', closeEditProductModal);

        editProductModal.addEventListener('click', function(e) {
            if (e.target === editProductModal) {
                closeEditProductModal();
            }
        });

        // Image Preview for Add Product
        document.getElementById('productImage').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('imagePreview').innerHTML = 
                        `<img src="${e.target.result}" alt="Preview">
                         <button type="button" class="remove-image" onclick="removeAddImage()">
                            <i class='bx bx-x'></i>
                         </button>`;
                    
                    Toast.fire({
                        icon: 'success',
                        title: 'Image uploaded successfully'
                    });
                }
                reader.readAsDataURL(file);
            }
        });

        // Image Preview for Edit Product
        document.getElementById('editProductImage').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('editImagePreview').innerHTML = 
                        `<img src="${e.target.result}" alt="Preview">
                         <button type="button" class="remove-image" onclick="removeEditImage()">
                            <i class='bx bx-x'></i>
                         </button>`;
                    
                    Toast.fire({
                        icon: 'success',
                        title: 'Image uploaded successfully'
                    });
                }
                reader.readAsDataURL(file);
            }
        });

        // Remove Image Functions
        function removeAddImage() {
            document.getElementById('productImage').value = '';
            document.getElementById('imagePreview').innerHTML = '';
            Toast.fire({
                icon: 'info',
                title: 'Image removed'
            });
        }

        function removeEditImage() {
            document.getElementById('editProductImage').value = '';
            document.getElementById('editImagePreview').innerHTML = '';
            Toast.fire({
                icon: 'info',
                title: 'Image removed'
            });
        }

        // Add Product Form Submit
        document.getElementById('addProductForm').addEventListener('submit', function(e) {
            // Allow form to submit normally to PHP handler
            // The form will POST to the server
        });

        // Edit Product Form Submit
        document.getElementById('editProductForm').addEventListener('submit', function(e) {
            // Allow form to submit normally to PHP handler
            // The form will POST to the server
        });

        // Search Functionality
        const searchInput = document.getElementById('searchInput');
        let searchTimeout;
        
        searchInput.addEventListener('input', function(e) {
            clearTimeout(searchTimeout);
            
            searchTimeout = setTimeout(() => {
                const searchTerm = e.target.value.toLowerCase();
                const productCards = document.querySelectorAll('.product-card');
                let foundCount = 0;
                
                productCards.forEach(card => {
                    const productName = card.querySelector('.product-name').textContent.toLowerCase();
                    const productCategory = card.querySelector('.product-category').textContent.toLowerCase();
                    
                    if (productName.includes(searchTerm) || productCategory.includes(searchTerm)) {
                        card.style.display = 'block';
                        foundCount++;
                    } else {
                        card.style.display = 'none';
                    }
                });
                
                if (searchTerm && foundCount === 0) {
                    Toast.fire({
                        icon: 'info',
                        title: 'No products found'
                    });
                }
            }, 500);
        });

        // Filters Button
        document.getElementById('filtersBtn').addEventListener('click', function() {
            Toast.fire({
                icon: 'info',
                title: 'Filter options coming soon'
            });
        });

        // Edit Product Button - Fetch and populate form with existing data
        document.querySelectorAll('.btn-action.edit').forEach(btn => {
            btn.addEventListener('click', function() {
                const productId = this.dataset.id;
                
                // Fetch product data via AJAX
                fetch(`get_product.php?id=${productId}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            const product = data.product;
                            
                            // Populate form fields
                            document.getElementById('editProductId').value = product.product_id;
                            document.getElementById('editProductName').value = product.product_name;
                            document.getElementById('editProductCategory').value = product.category_id;
                            document.getElementById('editProductSKU').value = product.sku || '';
                            document.getElementById('editProductPrice').value = product.price;
                            document.getElementById('editProductOriginalPrice').value = product.original_price || '';
                            document.getElementById('editProductStock').value = product.stock_quantity;
                            document.getElementById('editProductLowStock').value = product.low_stock_threshold || 10;
                            document.getElementById('editProductShortDesc').value = product.short_description || '';
                            document.getElementById('editProductDescription').value = product.description || '';
                            
                            // Set checkboxes
                            document.getElementById('editIsFeatured').checked = product.is_featured == 1;
                            document.getElementById('editIsNewArrival').checked = product.is_new_arrival == 1;
                            document.getElementById('editIsOnSale').checked = product.is_on_sale == 1;
                            
                            // Set status radio
                            if (product.status === 'draft') {
                                document.getElementById('editStatusDraft').checked = true;
                            } else {
                                document.getElementById('editStatusActive').checked = true;
                            }
                            
                            // Show existing image
                            if (product.image_url) {
                                let imgSrc = product.image_url;
                                if (imgSrc.startsWith('./images/')) {
                                    imgSrc = imgSrc.replace('./images/', '../images/');
                                }
                                document.getElementById('editImagePreview').innerHTML = 
                                    `<img src="${imgSrc}" alt="Current Image">`;
                            }
                            
                            // Load existing variants
                            const editVariantsContainer = document.getElementById('editVariantsContainer');
                            editVariantsContainer.innerHTML = '';
                            editVariantCounter = 0;
                            
                            if (product.variants && product.variants.length > 0) {
                                product.variants.forEach(variant => {
                                    const variantRow = document.createElement('div');
                                    variantRow.className = 'variant-row';
                                    variantRow.innerHTML = `
                                        <div class="variant-fields">
                                            <div class="variant-field">
                                                <label>Size</label>
                                                <select name="variants[${editVariantCounter}][size]">
                                                    <option value="">Select Size</option>
                                                    <option value="XS" ${variant.size === 'XS' ? 'selected' : ''}>XS</option>
                                                    <option value="S" ${variant.size === 'S' ? 'selected' : ''}>S</option>
                                                    <option value="M" ${variant.size === 'M' ? 'selected' : ''}>M</option>
                                                    <option value="L" ${variant.size === 'L' ? 'selected' : ''}>L</option>
                                                    <option value="XL" ${variant.size === 'XL' ? 'selected' : ''}>XL</option>
                                                    <option value="XXL" ${variant.size === 'XXL' ? 'selected' : ''}>XXL</option>
                                                    <option value="One Size" ${variant.size === 'One Size' ? 'selected' : ''}>One Size</option>
                                                </select>
                                            </div>
                                            <div class="variant-field">
                                                <label>Color Name</label>
                                                <input type="text" name="variants[${editVariantCounter}][color]" value="${variant.color || ''}" placeholder="e.g., Black, Navy" class="color-name-input">
                                            </div>
                                            <div class="variant-field">
                                                <label>Color Code (Hex)</label>
                                                <input type="color" name="variants[${editVariantCounter}][color_hex]" value="${variant.color_hex || '#000000'}" class="color-picker">
                                            </div>
                                            <div class="variant-field">
                                                <label>Stock</label>
                                                <input type="number" name="variants[${editVariantCounter}][stock]" value="${variant.stock_quantity || 0}" min="0" placeholder="0">
                                            </div>
                                            <div class="variant-field">
                                                <label>SKU</label>
                                                <input type="text" name="variants[${editVariantCounter}][sku]" value="${variant.sku || ''}" placeholder="Optional">
                                            </div>
                                            <div class="variant-field">
                                                <label>Extra Price ($)</label>
                                                <input type="number" name="variants[${editVariantCounter}][additional_price]" value="${variant.additional_price || 0}" step="0.01" min="0" placeholder="0.00">
                                            </div>
                                            <div class="variant-field">
                                                <button type="button" class="btn-remove-variant" onclick="removeVariant(this)">
                                                    <i class='bx bx-trash'></i>
                                                </button>
                                            </div>
                                        </div>
                                    `;
                                    editVariantsContainer.appendChild(variantRow);
                                    editVariantCounter++;
                                });
                            }
                            
                            // Open modal
                            editProductModal.classList.add('active');
                            document.body.style.overflow = 'hidden';
                            
                            Toast.fire({
                                icon: 'info',
                                title: `Editing ${product.product_name}`
                            });
                        } else {
                            Toast.fire({
                                icon: 'error',
                                title: 'Failed to load product data'
                            });
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        Toast.fire({
                            icon: 'error',
                            title: 'An error occurred'
                        });
                    });
            });
        });

        // Duplicate Product
        document.querySelectorAll('.btn-action.duplicate').forEach(btn => {
            btn.addEventListener('click', function() {
                const card = this.closest('.product-card');
                const productName = card.querySelector('.product-name').textContent;
                
                Toast.fire({
                    icon: 'success',
                    title: `${productName} duplicated successfully!`
                });
            });
        });

        // Delete Product
        document.querySelectorAll('.btn-action.delete').forEach(btn => {
            btn.addEventListener('click', function() {
                const productId = this.dataset.id;
                const card = this.closest('.product-card');
                const productName = card.querySelector('.product-name').textContent;
                
                Swal.fire({
                    title: 'Are you sure?',
                    text: `You want to delete "${productName}"?`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#EF4444',
                    cancelButtonColor: '#6B7280',
                    confirmButtonText: 'Yes, delete it!',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Create form and submit
                        const form = document.createElement('form');
                        form.method = 'POST';
                        form.action = '';
                        
                        const input = document.createElement('input');
                        input.type = 'hidden';
                        input.name = 'delete_product';
                        input.value = '1';
                        
                        const idInput = document.createElement('input');
                        idInput.type = 'hidden';
                        idInput.name = 'product_id';
                        idInput.value = productId;
                        
                        form.appendChild(input);
                        form.appendChild(idInput);
                        document.body.appendChild(form);
                        form.submit();
                    }
                });
            });
        });

        // Toggle Trending Status
        document.querySelectorAll('.btn-action.trending-toggle').forEach(btn => {
            btn.addEventListener('click', function() {
                const productId = this.dataset.id;
                const productName = this.closest('.product-card').querySelector('.product-name').textContent;
                const button = this;
                
                fetch('./api/toggle-trending.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `product_id=${productId}`
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        button.classList.toggle('active');
                        button.dataset.trending = data.is_trending;
                        button.title = data.is_trending ? 'Remove from Trending' : 'Mark as Trending';
                        
                        // Update trending badge
                        const card = button.closest('.product-card');
                        const productImage = card.querySelector('.product-image');
                        let trendingBadge = productImage.querySelector('.trending-badge');
                        
                        if (data.is_trending) {
                            if (!trendingBadge) {
                                trendingBadge = document.createElement('span');
                                trendingBadge.className = 'trending-badge';
                                trendingBadge.textContent = '🔥 Trending';
                                productImage.appendChild(trendingBadge);
                            }
                        } else {
                            if (trendingBadge) {
                                trendingBadge.remove();
                            }
                        }
                        
                        Toast.fire({
                            icon: 'success',
                            title: data.message
                        });
                    } else {
                        Toast.fire({
                            icon: 'error',
                            title: data.message || 'Failed to update trending status'
                        });
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    Toast.fire({
                        icon: 'error',
                        title: 'An error occurred'
                    });
                });
            });
        });
    </script>

</body>
</html>
