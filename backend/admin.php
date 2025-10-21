<?php
include('../includes/header.php');
include('../includes/navbar.php');
require_once('../includes/auth.php');
require_once('../includes/functions.php');

// Require admin access
requireAdmin();

// Get counts
$user_count = 0;
$message_count = 0;
$review_count = 0;
$playlist_count = 0;
$saved_song_count = 0;

$result = $conn->query("SELECT COUNT(*) as count FROM users");
if ($result) {
    $user_count = $result->fetch_assoc()['count'];
}

$result = $conn->query("SELECT COUNT(*) as count FROM admin_messages WHERE is_read = FALSE");
if ($result) {
    $message_count = $result->fetch_assoc()['count'];
}

$result = $conn->query("SELECT COUNT(*) as count FROM reviews");
if ($result) {
    $review_count = $result->fetch_assoc()['count'];
}

$result = $conn->query("SELECT COUNT(*) as count FROM playlists");
if ($result) {
    $playlist_count = $result->fetch_assoc()['count'];
}

$result = $conn->query("SELECT COUNT(*) as count FROM saved_songs");
if ($result) {
    $saved_song_count = $result->fetch_assoc()['count'];
}

// Get monthly activity data for charts
$monthly_users = [];
$monthly_reviews = [];
$monthly_playlists = [];

$result = $conn->query("SELECT DATE_FORMAT(created_at, '%Y-%m') as month, COUNT(*) as count FROM users GROUP BY month ORDER BY month ASC LIMIT 6");
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $monthly_users[$row['month']] = $row['count'];
    }
}

$result = $conn->query("SELECT DATE_FORMAT(created_at, '%Y-%m') as month, COUNT(*) as count FROM reviews GROUP BY month ORDER BY month ASC LIMIT 6");
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $monthly_reviews[$row['month']] = $row['count'];
    }
}

$result = $conn->query("SELECT DATE_FORMAT(created_at, '%Y-%m') as month, COUNT(*) as count FROM playlists GROUP BY month ORDER BY month ASC LIMIT 6");
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $monthly_playlists[$row['month']] = $row['count'];
    }
}

// Handle tab selection
$active_tab = $_GET['tab'] ?? 'dashboard';

// Get data for active tab
$users = [];
$messages = [];
$reviews = [];

if ($active_tab == 'users') {
    $result = $conn->query("SELECT * FROM users ORDER BY created_at DESC");
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $users[] = $row;
        }
    }
} elseif ($active_tab == 'messages') {
    $result = $conn->query("SELECT m.*, u.username FROM admin_messages m JOIN users u ON m.user_id = u.id ORDER BY m.is_read ASC, m.priority DESC, m.created_at DESC");
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $messages[] = $row;
        }
    }
} elseif ($active_tab == 'reviews') {
    $result = $conn->query("SELECT r.*, u.username FROM reviews r JOIN users u ON r.user_id = u.id ORDER BY r.is_flagged DESC, r.created_at DESC");
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $reviews[] = $row;
        }
    }
}

// Handle actions
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // CSRF protection
    if (!isset($_POST['csrf_token']) || !verifyCSRFToken($_POST['csrf_token'])) {
$_SESSION['alert'] = ['type' => 'danger', 'message' => 'Invalid security token. Please try again.'];
        header('Location: admin.php?tab=' . $active_tab);
        exit;
    }
    if (isset($_POST['action'])) {
        $action = $_POST['action'];
        
        if ($action == 'delete_user' && isset($_POST['user_id'])) {
            $user_id = (int)$_POST['user_id'];
            $conn->query("DELETE FROM users WHERE id = $user_id");
$_SESSION['alert'] = ['type' => 'success', 'message' => 'User deleted successfully.'];
            header('Location: admin.php?tab=users');
            exit;
        } elseif ($action == 'mark_read' && isset($_POST['message_id'])) {
            $message_id = (int)$_POST['message_id'];
            $conn->query("UPDATE admin_messages SET is_read = TRUE WHERE message_id = $message_id");
$_SESSION['alert'] = ['type' => 'success', 'message' => 'Message marked as read successfully.'];
            header('Location: admin.php?tab=messages');
            exit;
        } elseif ($action == 'delete_message' && isset($_POST['message_id'])) {
            $message_id = (int)$_POST['message_id'];
            $conn->query("DELETE FROM admin_messages WHERE message_id = $message_id");
$_SESSION['alert'] = ['type' => 'success', 'message' => 'Message deleted successfully.'];
            header('Location: admin.php?tab=messages');
            exit;
        } elseif ($action == 'delete_review' && isset($_POST['review_id'])) {
            $review_id = (int)$_POST['review_id'];
            $conn->query("DELETE FROM reviews WHERE review_id = $review_id");
$_SESSION['alert'] = ['type' => 'success', 'message' => 'Review deleted successfully.'];
            header('Location: admin.php?tab=reviews');
            exit;
        } elseif ($action == 'flag_review' && isset($_POST['review_id'])) {
            $review_id = (int)$_POST['review_id'];
            $conn->query("UPDATE reviews SET is_flagged = TRUE WHERE review_id = $review_id");
$_SESSION['alert'] = ['type' => 'success', 'message' => 'Review flagged successfully.'];
            header('Location: admin.php?tab=reviews');
            exit;
        } elseif ($action == 'unflag_review' && isset($_POST['review_id'])) {
            $review_id = (int)$_POST['review_id'];
            $conn->query("UPDATE reviews SET is_flagged = FALSE WHERE review_id = $review_id");
$_SESSION['alert'] = ['type' => 'success', 'message' => 'Review unflagged successfully.'];
            header('Location: admin.php?tab=reviews');
            exit;
        }
    }
}
?>

<div class="admin-container">
    <div class="admin-sidebar">
        <h2>Admin Panel</h2>
        <ul class="admin-nav">
            <li class="<?php echo $active_tab == 'dashboard' ? 'active' : ''; ?>">
                <a href="admin.php?tab=dashboard">Dashboard</a>
            </li>
            <li class="<?php echo $active_tab == 'users' ? 'active' : ''; ?>">
                <a href="admin.php?tab=users">Users</a>
            </li>
            <li class="<?php echo $active_tab == 'messages' ? 'active' : ''; ?>">
                <a href="admin.php?tab=messages">Messages</a>
            </li>
            <li class="<?php echo $active_tab == 'reviews' ? 'active' : ''; ?>">
                <a href="admin.php?tab=reviews">Reviews</a>
            </li>
        </ul>
    </div>
    
    <div class="admin-content">
        <?php if ($active_tab == 'dashboard'): ?>
            <div class="admin-dashboard">
                <h1>Admin Dashboard</h1>
                
                <div class="stat-cards">
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="fas fa-users"></i>
                        </div>
                        <div class="stat-info">
                            <h3>Total Users</h3>
                            <p class="stat-value"><?php echo $user_count; ?></p>
                        </div>
                    </div>
                    
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="fas fa-envelope"></i>
                        </div>
                        <div class="stat-info">
                            <h3>Unread Messages</h3>
                            <p class="stat-value"><?php echo $message_count; ?></p>
                        </div>
                    </div>
                    
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="fas fa-comment"></i>
                        </div>
                        <div class="stat-info">
                            <h3>Total Reviews</h3>
                            <p class="stat-value"><?php echo $review_count; ?></p>
                        </div>
                    </div>
                    
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="fas fa-music"></i>
                        </div>
                        <div class="stat-info">
                            <h3>Saved Songs</h3>
                            <p class="stat-value"><?php echo $saved_song_count; ?></p>
                        </div>
                    </div>
                    
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="fas fa-list"></i>
                        </div>
                        <div class="stat-info">
                            <h3>Playlists</h3>
                            <p class="stat-value"><?php echo $playlist_count; ?></p>
                        </div>
                    </div>
                </div>
                
                <div class="analytics-section">
                    <h2>Site Analytics</h2>
                    <div class="analytics-charts">
                        <div class="chart-container">
                            <h3>Monthly User Growth</h3>
                            <div class="chart">
                                <canvas id="userChart"></canvas>
                            </div>
                        </div>
                        <div class="chart-container">
                            <h3>Content Creation</h3>
                            <div class="chart">
                                <canvas id="contentChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
                
                <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
                <script>
                    // User growth chart
                    const userCtx = document.getElementById('userChart').getContext('2d');
                    const userChart = new Chart(userCtx, {
                        type: 'line',
                        data: {
                            labels: [<?php echo "'" . implode("', '", array_keys($monthly_users)) . "'"; ?>],
                            datasets: [{
                                label: 'New Users',
                                data: [<?php echo implode(", ", array_values($monthly_users)); ?>],
                                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                                borderColor: 'rgba(54, 162, 235, 1)',
                                borderWidth: 2,
                                tension: 0.1
                            }]
                        },
                        options: {
                            scales: {
                                y: {
                                    beginAtZero: true
                                }
                            }
                        }
                    });
                    
                    // Content creation chart
                    const contentCtx = document.getElementById('contentChart').getContext('2d');
                    const contentChart = new Chart(contentCtx, {
                        type: 'bar',
                        data: {
                            labels: [<?php echo "'" . implode("', '", array_keys($monthly_reviews)) . "'"; ?>],
                            datasets: [{
                                label: 'Reviews',
                                data: [<?php echo implode(", ", array_values($monthly_reviews)); ?>],
                                backgroundColor: 'rgba(255, 99, 132, 0.2)',
                                borderColor: 'rgba(255, 99, 132, 1)',
                                borderWidth: 1
                            }, {
                                label: 'Playlists',
                                data: [<?php echo implode(", ", array_values($monthly_playlists)); ?>],
                                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                                borderColor: 'rgba(75, 192, 192, 1)',
                                borderWidth: 1
                            }]
                        },
                        options: {
                            scales: {
                                y: {
                                    beginAtZero: true
                                }
                            }
                        }
                    });
                </script>
                
                <div class="quick-actions">
                    <h2>Quick Actions</h2>
                    <div class="action-buttons">
                        <a href="admin.php?tab=users" class="btn btn-primary">Manage Users</a>
                        <a href="admin.php?tab=messages" class="btn btn-primary">Check Messages</a>
                        <a href="admin.php?tab=reviews" class="btn btn-primary">Moderate Reviews</a>
                    </div>
                </div>
            </div>
            
        <?php elseif ($active_tab == 'users'): ?>
            <div class="admin-users">
                <h1>Manage Users</h1>
                
                <div class="data-table">
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Username</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Joined</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($users as $user): ?>
                                <tr>
                                    <td><?php echo $user['id']; ?></td>
                                    <td><?php echo $user['username']; ?></td>
                                    <td><?php echo $user['email']; ?></td>
                                    <td><?php echo $user['role']; ?></td>
                                    <td><?php echo date('M d, Y', strtotime($user['created_at'])); ?></td>
                                    <td>
                                        <?php if ($user['role'] != 'admin'): ?>
                                            <form method="POST" action="" onsubmit="return confirm('Are you sure you want to delete this user?');">
                                                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                                                <input type="hidden" name="action" value="delete_user">
                                                <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                                                <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                            </form>
                                        <?php else: ?>
                                            <span class="admin-badge">Admin</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            
        <?php elseif ($active_tab == 'messages'): ?>
            <div class="admin-messages">
                <h1>User Messages</h1>
                
                <div class="data-table">
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>User</th>
                                <th>Subject</th>
                                <th>Message</th>
                                <th>Date</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($messages as $message): ?>
                                <tr class="<?php echo $message['is_read'] ? '' : 'unread'; ?>">
                                    <td><?php echo $message['message_id']; ?></td>
                                    <td><?php echo $message['username']; ?></td>
                                    <td><?php echo $message['subject']; ?></td>
                                    <td><?php echo substr($message['message_text'], 0, 50) . (strlen($message['message_text']) > 50 ? '...' : ''); ?></td>
                                    <td><?php echo date('M d, Y', strtotime($message['created_at'])); ?></td>
                                    <td>
                                        <span class="status-badge <?php echo $message['is_read'] ? 'read' : 'unread'; ?>">
                                            <?php echo $message['is_read'] ? 'Read' : 'Unread'; ?>
                                        </span>
                                    </td>
                                    <td>
                                        <?php if (!$message['is_read']): ?>
                                            <form method="POST" action="" style="display: inline;">
                                                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                                                <input type="hidden" name="action" value="mark_read">
                                                <input type="hidden" name="message_id" value="<?php echo $message['message_id']; ?>">
                                                <button type="submit" class="btn btn-primary btn-sm">Mark Read</button>
                                            </form>
                                        <?php endif; ?>
                                        
                                        <form method="POST" action="" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this message?');">
                                            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                                            <input type="hidden" name="action" value="delete_message">
                                            <input type="hidden" name="message_id" value="<?php echo $message['message_id']; ?>">
                                            <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            
        <?php elseif ($active_tab == 'reviews'): ?>
            <div class="admin-reviews">
                <h1>Moderate Reviews</h1>
                
                <div class="data-table">
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>User</th>
                                <th>Song</th>
                                <th>Review</th>
                                <th>Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($reviews as $review): ?>
                                <tr class="<?php echo $review['is_flagged'] ? 'flagged-review' : ''; ?>">
                                    <td><?php echo $review['review_id']; ?></td>
                                    <td><?php echo $review['username']; ?></td>
                                    <td><?php echo $review['song_title'] . ' - ' . $review['artist_name']; ?></td>
                                    <td><?php echo substr($review['review_text'], 0, 50) . (strlen($review['review_text']) > 50 ? '...' : ''); ?></td>
                                    <td><?php echo date('M d, Y', strtotime($review['created_at'])); ?></td>
                                    <td>
                                        <?php if ($review['is_flagged']): ?>
                                            <form method="POST" action="" style="display: inline;">
                                                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                                                <input type="hidden" name="action" value="unflag_review">
                                                <input type="hidden" name="review_id" value="<?php echo $review['review_id']; ?>">
                                                <button type="submit" class="btn btn-success btn-sm">Unflag</button>
                                            </form>
                                        <?php else: ?>
                                            <form method="POST" action="" style="display: inline;">
                                                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                                                <input type="hidden" name="action" value="flag_review">
                                                <input type="hidden" name="review_id" value="<?php echo $review['review_id']; ?>">
                                                <button type="submit" class="btn btn-warning btn-sm">Flag</button>
                                            </form>
                                        <?php endif; ?>
                                        
                                        <form method="POST" action="" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this review?');">
                                            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                                            <input type="hidden" name="action" value="delete_review">
                                            <input type="hidden" name="review_id" value="<?php echo $review['review_id']; ?>">
                                            <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php include('../includes/footer.php'); ?>