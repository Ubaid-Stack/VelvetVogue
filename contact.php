<?php
require_once './inc/db.php';

$contactToast = null;
$contactFormData = [
    'name' => '',
    'email' => '',
    'subject' => '',
    'message' => ''
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $contactFormData['name'] = trim($_POST['name'] ?? '');
    $contactFormData['email'] = trim($_POST['email'] ?? '');
    $contactFormData['subject'] = trim($_POST['subject'] ?? '');
    $contactFormData['message'] = trim($_POST['message'] ?? '');

    if (
        $contactFormData['name'] === '' ||
        $contactFormData['email'] === '' ||
        $contactFormData['subject'] === '' ||
        $contactFormData['message'] === ''
    ) {
        $contactToast = [
            'type' => 'error',
            'title' => 'Missing Fields',
            'message' => 'Please fill in all fields before submitting your inquiry.'
        ];
    } elseif (!filter_var($contactFormData['email'], FILTER_VALIDATE_EMAIL)) {
        $contactToast = [
            'type' => 'error',
            'title' => 'Invalid Email',
            'message' => 'Please enter a valid email address.'
        ];
    } else {
        $stmt = $conn->prepare("INSERT INTO contacts (name, email, subject, message) VALUES (?, ?, ?, ?)");

        if ($stmt) {
            $stmt->bind_param(
                "ssss",
                $contactFormData['name'],
                $contactFormData['email'],
                $contactFormData['subject'],
                $contactFormData['message']
            );

            if ($stmt->execute()) {
                $contactToast = [
                    'type' => 'success',
                    'title' => 'Message Sent',
                    'message' => 'Thanks for reaching out. Our team will get back to you soon.'
                ];

                $contactFormData = [
                    'name' => '',
                    'email' => '',
                    'subject' => '',
                    'message' => ''
                ];
            } else {
                $contactToast = [
                    'type' => 'error',
                    'title' => 'Submission Failed',
                    'message' => 'Something went wrong while saving your message. Please try again.'
                ];
            }

            $stmt->close();
        } else {
            $contactToast = [
                'type' => 'error',
                'title' => 'Submission Failed',
                'message' => 'Something went wrong while preparing your request. Please try again.'
            ];
        }
    }
}
?>

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

    <form class="contact-form" action="contact.php" method="post">
        <label for="name">Name:</label>
        <input
            type="text"
            id="name"
            name="name"
            placeholder="Enter your name"
            value="<?php echo htmlspecialchars($contactFormData['name']); ?>"
            required
        >

        <label for="email">Email:</label>
        <input
            type="email"
            id="email"
            name="email"
            placeholder="Enter your email"
            value="<?php echo htmlspecialchars($contactFormData['email']); ?>"
            required
        >

        <label for="subject">Subject:</label>
        <input
            type="text"
            id="subject"
            name="subject"
            placeholder="Enter your subject"
            value="<?php echo htmlspecialchars($contactFormData['subject']); ?>"
            required
        >

        <label for="message">Message:</label>
        <textarea
            id="message"
            name="message"
            rows="5"
            placeholder="Enter your message"
            required
        ><?php echo htmlspecialchars($contactFormData['message']); ?></textarea>

        <button type="submit">Send Message</button>
    </form>
</section>

<?php if ($contactToast): ?>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                icon: <?php echo json_encode($contactToast['type']); ?>,
                title: <?php echo json_encode($contactToast['title']); ?>,
                text: <?php echo json_encode($contactToast['message']); ?>,
                confirmButtonColor: '#3C91E6'
            });
        });
    </script>
<?php endif; ?>

<?php include './inc/footer.php'; ?>