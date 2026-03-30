<?php
session_start();
require_once '../inc/db.php';

if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'admin') {
    header('Location: adminLogin.php');
    exit();
}

$pageTitle = 'Manage Inquiries';
$pageSubtitle = 'Review, track, and follow up on customer messages';

function fetchCount($conn, $sql)
{
    $result = $conn->query($sql);
    if ($result && $row = $result->fetch_assoc()) {
        return (int) $row['total'];
    }

    return 0;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['inquiry_action'], $_POST['contact_id'])) {
    $contactId = intval($_POST['contact_id']);
    $action = $_POST['inquiry_action'];
    $toast = [
        'type' => 'danger',
        'message' => 'Unable to process this request.'
    ];

    if ($contactId > 0) {
        if ($action === 'mark_read' || $action === 'mark_unread') {
            $isRead = $action === 'mark_read' ? 1 : 0;
            $stmt = $conn->prepare('UPDATE contacts SET is_read = ? WHERE contact_id = ?');

            if ($stmt) {
                $stmt->bind_param('ii', $isRead, $contactId);
                if ($stmt->execute()) {
                    $toast['type'] = 'success';
                    $toast['message'] = $isRead ? 'Inquiry marked as read.' : 'Inquiry marked as unread.';
                }
                $stmt->close();
            }
        } elseif ($action === 'toggle_replied') {
            $stmt = $conn->prepare('UPDATE contacts SET is_replied = NOT is_replied WHERE contact_id = ?');

            if ($stmt) {
                $stmt->bind_param('i', $contactId);
                if ($stmt->execute()) {
                    $toast['type'] = 'success';
                    $toast['message'] = 'Reply status updated successfully.';
                }
                $stmt->close();
            }
        } elseif ($action === 'delete') {
            $stmt = $conn->prepare('DELETE FROM contacts WHERE contact_id = ?');

            if ($stmt) {
                $stmt->bind_param('i', $contactId);
                if ($stmt->execute()) {
                    $toast['type'] = 'success';
                    $toast['message'] = 'Inquiry deleted successfully.';
                }
                $stmt->close();
            }
        }
    }

    $_SESSION['inquiry_toast'] = $toast;
    header('Location: manageInquiry.php');
    exit();
}

$toast = null;
if (isset($_SESSION['inquiry_toast'])) {
    $toast = $_SESSION['inquiry_toast'];
    unset($_SESSION['inquiry_toast']);
}

$allowedStatuses = ['all', 'unread', 'read', 'replied'];
$status = isset($_GET['status']) ? strtolower(trim($_GET['status'])) : 'all';
$search = trim($_GET['search'] ?? '');

if (!in_array($status, $allowedStatuses, true)) {
    $status = 'all';
}

$whereConditions = [];
$params = [];
$types = '';

if ($status === 'unread') {
    $whereConditions[] = 'c.is_read = 0';
} elseif ($status === 'read') {
    $whereConditions[] = 'c.is_read = 1';
} elseif ($status === 'replied') {
    $whereConditions[] = 'c.is_replied = 1';
}

if ($search !== '') {
    $searchLike = '%' . $search . '%';
    $whereConditions[] = '(c.name LIKE ? OR c.email LIKE ? OR c.subject LIKE ? OR c.message LIKE ?)';
    $params = [$searchLike, $searchLike, $searchLike, $searchLike];
    $types .= 'ssss';
}

$whereSql = '';
if (!empty($whereConditions)) {
    $whereSql = 'WHERE ' . implode(' AND ', $whereConditions);
}

$inquiries = [];
$inquiryPayload = [];

$listSql = "SELECT c.contact_id, c.name, c.email, c.subject, c.message, c.is_read, c.is_replied, c.submitted_at
            FROM contacts c
            $whereSql
            ORDER BY c.submitted_at DESC";
$listStmt = $conn->prepare($listSql);

if ($listStmt) {
    if ($types !== '') {
        $listStmt->bind_param('ssss', $params[0], $params[1], $params[2], $params[3]);
    }

    $listStmt->execute();
    $result = $listStmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $inquiries[] = $row;
        $inquiryPayload[] = [
            'contact_id' => (int) $row['contact_id'],
            'name' => $row['name'],
            'email' => $row['email'],
            'subject' => $row['subject'],
            'message' => $row['message'],
            'submitted_at' => $row['submitted_at'],
            'is_read' => (int) $row['is_read'],
            'is_replied' => (int) $row['is_replied']
        ];
    }

    $listStmt->close();
}

$totalInquiries = fetchCount($conn, 'SELECT COUNT(*) AS total FROM contacts');
$unreadInquiries = fetchCount($conn, 'SELECT COUNT(*) AS total FROM contacts WHERE is_read = 0');
$repliedInquiries = fetchCount($conn, 'SELECT COUNT(*) AS total FROM contacts WHERE is_replied = 1');
$todayInquiries = fetchCount($conn, 'SELECT COUNT(*) AS total FROM contacts WHERE DATE(submitted_at) = CURDATE()');

$tabSearchQuery = $search !== '' ? '&search=' . urlencode($search) : '';
$filteredCount = count($inquiries);
?>
<?php include './inc/head.php'; ?>

<?php include './inc/sidbar.php'; ?>

<?php include './inc/topbar.php'; ?>

<section class="inquiry-section">

    <?php if ($toast): ?>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: <?php echo json_encode($toast['type'] === 'success' ? 'success' : 'error'); ?>,
                    title: <?php echo json_encode($toast['type'] === 'success' ? 'Success!' : 'Error!'); ?>,
                    text: <?php echo json_encode($toast['message']); ?>,
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true
                });
            });
        </script>
    <?php endif; ?>

    <div class="order-stats-grid inquiry-stats-grid">
        <div class="order-stat-card total">
            <span class="stat-label">Total Inquiries</span>
            <h3 class="stat-number"><?php echo $totalInquiries; ?></h3>
        </div>

        <div class="order-stat-card unread">
            <span class="stat-label">Unread</span>
            <h3 class="stat-number"><?php echo $unreadInquiries; ?></h3>
        </div>

        <div class="order-stat-card replied">
            <span class="stat-label">Replied</span>
            <h3 class="stat-number"><?php echo $repliedInquiries; ?></h3>
        </div>

        <div class="order-stat-card today">
            <span class="stat-label">Today</span>
            <h3 class="stat-number"><?php echo $todayInquiries; ?></h3>
        </div>
    </div>

    <div class="order-search-bar">
        <form action="manageInquiry.php" method="get" class="inquiry-search-form">
            <input type="hidden" name="status" value="<?php echo htmlspecialchars($status); ?>">
            <div class="search-box">
                <i class='bx bx-search'></i>
                <input
                    type="text"
                    name="search"
                    placeholder="Search by name, email, subject, or message..."
                    value="<?php echo htmlspecialchars($search); ?>"
                >
            </div>
            <button class="btn-filters" type="submit">
                <i class='bx bx-search-alt'></i>
                <span>Search</span>
            </button>
        </form>
    </div>

    <div class="inquiry-filter-tabs">
        <a href="manageInquiry.php?status=all<?php echo $tabSearchQuery; ?>" class="inquiry-filter-link <?php echo $status === 'all' ? 'active' : ''; ?>">
            All (<?php echo $totalInquiries; ?>)
        </a>
        <a href="manageInquiry.php?status=unread<?php echo $tabSearchQuery; ?>" class="inquiry-filter-link <?php echo $status === 'unread' ? 'active' : ''; ?>">
            Unread (<?php echo $unreadInquiries; ?>)
        </a>
        <a href="manageInquiry.php?status=read<?php echo $tabSearchQuery; ?>" class="inquiry-filter-link <?php echo $status === 'read' ? 'active' : ''; ?>">
            Read
        </a>
        <a href="manageInquiry.php?status=replied<?php echo $tabSearchQuery; ?>" class="inquiry-filter-link <?php echo $status === 'replied' ? 'active' : ''; ?>">
            Replied (<?php echo $repliedInquiries; ?>)
        </a>
    </div>

    <div class="table-card inquiry-table-card">
        <div class="table-header">
            <h3>Customer Inquiries</h3>
            <p class="inquiry-subtext">Showing <?php echo $filteredCount; ?> inquiry<?php echo $filteredCount === 1 ? '' : 'ies'; ?></p>
        </div>
        <div class="table-container">
            <table class="data-table inquiry-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Customer</th>
                        <th>Subject</th>
                        <th>Message</th>
                        <th>Status</th>
                        <th>Received</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($inquiries)): ?>
                        <tr>
                            <td colspan="7" style="text-align: center; padding: 30px; color: #6B7280;">No inquiries found.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($inquiries as $inquiry): ?>
                            <?php
                            $messageFlat = preg_replace('/\s+/', ' ', trim($inquiry['message']));
                            $messagePreview = strlen($messageFlat) > 95 ? substr($messageFlat, 0, 95) . '...' : $messageFlat;
                            $submittedAt = date('M d, Y h:i A', strtotime($inquiry['submitted_at']));
                            $isRead = (int) $inquiry['is_read'] === 1;
                            $isReplied = (int) $inquiry['is_replied'] === 1;
                            ?>
                            <tr class="<?php echo $isRead ? '' : 'inquiry-unread-row'; ?>">
                                <td>#<?php echo (int) $inquiry['contact_id']; ?></td>
                                <td>
                                    <div class="inquiry-customer">
                                        <strong><?php echo htmlspecialchars($inquiry['name']); ?></strong>
                                        <span><?php echo htmlspecialchars($inquiry['email']); ?></span>
                                    </div>
                                </td>
                                <td><?php echo htmlspecialchars($inquiry['subject']); ?></td>
                                <td>
                                    <span class="inquiry-message-preview"><?php echo htmlspecialchars($messagePreview); ?></span>
                                </td>
                                <td>
                                    <div class="inquiry-status-wrap">
                                        <span class="status-chip <?php echo $isRead ? 'read' : 'unread'; ?>"><?php echo $isRead ? 'Read' : 'Unread'; ?></span>
                                        <span class="status-chip <?php echo $isReplied ? 'replied' : 'not-replied'; ?>"><?php echo $isReplied ? 'Replied' : 'Not Replied'; ?></span>
                                    </div>
                                </td>
                                <td><?php echo htmlspecialchars($submittedAt); ?></td>
                                <td>
                                    <div class="inquiry-actions">
                                        <button
                                            type="button"
                                            class="btn-action edit inquiry-view-btn"
                                            title="View Details"
                                            data-id="<?php echo (int) $inquiry['contact_id']; ?>"
                                        >
                                            <i class='bx bx-show'></i>
                                        </button>

                                        <form method="post" class="inline-action-form">
                                            <input type="hidden" name="contact_id" value="<?php echo (int) $inquiry['contact_id']; ?>">
                                            <input type="hidden" name="inquiry_action" value="<?php echo $isRead ? 'mark_unread' : 'mark_read'; ?>">
                                            <button type="submit" class="btn-action <?php echo $isRead ? 'duplicate' : 'edit'; ?>" title="<?php echo $isRead ? 'Mark as Unread' : 'Mark as Read'; ?>">
                                                <i class='bx <?php echo $isRead ? 'bx-envelope' : 'bx-envelope-open'; ?>'></i>
                                            </button>
                                        </form>

                                        <form method="post" class="inline-action-form">
                                            <input type="hidden" name="contact_id" value="<?php echo (int) $inquiry['contact_id']; ?>">
                                            <input type="hidden" name="inquiry_action" value="toggle_replied">
                                            <button type="submit" class="btn-action <?php echo $isReplied ? 'duplicate' : 'edit'; ?>" title="<?php echo $isReplied ? 'Mark as Not Replied' : 'Mark as Replied'; ?>">
                                                <i class='bx <?php echo $isReplied ? 'bx-undo' : 'bx-reply'; ?>'></i>
                                            </button>
                                        </form>

                                        <form method="post" class="inline-action-form" onsubmit="return confirm('Delete this inquiry permanently?');">
                                            <input type="hidden" name="contact_id" value="<?php echo (int) $inquiry['contact_id']; ?>">
                                            <input type="hidden" name="inquiry_action" value="delete">
                                            <button type="submit" class="btn-action delete" title="Delete Inquiry">
                                                <i class='bx bx-trash'></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

</section>

</main>

<div class="modal-overlay" id="inquiryModal">
    <div class="modal-container inquiry-modal-container">
        <div class="modal-header">
            <h2>Inquiry Details</h2>
            <button class="modal-close" type="button" onclick="closeInquiryModal()">
                <i class='bx bx-x'></i>
            </button>
        </div>
        <div class="modal-body">
            <div class="detail-grid inquiry-detail-grid">
                <div class="detail-item">
                    <span class="detail-label">Name</span>
                    <span class="detail-value" id="modalInquiryName">-</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Email</span>
                    <span class="detail-value" id="modalInquiryEmail">-</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Subject</span>
                    <span class="detail-value" id="modalInquirySubject">-</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Received At</span>
                    <span class="detail-value" id="modalInquiryDate">-</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Read Status</span>
                    <span class="status-chip unread" id="modalInquiryRead">Unread</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Reply Status</span>
                    <span class="status-chip not-replied" id="modalInquiryReplied">Not Replied</span>
                </div>
            </div>

            <div class="order-detail-section inquiry-message-section">
                <h3 class="detail-section-title">Message</h3>
                <div class="inquiry-message-box" id="modalInquiryMessage"></div>
            </div>
        </div>
    </div>
</div>

<script>
    const inquiryData = <?php echo json_encode($inquiryPayload, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT); ?>;
    const inquiryMap = {};
    inquiryData.forEach(function(item) {
        inquiryMap[String(item.contact_id)] = item;
    });

    const mobileMenuBtn = document.getElementById('mobileMenuBtn');
    const adminSidebar = document.getElementById('adminSidebar');
    const sidebarOverlay = document.getElementById('sidebarOverlay');
    const inquiryModal = document.getElementById('inquiryModal');

    if (mobileMenuBtn && adminSidebar && sidebarOverlay) {
        mobileMenuBtn.addEventListener('click', function() {
            adminSidebar.classList.toggle('active');
            sidebarOverlay.classList.toggle('active');
        });

        sidebarOverlay.addEventListener('click', function() {
            adminSidebar.classList.remove('active');
            sidebarOverlay.classList.remove('active');
        });
    }

    document.querySelectorAll('.inquiry-view-btn').forEach(function(button) {
        button.addEventListener('click', function() {
            openInquiryModal(this.getAttribute('data-id'));
        });
    });

    function formatSubmittedDate(dateText) {
        if (!dateText) {
            return '-';
        }

        const parsed = new Date(String(dateText).replace(' ', 'T'));
        if (Number.isNaN(parsed.getTime())) {
            return dateText;
        }

        return parsed.toLocaleString([], {
            year: 'numeric',
            month: 'short',
            day: '2-digit',
            hour: '2-digit',
            minute: '2-digit'
        });
    }

    function openInquiryModal(contactId) {
        const inquiry = inquiryMap[String(contactId)];

        if (!inquiry || !inquiryModal) {
            return;
        }

        document.getElementById('modalInquiryName').textContent = inquiry.name || '-';
        document.getElementById('modalInquiryEmail').textContent = inquiry.email || '-';
        document.getElementById('modalInquirySubject').textContent = inquiry.subject || '-';
        document.getElementById('modalInquiryDate').textContent = formatSubmittedDate(inquiry.submitted_at);
        document.getElementById('modalInquiryMessage').textContent = inquiry.message || '-';

        const readBadge = document.getElementById('modalInquiryRead');
        const isRead = Number(inquiry.is_read) === 1;
        readBadge.textContent = isRead ? 'Read' : 'Unread';
        readBadge.className = 'status-chip ' + (isRead ? 'read' : 'unread');

        const repliedBadge = document.getElementById('modalInquiryReplied');
        const isReplied = Number(inquiry.is_replied) === 1;
        repliedBadge.textContent = isReplied ? 'Replied' : 'Not Replied';
        repliedBadge.className = 'status-chip ' + (isReplied ? 'replied' : 'not-replied');

        inquiryModal.classList.add('active');
    }

    function closeInquiryModal() {
        if (inquiryModal) {
            inquiryModal.classList.remove('active');
        }
    }

    if (inquiryModal) {
        inquiryModal.addEventListener('click', function(event) {
            if (event.target === inquiryModal) {
                closeInquiryModal();
            }
        });
    }

    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            closeInquiryModal();
        }
    });
</script>
</body>
</html>
