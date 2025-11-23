<?php 
$pageTitle = 'Manage Products';
$pageSubtitle = 'Add, edit, and organize your product catalog';
?>
<?php include './inc/head.php'; ?>

<?php include './inc/sidbar.php'; ?>
    
<?php include './inc/topbar.php'; ?>
        <!-- Products Section -->
        <section class="products-section">
            
            <!-- Page Header with Add Button -->
            <div class="page-header">
                <button class="btn-add-product" id="addProductBtn">
                    <i class='bx bx-plus'></i>
                    <span>Add Product</span>
                </button>
            </div>

            <!-- Stats Cards -->
            <div class="product-stats-grid">
                <div class="product-stat-card">
                    <div class="stat-info">
                        <span class="stat-label">Total Products</span>
                        <h3 class="stat-number">8</h3>
                    </div>
                </div>

                <div class="product-stat-card in-stock">
                    <div class="stat-info">
                        <span class="stat-label">In Stock</span>
                        <h3 class="stat-number">5</h3>
                    </div>
                </div>

                <div class="product-stat-card low-stock">
                    <div class="stat-info">
                        <span class="stat-label">Low Stock</span>
                        <h3 class="stat-number">2</h3>
                    </div>
                </div>

                <div class="product-stat-card out-stock">
                    <div class="stat-info">
                        <span class="stat-label">Out of Stock</span>
                        <h3 class="stat-number">1</h3>
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
                
                <!-- Product Card 1 -->
                <div class="product-card">
                    <div class="product-image">
                        <img src="https://via.placeholder.com/300x350/FF6B6B/FFFFFF?text=Silk+Dress" alt="Silk Evening Dress">
                        <span class="stock-badge in-stock">In Stock</span>
                    </div>
                    <div class="product-details">
                        <h3 class="product-name">Silk Evening Dress</h3>
                        <p class="product-category">Dresses</p>
                        <div class="product-meta">
                            <div class="meta-item">
                                <span class="meta-label">Price</span>
                                <span class="meta-value">$299</span>
                            </div>
                            <div class="meta-item">
                                <span class="meta-label">Stock</span>
                                <span class="meta-value">15 units</span>
                            </div>
                        </div>
                        <div class="product-actions">
                            <button class="btn-action edit" title="Edit">
                                <i class='bx bx-edit'></i>
                            </button>
                            <button class="btn-action duplicate" title="Duplicate">
                                <i class='bx bx-copy'></i>
                            </button>
                            <button class="btn-action delete" title="Delete">
                                <i class='bx bx-trash'></i>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Product Card 2 -->
                <div class="product-card">
                    <div class="product-image">
                        <img src="https://via.placeholder.com/300x350/FFA500/FFFFFF?text=Cashmere+Coat" alt="Cashmere Coat">
                        <span class="stock-badge low-stock">Low Stock</span>
                    </div>
                    <div class="product-details">
                        <h3 class="product-name">Cashmere Coat</h3>
                        <p class="product-category">Outerwear</p>
                        <div class="product-meta">
                            <div class="meta-item">
                                <span class="meta-label">Price</span>
                                <span class="meta-value">$450</span>
                            </div>
                            <div class="meta-item">
                                <span class="meta-label">Stock</span>
                                <span class="meta-value">8 units</span>
                            </div>
                        </div>
                        <div class="product-actions">
                            <button class="btn-action edit" title="Edit">
                                <i class='bx bx-edit'></i>
                            </button>
                            <button class="btn-action duplicate" title="Duplicate">
                                <i class='bx bx-copy'></i>
                            </button>
                            <button class="btn-action delete" title="Delete">
                                <i class='bx bx-trash'></i>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Product Card 3 -->
                <div class="product-card">
                    <div class="product-image">
                        <img src="https://via.placeholder.com/300x350/D2691E/FFFFFF?text=Velvet+Blazer" alt="Velvet Blazer">
                        <span class="stock-badge out-stock">Out of Stock</span>
                    </div>
                    <div class="product-details">
                        <h3 class="product-name">Velvet Blazer</h3>
                        <p class="product-category">Jackets</p>
                        <div class="product-meta">
                            <div class="meta-item">
                                <span class="meta-label">Price</span>
                                <span class="meta-value">$320</span>
                            </div>
                            <div class="meta-item">
                                <span class="meta-label">Stock</span>
                                <span class="meta-value">0 units</span>
                            </div>
                        </div>
                        <div class="product-actions">
                            <button class="btn-action edit" title="Edit">
                                <i class='bx bx-edit'></i>
                            </button>
                            <button class="btn-action duplicate" title="Duplicate">
                                <i class='bx bx-copy'></i>
                            </button>
                            <button class="btn-action delete" title="Delete">
                                <i class='bx bx-trash'></i>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Product Card 4 -->
                <div class="product-card">
                    <div class="product-image">
                        <img src="https://via.placeholder.com/300x350/2F4F4F/FFFFFF?text=Satin+Blouse" alt="Satin Blouse">
                        <span class="stock-badge in-stock">In Stock</span>
                    </div>
                    <div class="product-details">
                        <h3 class="product-name">Satin Blouse</h3>
                        <p class="product-category">Tops</p>
                        <div class="product-meta">
                            <div class="meta-item">
                                <span class="meta-label">Price</span>
                                <span class="meta-value">$120</span>
                            </div>
                            <div class="meta-item">
                                <span class="meta-label">Stock</span>
                                <span class="meta-value">25 units</span>
                            </div>
                        </div>
                        <div class="product-actions">
                            <button class="btn-action edit" title="Edit">
                                <i class='bx bx-edit'></i>
                            </button>
                            <button class="btn-action duplicate" title="Duplicate">
                                <i class='bx bx-copy'></i>
                            </button>
                            <button class="btn-action delete" title="Delete">
                                <i class='bx bx-trash'></i>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Product Card 5 -->
                <div class="product-card">
                    <div class="product-image">
                        <img src="https://via.placeholder.com/300x350/DC143C/FFFFFF?text=Leather+Handbag" alt="Leather Handbag">
                        <span class="stock-badge in-stock">In Stock</span>
                    </div>
                    <div class="product-details">
                        <h3 class="product-name">Leather Handbag</h3>
                        <p class="product-category">Accessories</p>
                        <div class="product-meta">
                            <div class="meta-item">
                                <span class="meta-label">Price</span>
                                <span class="meta-value">$280</span>
                            </div>
                            <div class="meta-item">
                                <span class="meta-label">Stock</span>
                                <span class="meta-value">12 units</span>
                            </div>
                        </div>
                        <div class="product-actions">
                            <button class="btn-action edit" title="Edit">
                                <i class='bx bx-edit'></i>
                            </button>
                            <button class="btn-action duplicate" title="Duplicate">
                                <i class='bx bx-copy'></i>
                            </button>
                            <button class="btn-action delete" title="Delete">
                                <i class='bx bx-trash'></i>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Product Card 6 -->
                <div class="product-card">
                    <div class="product-image">
                        <img src="https://via.placeholder.com/300x350/8B4513/FFFFFF?text=Wool+Trousers" alt="Wool Trousers">
                        <span class="stock-badge low-stock">Low Stock</span>
                    </div>
                    <div class="product-details">
                        <h3 class="product-name">Wool Trousers</h3>
                        <p class="product-category">Bottoms</p>
                        <div class="product-meta">
                            <div class="meta-item">
                                <span class="meta-label">Price</span>
                                <span class="meta-value">$180</span>
                            </div>
                            <div class="meta-item">
                                <span class="meta-label">Stock</span>
                                <span class="meta-value">5 units</span>
                            </div>
                        </div>
                        <div class="product-actions">
                            <button class="btn-action edit" title="Edit">
                                <i class='bx bx-edit'></i>
                            </button>
                            <button class="btn-action duplicate" title="Duplicate">
                                <i class='bx bx-copy'></i>
                            </button>
                            <button class="btn-action delete" title="Delete">
                                <i class='bx bx-trash'></i>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Product Card 7 -->
                <div class="product-card">
                    <div class="product-image">
                        <img src="https://via.placeholder.com/300x350/000000/FFFFFF?text=Pearl+Necklace" alt="Pearl Necklace">
                        <span class="stock-badge in-stock">In Stock</span>
                    </div>
                    <div class="product-details">
                        <h3 class="product-name">Pearl Necklace</h3>
                        <p class="product-category">Jewelry</p>
                        <div class="product-meta">
                            <div class="meta-item">
                                <span class="meta-label">Price</span>
                                <span class="meta-value">$540</span>
                            </div>
                            <div class="meta-item">
                                <span class="meta-label">Stock</span>
                                <span class="meta-value">18 units</span>
                            </div>
                        </div>
                        <div class="product-actions">
                            <button class="btn-action edit" title="Edit">
                                <i class='bx bx-edit'></i>
                            </button>
                            <button class="btn-action duplicate" title="Duplicate">
                                <i class='bx bx-copy'></i>
                            </button>
                            <button class="btn-action delete" title="Delete">
                                <i class='bx bx-trash'></i>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Product Card 8 -->
                <div class="product-card">
                    <div class="product-image">
                        <img src="https://via.placeholder.com/300x350/696969/FFFFFF?text=Designer+Sunglasses" alt="Designer Sunglasses">
                        <span class="stock-badge in-stock">In Stock</span>
                    </div>
                    <div class="product-details">
                        <h3 class="product-name">Designer Sunglasses</h3>
                        <p class="product-category">Accessories</p>
                        <div class="product-meta">
                            <div class="meta-item">
                                <span class="meta-label">Price</span>
                                <span class="meta-value">$240</span>
                            </div>
                            <div class="meta-item">
                                <span class="meta-label">Stock</span>
                                <span class="meta-value">30 units</span>
                            </div>
                        </div>
                        <div class="product-actions">
                            <button class="btn-action edit" title="Edit">
                                <i class='bx bx-edit'></i>
                            </button>
                            <button class="btn-action duplicate" title="Duplicate">
                                <i class='bx bx-copy'></i>
                            </button>
                            <button class="btn-action delete" title="Delete">
                                <i class='bx bx-trash'></i>
                            </button>
                        </div>
                    </div>
                </div>

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
                <form id="addProductForm">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="productName">Product Name *</label>
                            <input type="text" id="productName" name="productName" required placeholder="Enter product name">
                        </div>
                    </div>

                    <div class="form-row two-col">
                        <div class="form-group">
                            <label for="productCategory">Category *</label>
                            <select id="productCategory" name="productCategory" required>
                                <option value="">Select category</option>
                                <option value="dresses">Dresses</option>
                                <option value="outerwear">Outerwear</option>
                                <option value="tops">Tops</option>
                                <option value="bottoms">Bottoms</option>
                                <option value="accessories">Accessories</option>
                                <option value="jewelry">Jewelry</option>
                                <option value="jackets">Jackets</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="productPrice">Price ($) *</label>
                            <input type="number" id="productPrice" name="productPrice" required placeholder="0.00" step="0.01" min="0">
                        </div>
                    </div>

                    <div class="form-row two-col">
                        <div class="form-group">
                            <label for="productStock">Stock Quantity *</label>
                            <input type="number" id="productStock" name="productStock" required placeholder="0" min="0">
                        </div>
                        <div class="form-group">
                            <label for="productSKU">SKU</label>
                            <input type="text" id="productSKU" name="productSKU" placeholder="Product SKU">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="productDescription">Description</label>
                            <textarea id="productDescription" name="productDescription" rows="4" placeholder="Enter product description"></textarea>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="productImage">Product Image</label>
                            <div class="file-upload">
                                <input type="file" id="productImage" name="productImage" accept="image/*">
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
                            <label>Status</label>
                            <div class="radio-group">
                                <label class="radio-label">
                                    <input type="radio" name="productStatus" value="active" checked>
                                    <span>Active</span>
                                </label>
                                <label class="radio-label">
                                    <input type="radio" name="productStatus" value="draft">
                                    <span>Draft</span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn-secondary" id="cancelAddBtn">Cancel</button>
                        <button type="submit" class="btn-primary">Add Product</button>
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
                <form id="editProductForm">
                    <input type="hidden" id="editProductId" name="productId">
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="editProductName">Product Name *</label>
                            <input type="text" id="editProductName" name="productName" required placeholder="Enter product name">
                        </div>
                    </div>

                    <div class="form-row two-col">
                        <div class="form-group">
                            <label for="editProductCategory">Category *</label>
                            <select id="editProductCategory" name="productCategory" required>
                                <option value="">Select category</option>
                                <option value="dresses">Dresses</option>
                                <option value="outerwear">Outerwear</option>
                                <option value="tops">Tops</option>
                                <option value="bottoms">Bottoms</option>
                                <option value="accessories">Accessories</option>
                                <option value="jewelry">Jewelry</option>
                                <option value="jackets">Jackets</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="editProductPrice">Price ($) *</label>
                            <input type="number" id="editProductPrice" name="productPrice" required placeholder="0.00" step="0.01" min="0">
                        </div>
                    </div>

                    <div class="form-row two-col">
                        <div class="form-group">
                            <label for="editProductStock">Stock Quantity *</label>
                            <input type="number" id="editProductStock" name="productStock" required placeholder="0" min="0">
                        </div>
                        <div class="form-group">
                            <label for="editProductSKU">SKU</label>
                            <input type="text" id="editProductSKU" name="productSKU" placeholder="Product SKU">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="editProductDescription">Description</label>
                            <textarea id="editProductDescription" name="productDescription" rows="4" placeholder="Enter product description"></textarea>
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
                            <label>Status</label>
                            <div class="radio-group">
                                <label class="radio-label">
                                    <input type="radio" name="editProductStatus" value="active" checked>
                                    <span>Active</span>
                                </label>
                                <label class="radio-label">
                                    <input type="radio" name="editProductStatus" value="draft">
                                    <span>Draft</span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn-secondary" id="cancelEditBtn">Cancel</button>
                        <button type="submit" class="btn-primary">Update Product</button>
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
            e.preventDefault();
            
            // Get form data
            const formData = new FormData(this);
            const productName = formData.get('productName');
            
            // Here you would normally send the data to your backend
            console.log('Adding product:', Object.fromEntries(formData));
            
            // Show success toast
            Toast.fire({
                icon: 'success',
                title: `${productName} added successfully!`
            });
            
            closeAddProductModal();
        });

        // Edit Product Form Submit
        document.getElementById('editProductForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Get form data
            const formData = new FormData(this);
            const productName = formData.get('productName');
            
            // Here you would normally send the data to your backend
            console.log('Updating product:', Object.fromEntries(formData));
            
            // Show success toast
            Toast.fire({
                icon: 'success',
                title: `${productName} updated successfully!`
            });
            
            closeEditProductModal();
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

        // Edit Product Button - Populate form with existing data
        document.querySelectorAll('.btn-action.edit').forEach(btn => {
            btn.addEventListener('click', function() {
                const card = this.closest('.product-card');
                const productName = card.querySelector('.product-name').textContent;
                const productCategory = card.querySelector('.product-category').textContent.toLowerCase();
                const productPrice = card.querySelector('.meta-value').textContent.replace('$', '');
                const productStock = card.querySelectorAll('.meta-value')[1].textContent.replace(' units', '');
                const productImage = card.querySelector('.product-image img').src;
                
                // Populate edit form
                document.getElementById('editProductName').value = productName;
                document.getElementById('editProductCategory').value = productCategory;
                document.getElementById('editProductPrice').value = productPrice;
                document.getElementById('editProductStock').value = productStock;
                
                // Show existing image
                if (productImage) {
                    document.getElementById('editImagePreview').innerHTML = 
                        `<img src="${productImage}" alt="Current Image">`;
                }
                
                // Open modal
                editProductModal.classList.add('active');
                document.body.style.overflow = 'hidden';
                
                Toast.fire({
                    icon: 'info',
                    title: `Editing ${productName}`
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
                        card.remove();
                        
                        Toast.fire({
                            icon: 'success',
                            title: `${productName} deleted successfully!`
                        });
                    }
                });
            });
        });
    </script>

</body>
</html>
