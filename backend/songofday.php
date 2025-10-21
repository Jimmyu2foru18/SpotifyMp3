<?php
require_once '../includes/db_connect.php';
require_once '../includes/functions.php';
require_once '../includes/auth.php';

// Get the current song of the day
function getSongOfDay() {
    global $conn;
    
    // Get the current date in Y-m-d format
    $today = date('Y-m-d');
    
    // Check if we already have a song for today
    $stmt = $conn->prepare("SELECT * FROM song_of_day WHERE date = ?");
    $stmt->bind_param("s", $today);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        // Return the existing song of the day
        return $result->fetch_assoc();
    } else {
        // No song for today, create a new one
        return createNewSongOfDay();
    }
}

// Create a new song of the day
function createNewSongOfDay() {
    global $conn;
    
    // Get a random song from the saved_songs table
    $result = $conn->query("SELECT * FROM saved_songs ORDER BY RAND() LIMIT 1");
    
    if ($result->num_rows > 0) {
        $song = $result->fetch_assoc();
    } else {
        // If no songs in the database, use a default song
        $song = [
            'song_id' => '0VjIjW4GlUZAMYd2vXMi3b',
            'title' => 'Blinding Lights',
            'artist' => 'The Weeknd',
            'album' => 'After Hours',
            'image_url' => 'https://i.scdn.co/image/ab67616d0000b273af52c228c9619ff6298b08cd',
            'platform' => 'spotify',
            'preview_url' => 'https://p.scdn.co/mp3-preview/5ee8d7a3a3b32a9fc5c733a0d0a2c4fc0f816b6c'
        ];
    }
    
    // Insert the new song of the day
    $today = date('Y-m-d');
    $stmt = $conn->prepare("INSERT INTO song_of_day (song_id, title, artist, album, image_url, platform, preview_url, date) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssss", 
        $song['song_id'], 
        $song['title'], 
        $song['artist'], 
        $song['album'], 
        $song['image_url'], 
        $song['platform'],
        $song['preview_url'],
        $today
    );
    $stmt->execute();
    
    // Return the newly created song of the day
    return [
        'song_id' => $song['song_id'],
        'title' => $song['title'],
        'artist' => $song['artist'],
        'album' => $song['album'],
        'image_url' => $song['image_url'],
        'platform' => $song['platform'],
        'preview_url' => $song['preview_url'],
        'date' => $today
    ];
}

// Get previous songs of the day (last 7 days)
function getPreviousSongsOfDay() {
    global $conn;
    
    $today = date('Y-m-d');
    $stmt = $conn->prepare("SELECT * FROM song_of_day WHERE date < ? ORDER BY date DESC LIMIT 7");
    $stmt->bind_param("s", $today);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $songs = [];
    while ($row = $result->fetch_assoc()) {
        $songs[] = $row;
    }
    
    return $songs;
}

// Handle AJAX requests
if (isset($_GET['action'])) {
    header('Content-Type: application/json');
    
    if ($_GET['action'] == 'get_current') {
        echo json_encode(getSongOfDay());
        exit;
    } elseif ($_GET['action'] == 'get_previous') {
        echo json_encode(getPreviousSongsOfDay());
        exit;
    }
}

// Include header
include_once '../includes/header.php';
?>

<div class="container">
    <h1>Song of the Day</h1>
    
    <div class="song-of-day-container">
        <div class="current-song-of-day">
            <h2>Today's Featured Song</h2>
            <?php $song = getSongOfDay(); ?>
            <div class="featured-song">
                <img src="<?php echo htmlspecialchars($song['image_url']); ?>" alt="<?php echo htmlspecialchars($song['title']); ?>" class="featured-song-image">
                <div class="featured-song-info">
                    <h3><?php echo htmlspecialchars($song['title']); ?></h3>
                    <p class="artist"><?php echo htmlspecialchars($song['artist']); ?></p>
                    <p class="album"><?php echo htmlspecialchars($song['album']); ?></p>
                    <div class="song-actions">
                        <button class="action-btn play-btn" data-song-id="<?php echo htmlspecialchars($song['song_id']); ?>" data-platform="<?php echo htmlspecialchars($song['platform']); ?>">
                            <i class="fas fa-play"></i> Play
                        </button>
                        <?php if (isLoggedIn()): ?>
                        <button class="action-btn save-btn" data-song-id="<?php echo htmlspecialchars($song['song_id']); ?>" data-platform="<?php echo htmlspecialchars($song['platform']); ?>">
                            <i class="fas fa-heart"></i> Save
                        </button>
                        <button class="action-btn add-to-playlist-btn" data-song-id="<?php echo htmlspecialchars($song['song_id']); ?>" data-platform="<?php echo htmlspecialchars($song['platform']); ?>">
                            <i class="fas fa-plus"></i> Add to Playlist
                        </button>
                        <button class="action-btn review-btn" data-song-id="<?php echo htmlspecialchars($song['song_id']); ?>" data-platform="<?php echo htmlspecialchars($song['platform']); ?>">
                            <i class="fas fa-comment"></i> Review
                        </button>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="previous-songs">
            <h2>Previous Songs</h2>
            <div class="songs-grid">
                <?php 
                $previousSongs = getPreviousSongsOfDay();
                foreach ($previousSongs as $prevSong): 
                ?>
                <div class="song-card">
                    <img src="<?php echo htmlspecialchars($prevSong['image_url']); ?>" alt="<?php echo htmlspecialchars($prevSong['title']); ?>" class="song-thumb">
                    <div class="song-info">
                        <h3 class="song-title"><?php echo htmlspecialchars($prevSong['title']); ?></h3>
                        <p class="song-artist"><?php echo htmlspecialchars($prevSong['artist']); ?></p>
                        <p class="song-date"><?php echo date('F j, Y', strtotime($prevSong['date'])); ?></p>
                        <span class="song-platform platform-<?php echo htmlspecialchars($prevSong['platform']); ?>"><?php echo ucfirst(htmlspecialchars($prevSong['platform'])); ?></span>
                        <div class="song-actions">
                            <button class="action-btn play-btn" data-song-id="<?php echo htmlspecialchars($prevSong['song_id']); ?>" data-platform="<?php echo htmlspecialchars($prevSong['platform']); ?>">
                                <i class="fas fa-play"></i>
                            </button>
                            <?php if (isLoggedIn()): ?>
                            <button class="action-btn save-btn" data-song-id="<?php echo htmlspecialchars($prevSong['song_id']); ?>" data-platform="<?php echo htmlspecialchars($prevSong['platform']); ?>">
                                <i class="fas fa-heart"></i>
                            </button>
                            <button class="action-btn add-to-playlist-btn" data-song-id="<?php echo htmlspecialchars($prevSong['song_id']); ?>" data-platform="<?php echo htmlspecialchars($prevSong['platform']); ?>">
                                <i class="fas fa-plus"></i>
                            </button>
                            <button class="action-btn review-btn" data-song-id="<?php echo htmlspecialchars($prevSong['song_id']); ?>" data-platform="<?php echo htmlspecialchars($prevSong['platform']); ?>">
                                <i class="fas fa-comment"></i>
                            </button>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
                
                <?php if (empty($previousSongs)): ?>
                <p class="no-songs-message">No previous songs available yet.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script src="../assets/js/player.js"></script>
<script>
    // Song of Day specific JavaScript
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize player functionality
        initializePlayer();
        
        // Add event listeners for song actions
        document.querySelectorAll('.save-btn').forEach(button => {
            button.addEventListener('click', function() {
                const songId = this.getAttribute('data-song-id');
                const platform = this.getAttribute('data-platform');
                saveSong(songId, platform);
            });
        });
        
        document.querySelectorAll('.add-to-playlist-btn').forEach(button => {
            button.addEventListener('click', function() {
                const songId = this.getAttribute('data-song-id');
                const platform = this.getAttribute('data-platform');
                showAddToPlaylistModal(songId, platform);
            });
        });
        
        document.querySelectorAll('.review-btn').forEach(button => {
            button.addEventListener('click', function() {
                const songId = this.getAttribute('data-song-id');
                const platform = this.getAttribute('data-platform');
                showReviewModal(songId, platform);
            });
        });
    });
    
    function saveSong(songId, platform) {
        fetch('../backend/api/save_song.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `song_id=${songId}&platform=${platform}`
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Song saved successfully!');
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while saving the song.');
        });
    }
    
    function showAddToPlaylistModal(songId, platform) {
        // Implementation would show a modal for adding to playlist
        alert('Add to playlist functionality would be implemented here.');
    }
    
    function showReviewModal(songId, platform) {
        // Implementation would show a modal for writing a review
        alert('Review functionality would be implemented here.');
    }
</script>

<?php
// Include footer
include_once '../includes/footer.php';
?>