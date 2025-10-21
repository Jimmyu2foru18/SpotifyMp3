<?php
include('../includes/header.php');
include('../includes/navbar.php');
require_once('../includes/auth.php');
require_once('../includes/functions.php');

// Require login for this page
requireLogin();

$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'];

// Get user info
$user = [];
$sql = "SELECT * FROM users WHERE id = $user_id";
$result = $conn->query($sql);
if ($result && $result->num_rows > 0) {
    $user = $result->fetch_assoc();
}

// Handle profile update
$success_message = '';
$error_message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'update_profile') {
    $bio = $_POST['bio'] ?? '';
    
    // Update bio
    $bio = $conn->real_escape_string($bio);
    $update_sql = "UPDATE users SET bio = '$bio' WHERE id = $user_id";
    
    if ($conn->query($update_sql)) {
        $success_message = 'Profile updated successfully';
        $user['bio'] = $bio;
    } else {
        $error_message = 'Error updating profile';
    }
    
    // Handle image upload
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];
        $filename = $_FILES['image']['name'];
        $ext = pathinfo($filename, PATHINFO_EXTENSION);
        
        if (in_array(strtolower($ext), $allowed)) {
            $new_filename = 'user_' . $user_id . '.' . $ext;
            $upload_path = '../assets/images/profiles/' . $new_filename;
            
            // Create directory if it doesn't exist
            if (!file_exists('../assets/images/profiles/')) {
                mkdir('../assets/images/profiles/', 0777, true);
            }
            
            if (move_uploaded_file($_FILES['image']['tmp_name'], $upload_path)) {
                $image_path = 'assets/images/profiles/' . $new_filename;
                $update_img_sql = "UPDATE users SET image = '$image_path' WHERE id = $user_id";
                
                if ($conn->query($update_img_sql)) {
                    $success_message = 'Profile updated successfully';
                    $user['image'] = $image_path;
                } else {
                    $error_message = 'Error updating profile image';
                }
            } else {
                $error_message = 'Error uploading image';
            }
        } else {
            $error_message = 'Invalid file type. Only JPG, JPEG, PNG and GIF are allowed.';
        }
    }
}

// Get user playlists
$playlists = getUserPlaylists($user_id);

// Get user reviews
$reviews = getUserReviews($user_id);
?>

<div class="container">
    <div class="profile-page">
        <h1>Your Profile</h1>
        
        <?php if (!empty($success_message)): ?>
            <div class="alert alert-success"><?php echo $success_message; ?></div>
        <?php endif; ?>
        
        <?php if (!empty($error_message)): ?>
            <div class="alert alert-danger"><?php echo $error_message; ?></div>
        <?php endif; ?>
        
        <div class="profile-content">
            <div class="profile-sidebar">
                <div class="profile-image">
                    <?php if (!empty($user['image'])): ?>
                        <img src="../<?php echo $user['image']; ?>" alt="<?php echo $username; ?>">
                    <?php else: ?>
                        <img src="../assets/images/default_profile.png" alt="<?php echo $username; ?>">
                    <?php endif; ?>
                </div>
                
                <div class="profile-info">
                    <h2><?php echo $username; ?></h2>
                    <p class="join-date">Member since: <?php echo date('F Y', strtotime($user['created_at'] ?? 'now')); ?></p>
                    
                    <div class="profile-stats">
                        <div class="stat">
                            <span class="stat-value"><?php echo count($playlists); ?></span>
                            <span class="stat-label">Playlists</span>
                        </div>
                        <div class="stat">
                            <span class="stat-value"><?php echo count($reviews); ?></span>
                            <span class="stat-label">Reviews</span>
                        </div>
                    </div>
                </div>
                
                <div class="profile-edit">
                    <h3>Edit Profile</h3>
                    <form method="POST" action="" enctype="multipart/form-data">
                        <input type="hidden" name="action" value="update_profile">
                        
                        <div class="form-group">
                            <label for="bio">Bio</label>
                            <textarea id="bio" name="bio"><?php echo $user['bio'] ?? ''; ?></textarea>
                        </div>
                        
                        <div class="form-group">
                            <label for="image">Profile Image</label>
                            <input type="file" id="image" name="image">
                        </div>
                        
                        <button type="submit" class="btn btn-primary">Update Profile</button>
                    </form>
                </div>
            </div>
            
            <div class="profile-main">
                <div class="profile-section">
                    <h3>Your Playlists</h3>
                    
                    <?php if (empty($playlists)): ?>
                        <p class="no-items">You haven't created any playlists yet.</p>
                        <a href="playlists.php" class="btn btn-primary">Create Playlist</a>
                    <?php else: ?>
                        <div class="playlists-grid">
                            <?php foreach (array_slice($playlists, 0, 4) as $playlist): ?>
                                <div class="playlist-card">
                                    <h4><?php echo $playlist['name']; ?></h4>
                                    <p><?php echo $playlist['desc']; ?></p>
                                    <a href="playlists.php?id=<?php echo $playlist['id']; ?>" class="btn btn-sm">View Playlist</a>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        
                        <?php if (count($playlists) > 4): ?>
                            <a href="playlists.php" class="view-all">View All Playlists</a>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
                
                <div class="profile-section">
                    <h3>Your Reviews</h3>
                    
                    <?php if (empty($reviews)): ?>
                        <p class="no-items">You haven't written any reviews yet.</p>
                    <?php else: ?>
                        <div class="reviews-list">
                            <?php foreach (array_slice($reviews, 0, 5) as $review): ?>
                                <div class="review-item">
                                    <div class="review-song">
                                        <img src="<?php echo $review['thumb']; ?>" alt="<?php echo $review['title']; ?>">
                                        <div>
                                            <h4><?php echo $review['title']; ?></h4>
                                            <p><?php echo $review['artist']; ?></p>
                                        </div>
                                    </div>
                                    <div class="review-text">
                                        <p><?php echo $review['text']; ?></p>
                                        <span class="review-date"><?php echo date('M d, Y', strtotime($review['created_at'])); ?></span>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        
                        <?php if (count($reviews) > 5): ?>
                            <a href="reviews.php" class="view-all">View All Reviews</a>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include('../includes/footer.php'); ?>