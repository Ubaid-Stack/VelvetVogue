-- =============================================
-- VelvetVogue E-Commerce Database
-- Database: velvetvogue_db
-- =============================================

-- Create Database
CREATE DATABASE IF NOT EXISTS velvetvogue_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE velvetvogue_db;

-- =============================================
-- Table: users
-- Description: Stores user account information
-- =============================================
CREATE TABLE IF NOT EXISTS users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    phone VARCHAR(20) NOT NULL,
    password VARCHAR(255) NOT NULL,
    full_name VARCHAR(100),
    profile_image VARCHAR(255) DEFAULT NULL,
    user_type ENUM('customer', 'admin') DEFAULT 'customer',
    is_active BOOLEAN DEFAULT TRUE,
    email_verified BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    last_login TIMESTAMP NULL DEFAULT NULL,
    INDEX idx_email (email),
    INDEX idx_username (username),
    INDEX idx_user_type (user_type)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =============================================
-- Table: categories
-- Description: Product categories
-- =============================================
CREATE TABLE IF NOT EXISTS categories (
    category_id INT AUTO_INCREMENT PRIMARY KEY,
    category_name VARCHAR(100) NOT NULL UNIQUE,
    category_slug VARCHAR(100) NOT NULL UNIQUE,
    description TEXT,
    parent_id INT DEFAULT NULL,
    image_url VARCHAR(255) DEFAULT NULL,
    is_active BOOLEAN DEFAULT TRUE,
    display_order INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (parent_id) REFERENCES categories(category_id) ON DELETE SET NULL,
    INDEX idx_category_slug (category_slug),
    INDEX idx_parent (parent_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =============================================
-- Table: products
-- Description: Product catalog
-- =============================================
CREATE TABLE IF NOT EXISTS products (
    product_id INT AUTO_INCREMENT PRIMARY KEY,
    product_name VARCHAR(200) NOT NULL,
    product_slug VARCHAR(200) NOT NULL UNIQUE,
    sku VARCHAR(50) UNIQUE,
    category_id INT NOT NULL,
    description TEXT,
    short_description VARCHAR(500),
    price DECIMAL(10, 2) NOT NULL,
    original_price DECIMAL(10, 2),
    discount_percentage INT DEFAULT 0,
    stock_quantity INT DEFAULT 0,
    low_stock_threshold INT DEFAULT 10,
    is_featured BOOLEAN DEFAULT FALSE,
    is_new_arrival BOOLEAN DEFAULT FALSE,
    is_on_sale BOOLEAN DEFAULT FALSE,
    status ENUM('active', 'draft', 'out_of_stock') DEFAULT 'active',
    rating DECIMAL(3, 2) DEFAULT 0.00,
    review_count INT DEFAULT 0,
    views_count INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(category_id) ON DELETE CASCADE,
    INDEX idx_product_slug (product_slug),
    INDEX idx_category (category_id),
    INDEX idx_status (status),
    INDEX idx_featured (is_featured),
    INDEX idx_price (price),
    INDEX idx_sku (sku)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =============================================
-- Table: product_images
-- Description: Product image gallery
-- =============================================
CREATE TABLE IF NOT EXISTS product_images (
    image_id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT NOT NULL,
    image_url VARCHAR(255) NOT NULL,
    is_primary BOOLEAN DEFAULT FALSE,
    display_order INT DEFAULT 0,
    alt_text VARCHAR(200),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (product_id) REFERENCES products(product_id) ON DELETE CASCADE,
    INDEX idx_product (product_id),
    INDEX idx_primary (is_primary)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =============================================
-- Table: product_variants
-- Description: Product size, color variations
-- =============================================
CREATE TABLE IF NOT EXISTS product_variants (
    variant_id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT NOT NULL,
    size VARCHAR(20),
    color VARCHAR(50),
    additional_price DECIMAL(10, 2) DEFAULT 0.00,
    stock_quantity INT DEFAULT 0,
    sku VARCHAR(50) UNIQUE,
    is_available BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (product_id) REFERENCES products(product_id) ON DELETE CASCADE,
    INDEX idx_product (product_id),
    INDEX idx_sku (sku)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =============================================
-- Table: addresses
-- Description: User shipping addresses
-- =============================================
CREATE TABLE IF NOT EXISTS addresses (
    address_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    full_name VARCHAR(100) NOT NULL,
    phone VARCHAR(20) NOT NULL,
    address_line1 VARCHAR(200) NOT NULL,
    address_line2 VARCHAR(200),
    city VARCHAR(100) NOT NULL,
    state VARCHAR(100) NOT NULL,
    zip_code VARCHAR(20) NOT NULL,
    country VARCHAR(100) NOT NULL DEFAULT 'United States',
    is_default BOOLEAN DEFAULT FALSE,
    address_type ENUM('home', 'work', 'other') DEFAULT 'home',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE,
    INDEX idx_user (user_id),
    INDEX idx_default (is_default)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =============================================
-- Table: cart
-- Description: Shopping cart items
-- =============================================
CREATE TABLE IF NOT EXISTS cart (
    cart_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    product_id INT NOT NULL,
    variant_id INT DEFAULT NULL,
    quantity INT NOT NULL DEFAULT 1,
    added_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(product_id) ON DELETE CASCADE,
    FOREIGN KEY (variant_id) REFERENCES product_variants(variant_id) ON DELETE CASCADE,
    INDEX idx_user (user_id),
    INDEX idx_product (product_id),
    UNIQUE KEY unique_cart_item (user_id, product_id, variant_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =============================================
-- Table: wishlist
-- Description: User wishlist items
-- =============================================
CREATE TABLE IF NOT EXISTS wishlist (
    wishlist_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    product_id INT NOT NULL,
    added_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(product_id) ON DELETE CASCADE,
    INDEX idx_user (user_id),
    INDEX idx_product (product_id),
    UNIQUE KEY unique_wishlist_item (user_id, product_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =============================================
-- Table: orders
-- Description: Customer orders
-- =============================================
CREATE TABLE IF NOT EXISTS orders (
    order_id INT AUTO_INCREMENT PRIMARY KEY,
    order_number VARCHAR(50) NOT NULL UNIQUE,
    user_id INT NOT NULL,
    address_id INT NOT NULL,
    order_status ENUM('pending', 'processing', 'shipped', 'delivered', 'cancelled', 'refunded') DEFAULT 'pending',
    payment_status ENUM('pending', 'paid', 'failed', 'refunded') DEFAULT 'pending',
    payment_method ENUM('credit_card', 'debit_card', 'paypal', 'cash_on_delivery') NOT NULL,
    subtotal DECIMAL(10, 2) NOT NULL,
    shipping_cost DECIMAL(10, 2) DEFAULT 0.00,
    tax_amount DECIMAL(10, 2) DEFAULT 0.00,
    discount_amount DECIMAL(10, 2) DEFAULT 0.00,
    total_amount DECIMAL(10, 2) NOT NULL,
    shipping_method VARCHAR(100),
    tracking_number VARCHAR(100),
    notes TEXT,
    order_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    shipped_date TIMESTAMP NULL DEFAULT NULL,
    delivered_date TIMESTAMP NULL DEFAULT NULL,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE,
    FOREIGN KEY (address_id) REFERENCES addresses(address_id) ON DELETE RESTRICT,
    INDEX idx_user (user_id),
    INDEX idx_order_number (order_number),
    INDEX idx_order_status (order_status),
    INDEX idx_order_date (order_date)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =============================================
-- Table: order_items
-- Description: Items in each order
-- =============================================
CREATE TABLE IF NOT EXISTS order_items (
    order_item_id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    product_id INT NOT NULL,
    variant_id INT DEFAULT NULL,
    product_name VARCHAR(200) NOT NULL,
    product_sku VARCHAR(50),
    size VARCHAR(20),
    color VARCHAR(50),
    quantity INT NOT NULL,
    unit_price DECIMAL(10, 2) NOT NULL,
    subtotal DECIMAL(10, 2) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (order_id) REFERENCES orders(order_id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(product_id) ON DELETE RESTRICT,
    FOREIGN KEY (variant_id) REFERENCES product_variants(variant_id) ON DELETE RESTRICT,
    INDEX idx_order (order_id),
    INDEX idx_product (product_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =============================================
-- Table: reviews
-- Description: Product reviews and ratings
-- =============================================
CREATE TABLE IF NOT EXISTS reviews (
    review_id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT NOT NULL,
    user_id INT NOT NULL,
    order_id INT DEFAULT NULL,
    rating INT NOT NULL CHECK (rating >= 1 AND rating <= 5),
    review_title VARCHAR(200),
    review_text TEXT,
    is_verified_purchase BOOLEAN DEFAULT FALSE,
    is_approved BOOLEAN DEFAULT FALSE,
    helpful_count INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (product_id) REFERENCES products(product_id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE,
    FOREIGN KEY (order_id) REFERENCES orders(order_id) ON DELETE SET NULL,
    INDEX idx_product (product_id),
    INDEX idx_user (user_id),
    INDEX idx_rating (rating),
    INDEX idx_approved (is_approved)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =============================================
-- Table: newsletters
-- Description: Newsletter subscribers
-- =============================================
CREATE TABLE IF NOT EXISTS newsletters (
    newsletter_id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(100) NOT NULL UNIQUE,
    is_subscribed BOOLEAN DEFAULT TRUE,
    subscribed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    unsubscribed_at TIMESTAMP NULL DEFAULT NULL,
    INDEX idx_email (email),
    INDEX idx_subscribed (is_subscribed)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =============================================
-- Table: contacts
-- Description: Contact form submissions
-- =============================================
CREATE TABLE IF NOT EXISTS contacts (
    contact_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    subject VARCHAR(200) NOT NULL,
    message TEXT NOT NULL,
    is_read BOOLEAN DEFAULT FALSE,
    is_replied BOOLEAN DEFAULT FALSE,
    submitted_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_email (email),
    INDEX idx_read (is_read),
    INDEX idx_submitted (submitted_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =============================================
-- Table: coupons
-- Description: Discount coupons
-- =============================================
CREATE TABLE IF NOT EXISTS coupons (
    coupon_id INT AUTO_INCREMENT PRIMARY KEY,
    coupon_code VARCHAR(50) NOT NULL UNIQUE,
    description VARCHAR(200),
    discount_type ENUM('percentage', 'fixed_amount') NOT NULL,
    discount_value DECIMAL(10, 2) NOT NULL,
    minimum_order_amount DECIMAL(10, 2) DEFAULT 0.00,
    max_discount_amount DECIMAL(10, 2) DEFAULT NULL,
    usage_limit INT DEFAULT NULL,
    used_count INT DEFAULT 0,
    is_active BOOLEAN DEFAULT TRUE,
    start_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    end_date TIMESTAMP NULL DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_coupon_code (coupon_code),
    INDEX idx_active (is_active)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =============================================
-- Table: coupon_usage
-- Description: Track coupon usage by users
-- =============================================
CREATE TABLE IF NOT EXISTS coupon_usage (
    usage_id INT AUTO_INCREMENT PRIMARY KEY,
    coupon_id INT NOT NULL,
    user_id INT NOT NULL,
    order_id INT NOT NULL,
    used_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (coupon_id) REFERENCES coupons(coupon_id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE,
    FOREIGN KEY (order_id) REFERENCES orders(order_id) ON DELETE CASCADE,
    INDEX idx_coupon (coupon_id),
    INDEX idx_user (user_id),
    INDEX idx_order (order_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =============================================
-- Table: site_settings
-- Description: System configuration
-- =============================================
CREATE TABLE IF NOT EXISTS site_settings (
    setting_id INT AUTO_INCREMENT PRIMARY KEY,
    setting_key VARCHAR(100) NOT NULL UNIQUE,
    setting_value TEXT,
    setting_type VARCHAR(50) DEFAULT 'text',
    description TEXT,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_setting_key (setting_key)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =============================================
-- Insert Sample Data
-- =============================================

-- Insert Admin User (Password: admin123 - bcrypt hashed)
INSERT INTO users (username, email, phone, password, full_name, user_type) VALUES
('admin', 'admin@velvetvogue.com', '+1234567890', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Admin User', 'admin');

-- Insert Sample Customer (Password: customer123)
INSERT INTO users (username, email, phone, password, full_name) VALUES
('sophia_martinez', 'sophia.martinez@example.com', '+15551234567', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Sophia Martinez');

-- Insert Categories
INSERT INTO categories (category_name, category_slug, description, display_order) VALUES
('Dresses', 'dresses', 'Elegant dresses for all occasions', 1),
('Outerwear', 'outerwear', 'Coats, jackets, and more', 2),
('Tops', 'tops', 'Stylish tops and blouses', 3),
('Bottoms', 'bottoms', 'Pants, skirts, and shorts', 4),
('Accessories', 'accessories', 'Bags, jewelry, and accessories', 5),
('Men''s Wear', 'mens-wear', 'Men''s fashion collection', 6),
('Women''s Wear', 'womens-wear', 'Women''s fashion collection', 7),
('Formal Wear', 'formal-wear', 'Formal attire for special occasions', 8),
('Casual Wear', 'casual-wear', 'Comfortable everyday clothing', 9);

-- Insert Sample Products
INSERT INTO products (product_name, product_slug, sku, category_id, description, short_description, price, original_price, discount_percentage, stock_quantity, is_featured, is_new_arrival, is_on_sale, status) VALUES
('Velvet Evening Dress', 'velvet-evening-dress', 'VV-ED-1001', 1, 'A stunning velvet evening dress designed to make a statement. This elegant piece features a fitted silhouette, V-neckline, and a subtle slit for movement.', 'Elegant velvet dress for special occasions', 129.99, 162.49, 20, 15, TRUE, TRUE, TRUE, 'active'),
('Classic Denim Jacket', 'classic-denim-jacket', 'VV-DJ-2001', 2, 'Timeless denim jacket perfect for layering', 'Classic denim jacket', 89.99, 120.00, 25, 25, TRUE, FALSE, TRUE, 'active'),
('Silk Summer Blouse', 'silk-summer-blouse', 'VV-SB-3001', 3, 'Luxurious silk blouse for warm weather', 'Elegant silk blouse', 65.00, NULL, 0, 18, FALSE, TRUE, FALSE, 'active'),
('Tailored Formal Blazer', 'tailored-formal-blazer', 'VV-FB-4001', 2, 'Professional blazer for business occasions', 'Formal business blazer', 145.00, NULL, 0, 12, TRUE, FALSE, FALSE, 'active'),
('Designer Leather Handbag', 'designer-leather-handbag', 'VV-LH-5001', 5, 'Premium leather handbag with gold hardware', 'Luxury leather handbag', 280.00, NULL, 0, 8, TRUE, TRUE, FALSE, 'active');

-- Insert Product Images
INSERT INTO product_images (product_id, image_url, is_primary, display_order, alt_text) VALUES
(1, './images/product1.jpg', TRUE, 1, 'Velvet Evening Dress - Front View'),
(1, './images/product2.jpg', FALSE, 2, 'Velvet Evening Dress - Back View'),
(2, './images/product2.jpg', TRUE, 1, 'Classic Denim Jacket'),
(3, './images/product3.jpg', TRUE, 1, 'Silk Summer Blouse'),
(4, './images/product4.jpg', TRUE, 1, 'Tailored Formal Blazer'),
(5, './images/product1.jpg', TRUE, 1, 'Designer Leather Handbag');

-- Insert Product Variants (Sizes and Colors)
INSERT INTO product_variants (product_id, size, color, stock_quantity, sku) VALUES
(1, 'XS', 'Black', 3, 'VV-ED-1001-XS-BLK'),
(1, 'S', 'Black', 4, 'VV-ED-1001-S-BLK'),
(1, 'M', 'Black', 5, 'VV-ED-1001-M-BLK'),
(1, 'L', 'Black', 2, 'VV-ED-1001-L-BLK'),
(1, 'XL', 'Black', 1, 'VV-ED-1001-XL-BLK'),
(1, 'M', 'Maroon', 3, 'VV-ED-1001-M-MAR'),
(1, 'M', 'Navy', 2, 'VV-ED-1001-M-NAV'),
(2, 'S', 'Blue', 8, 'VV-DJ-2001-S-BLU'),
(2, 'M', 'Blue', 10, 'VV-DJ-2001-M-BLU'),
(2, 'L', 'Blue', 7, 'VV-DJ-2001-L-BLU');

-- Insert Sample Address
INSERT INTO addresses (user_id, full_name, phone, address_line1, address_line2, city, state, zip_code, is_default) VALUES
(2, 'Sophia Martinez', '+15551234567', '123 Fashion Avenue', 'Apt 4B', 'New York', 'NY', '10001', TRUE);

-- Insert Sample Newsletter Subscribers
INSERT INTO newsletters (email) VALUES
('customer1@example.com'),
('customer2@example.com'),
('customer3@example.com');

-- Insert Site Settings
INSERT INTO site_settings (setting_key, setting_value, setting_type, description) VALUES
('site_name', 'Velvet Vogue', 'text', 'Website name'),
('site_email', 'support@velvetvogue.com', 'email', 'Contact email'),
('site_phone', '+1 (234) 567-890', 'text', 'Contact phone'),
('shipping_cost', '5.99', 'decimal', 'Standard shipping cost'),
('tax_rate', '8', 'integer', 'Tax rate percentage'),
('currency', 'USD', 'text', 'Store currency'),
('free_shipping_threshold', '100', 'decimal', 'Minimum order for free shipping');

-- =============================================
-- Create Views for Common Queries
-- =============================================

-- View: Active Products with Category
CREATE OR REPLACE VIEW v_active_products AS
SELECT 
    p.product_id,
    p.product_name,
    p.product_slug,
    p.sku,
    p.price,
    p.original_price,
    p.discount_percentage,
    p.stock_quantity,
    p.rating,
    p.review_count,
    c.category_name,
    c.category_slug,
    pi.image_url as primary_image
FROM products p
JOIN categories c ON p.category_id = c.category_id
LEFT JOIN product_images pi ON p.product_id = pi.product_id AND pi.is_primary = TRUE
WHERE p.status = 'active';

-- View: Order Summary
CREATE OR REPLACE VIEW v_order_summary AS
SELECT 
    o.order_id,
    o.order_number,
    o.order_date,
    o.order_status,
    o.payment_status,
    o.total_amount,
    u.username,
    u.email,
    COUNT(oi.order_item_id) as item_count
FROM orders o
JOIN users u ON o.user_id = u.user_id
LEFT JOIN order_items oi ON o.order_id = oi.order_id
GROUP BY o.order_id;

-- =============================================
-- END OF DATABASE SCHEMA
-- =============================================
