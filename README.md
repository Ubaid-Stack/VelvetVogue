# Velvet Vogue

Velvet Vogue is a PHP + MySQL fashion e-commerce project with two main parts:

- A customer-facing storefront (browse products, cart, checkout, profile, wishlist, reviews)
- An admin dashboard (products, orders, inquiries, system settings, admin management)

This project is built with plain PHP pages and a MySQL database, so it is easy to run locally with XAMPP without extra framework setup.

## Features

### Customer side

- Home page with featured/trending products
- Product listing with filters (category, price, size, color)
- Product detail pages and reviews
- Cart and wishlist
- Checkout flow with address handling
- User authentication and profile/account pages
- Order history pages
- Contact form submission

### Admin side

- Dashboard with store analytics
- Product management (add/edit/delete, variants, featured status)
- Order management (status updates and order details)
- Inquiry management (view, search, mark read/unread, mark replied, delete)
- Admin profile and password update
- Create additional admin users
- Basic system settings page

## Tech Stack

- PHP (session-based app, no framework)
- MySQL / MariaDB
- HTML, CSS, JavaScript
- Chart.js
- SweetAlert2
- Boxicons

## Local Setup (XAMPP)

1. Clone or copy this project into your XAMPP `htdocs` folder.
2. Start Apache and MySQL from XAMPP Control Panel.
3. Create a database named `velvetvogue_db` (or just import the SQL, it creates DB if needed).
4. Import [database/velvetvogue.sql](database/velvetvogue.sql) using phpMyAdmin.
5. Confirm database config in [inc/db.php](inc/db.php):
   - host: `localhost`
   - user: `root`
   - password: empty by default in local XAMPP
   - database: `velvetvogue_db`
6. Open the app in your browser:
   - `http://localhost/VelvetVogue/`

## Default Seed Accounts

Based on the SQL seed file:

- Admin
  - Username: `Admin`
  - Password: `admin123`
- Customer
  - Username: `sophia_martinez`
  - Password: `customer123`

## Project Structure

- [index.php](index.php) - storefront landing page
- [shop.php](shop.php) - product listing/filtering
- [product.php](product.php) - product details
- [cart.php](cart.php), [checkout.php](checkout.php), [place_order.php](place_order.php) - order flow
- [contact.php](contact.php) - customer inquiry form
- [admin/dashboard.php](admin/dashboard.php) - admin overview
- [admin/manageProduct.php](admin/manageProduct.php) - product management
- [admin/manageOrder.php](admin/manageOrder.php) - order management
- [admin/manageInquiry.php](admin/manageInquiry.php) - inquiry management
- [database/velvetvogue.sql](database/velvetvogue.sql) - full schema + seed data
- [assets/admin.css](assets/admin.css), [assets/style.css](assets/style.css) - styling

## Quick Smoke Test

After setup, this is a good quick check:

1. Open storefront and verify products load.
2. Register or log in as customer and submit a message from Contact page.
3. Log in to admin panel at `http://localhost/VelvetVogue/admin/adminLogin.php`.
4. Open Manage Inquiries and confirm the submitted message appears.

## Notes

- This is a page-based PHP application, so logic is spread across route files.
- Most features use server-rendered pages plus small JavaScript enhancements.
- If UI updates do not appear immediately, do a hard refresh (`Ctrl + F5`).

## Future Improvements

- Payment gateway integration
- Email notifications for order and inquiry events
- Better role/permission granularity
- Automated tests and CI checks
- Refactor shared logic into reusable service classes

## License

This repository is currently shared for learning, portfolio, and demonstration purposes.
If you want to use it commercially, please contact the repository owner first.
