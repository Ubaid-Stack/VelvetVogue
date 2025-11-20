<?php include './inc/header.php'; ?>
    </main>
    <!-- Contact page content outside body-con wrapper -->
    <section class="contact-info-con">
        <div class="contact-info-card">
            <h2 class="con-head-text">Contact Information</h2>
            <div class="contact-info-item">
                <div class="contact-icon">
                    <i class='bx bx-envelope'></i>
                </div>
                <div class="contact-details">
                    <h4>Email</h4>
                    <p>support@velvetvogue.com</p>
                </div>
            </div>
            <div class="contact-info-item">
                <div class="contact-icon">
                    <i class='bx bx-phone'></i>
                </div>
                <div class="contact-details">
                    <h4>Phone</h4>
                    <p>+1 (234) 567-890</p>
                </div>
            </div>
            <div class="contact-info-item">
                <div class="contact-icon">
                    <i class='bx bx-map'></i>
                </div>
                <div class="contact-details">
                    <h4>Address</h4>
                    <p>123 Fashion Avenue<br>New York, NY 10001<br>United States</p>
                </div>
            </div>
            <div class="contact-info-item">
                <div class="contact-icon">
                    <i class='bx bx-time'></i>
                </div>
                <div class="contact-details">
                    <h4>Business Hours</h4>
                    <p>Monday - Friday: 9:00 AM - 6:00 PM<br>Saturday: 10:00 AM - 4:00 PM<br>Sunday: Closed</p>
                </div>
            </div>
        </div>
    </section>
    <section class="contact-us-container">
        <h2>Get in Touch</h2>
        <p>If you have any questions, feedback, or need assistance, feel free to reach out to us. We're here to help!</p>
        <form class="contact-form" action="#" method="post">
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" required>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>

            <label for="subject">Subject:</label>
            <input type="text" id="subject" name="subject" required>

            <label for="message">Message:</label>
            <textarea id="message" name="message" rows="5" required></textarea>

            <button type="submit">Send Message</button>
        </form>
    </section>

<?php include './inc/footer.php'; ?>