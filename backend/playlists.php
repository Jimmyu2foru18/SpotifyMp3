<?php
include('../includes/header.php');
include('../includes/navbar.php');
require_once('../includes/auth.php');
require_once('../includes/functions.php');

// Require login for this page
requireLogin();

$user_id = $_SESSION['user_id'];
$playlists = getUserPlaylists($user_id);

// Handle playlist creation
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'create') {
    $name = $_POST['name'] ?? '';
    $description = $_POST['description'] ?? '';
    
    if (!empty($name)) {
        $playlist_id = createPlaylist($user_id, $name, $description);
        if ($playlist_id) {
            header('Location: playlists.php');
            exit;
        }
    }
}

// Handle viewing a specific playlist
$current_playlist = null;
$playlist_songs = [];
if (isset($_GET['id'])) {
    $playlist_id = (int)$_GET['id'];
    
    // Get playlist info
    foreach ($playlists as $playlist) {
        if ($playlist['id'] == $playlist_id) {
            $current_playlist = $playlist;
            break;
        }
    }
    
    // Get playlist songs
    if ($current_playlist) {
        $playlist_songs = getPlaylistSongs($playlist_id);
    }
}
?>

<div class="container">
    <div class="playlists-page">
        <h1>Your Playlists</h1>
        
        <?php if ($current_playlist): ?>
            <!-- Single Playlist View -->
            <div class="playlist-header">
                <a href="playlists.php" class="btn btn-sm"><i class="fas fa-arrow-left"></i> Back to Playlists</a>
                <h2><?php echo $current_playlist['name']; ?></h2>
                <p><?php echo $current_playlist['desc']; ?></p>
            </div>
            
            <?php if (empty($playlist_songs)): ?>
                <p class="no-songs">This playlist is empty. Add songs from the homepage or search page.</p>
            <?php else: ?>
                <div class="song-list">
                    <?php foreach ($playlist_songs as $song): ?>
                        <div class="song-item" data-id="<?php echo $song['id']; ?>" data-platform="<?php echo $song['platform']; ?>" data-external-id="<?php echo $song['song_id']; ?>">
                            <div class="song-thumb">
                                <img src="<?php echo $song['thumb']; ?>" alt="<?php echo $song['title']; ?>">
                                <button class="play-btn" onclick="playSong(<?php echo $song['id']; ?>, '<?php echo $song['platform']; ?>', '<?php echo $song['song_id']; ?>', '<?php echo htmlspecialchars(addslashes($song['title'])); ?>', '<?php echo htmlspecialchars(addslashes($song['artist'])); ?>', '<?php echo $song['thumb']; ?>')">
                                    <i class="fas fa-play"></i>
                                </button>
                            </div>
                            <div class="song-info">
                                <h3><?php echo $song['title']; ?></h3>
                                <p><?php echo $song['artist']; ?></p>
                            </div>
                            <div class="song-actions">
                                <button class="btn btn-sm remove-song" data-playlist="<?php echo $current_playlist['id']; ?>" data-song="<?php echo $song['id']; ?>">
                                    <i class="fas fa-times"></i> Remove
                                </button>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
            
        <?php else: ?>
            <!-- Playlists Overview -->
            <div class="playlists-grid">
                <?php if (empty($playlists)): ?>
                    <p class="no-playlists">You don't have any playlists yet. Create your first playlist below.</p>
                <?php else: ?>
                    <?php foreach ($playlists as $playlist): ?>
                        <div class="playlist-card">
                            <h3><?php echo $playlist['name']; ?></h3>
                            <p><?php echo $playlist['desc']; ?></p>
                            <a href="playlists.php?id=<?php echo $playlist['id']; ?>" class="btn btn-primary">View Playlist</a>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
            
            <div class="create-playlist">
                <h2>Create New Playlist</h2>
                <form method="POST" action="">
                    <input type="hidden" name="action" value="create">
                    <div class="form-group">
                        <label for="name">Playlist Name</label>
                        <input type="text" id="name" name="name" required>
                    </div>
                    <div class="form-group">
                        <label for="description">Description (optional)</label>
                        <textarea id="description" name="description"></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Create Playlist</button>
                </form>
            </div>
        <?php endif; ?>
    </div>
</div>

<script>
// Remove song from playlist
document.querySelectorAll('.remove-song').forEach(button => {
    button.addEventListener('click', function() {
        const playlistId = this.dataset.playlist;
        const songId = this.dataset.song;
        
        fetch('../backend/api/remove_from_playlist.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `playlist_id=${playlistId}&song_id=${songId}`
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Remove song from UI
                this.closest('.song-item').remove();
                showNotification('Song removed from playlist');
            } else {
                showNotification('Error: ' + data.message, 'error');
            }
        })
        .catch(error => {
            showNotification('Error removing song', 'error');
            console.error('Error:', error);
        });
    });
});

// Helper function to show notifications
function showNotification(message, type = 'success') {
    const notification = document.createElement('div');
    notification.className = `notification ${type}`;
    notification.textContent = message;
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.classList.add('show');
    }, 10);
    
    setTimeout(() => {
        notification.classList.remove('show');
        setTimeout(() => {
            notification.remove();
        }, 300);
    }, 3000);
}
</script>

<?php include('../includes/footer.php'); ?>