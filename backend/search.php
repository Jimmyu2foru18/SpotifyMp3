<?php
include('../includes/header.php');
include('../includes/navbar.php');
require_once('../includes/functions.php');

$query = $_GET['q'] ?? '';
$spotify_results = [];
$youtube_results = [];

if (!empty($query)) {
    // Search Spotify
    $spotify_data = searchSpotify($query);
    if ($spotify_data['success']) {
        foreach ($spotify_data['tracks'] as $track) {
            $spotify_results[] = [
                'platform' => 'spotify',
                'song_id' => $track['id'],
                'title' => $track['name'],
                'artist' => $track['artists'][0]['name'],
                'thumb' => $track['album']['images'][0]['url'],
                'preview' => ''
            ];
        }
    }
    
    // Search YouTube
    $youtube_data = searchYouTube($query);
    if ($youtube_data['success']) {
        foreach ($youtube_data['videos'] as $video) {
            $youtube_results[] = [
                'platform' => 'youtube',
                'song_id' => $video['id'],
                'title' => $video['title'],
                'artist' => $video['channelTitle'],
                'thumb' => $video['thumbnail'],
                'preview' => ''
            ];
        }
    }
}
?>

<div class="container">
    <div class="search-page">
        <h1>Search Results</h1>
        
        <div class="search-form">
            <form action="search.php" method="GET">
                <div class="form-group">
                    <input type="text" name="q" value="<?php echo htmlspecialchars($query); ?>" placeholder="Search for songs...">
                    <button type="submit" class="btn btn-primary">Search</button>
                </div>
            </form>
        </div>
        
        <?php if (!empty($query)): ?>
            <div class="search-tabs">
                <button class="tab-btn active" data-tab="spotify">Spotify Results</button>
                <button class="tab-btn" data-tab="youtube">YouTube Results</button>
            </div>
            
            <div class="tab-content">
                <div id="spotify-tab" class="tab-pane active">
                    <?php if (!empty($spotify_results)): ?>
                        <div class="song-grid">
                            <?php foreach ($spotify_results as $index => $song): ?>
                                <div class="song-card" data-platform="<?php echo $song['platform']; ?>" data-external-id="<?php echo $song['song_id']; ?>">
                                    <div class="song-thumb">
                                        <img src="<?php echo $song['thumb']; ?>" alt="<?php echo $song['title']; ?>">
                                        <div class="play-overlay">
                                            <button class="play-btn" onclick="playSong(<?php echo $index; ?>, '<?php echo $song['platform']; ?>', '<?php echo $song['song_id']; ?>', '<?php echo htmlspecialchars(addslashes($song['title'])); ?>', '<?php echo htmlspecialchars(addslashes($song['artist'])); ?>', '<?php echo $song['thumb']; ?>')">
                                                <i class="fas fa-play"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="song-info">
                                        <h3><?php echo $song['title']; ?></h3>
                                        <p><?php echo $song['artist']; ?></p>
                                    </div>
                                    <div class="song-actions">
                                        <button class="btn btn-sm save-song" data-platform="<?php echo $song['platform']; ?>" data-id="<?php echo $song['song_id']; ?>" data-title="<?php echo htmlspecialchars(addslashes($song['title'])); ?>" data-artist="<?php echo htmlspecialchars(addslashes($song['artist'])); ?>" data-thumb="<?php echo $song['thumb']; ?>">
                                            <i class="fas fa-heart"></i>
                                        </button>
                                        <button class="btn btn-sm add-to-playlist" data-platform="<?php echo $song['platform']; ?>" data-id="<?php echo $song['song_id']; ?>" data-title="<?php echo htmlspecialchars(addslashes($song['title'])); ?>" data-artist="<?php echo htmlspecialchars(addslashes($song['artist'])); ?>" data-thumb="<?php echo $song['thumb']; ?>">
                                            <i class="fas fa-plus"></i>
                                        </button>
                                        <button class="btn btn-sm review-song" data-platform="<?php echo $song['platform']; ?>" data-id="<?php echo $song['song_id']; ?>" data-title="<?php echo htmlspecialchars(addslashes($song['title'])); ?>" data-artist="<?php echo htmlspecialchars(addslashes($song['artist'])); ?>">
                                            <i class="fas fa-comment"></i>
                                        </button>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <p class="no-results">No Spotify results found for "<?php echo htmlspecialchars($query); ?>"</p>
                    <?php endif; ?>
                </div>
                
                <div id="youtube-tab" class="tab-pane">
                    <?php if (!empty($youtube_results)): ?>
                        <div class="song-grid">
                            <?php foreach ($youtube_results as $index => $song): ?>
                                <div class="song-card" data-platform="<?php echo $song['platform']; ?>" data-external-id="<?php echo $song['song_id']; ?>">
                                    <div class="song-thumb">
                                        <img src="<?php echo $song['thumb']; ?>" alt="<?php echo $song['title']; ?>">
                                        <div class="play-overlay">
                                            <button class="play-btn" onclick="playSong(<?php echo $index; ?>, '<?php echo $song['platform']; ?>', '<?php echo $song['song_id']; ?>', '<?php echo htmlspecialchars(addslashes($song['title'])); ?>', '<?php echo htmlspecialchars(addslashes($song['artist'])); ?>', '<?php echo $song['thumb']; ?>')">
                                                <i class="fas fa-play"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="song-info">
                                        <h3><?php echo $song['title']; ?></h3>
                                        <p><?php echo $song['artist']; ?></p>
                                    </div>
                                    <div class="song-actions">
                                        <button class="btn btn-sm save-song" data-platform="<?php echo $song['platform']; ?>" data-id="<?php echo $song['song_id']; ?>" data-title="<?php echo htmlspecialchars(addslashes($song['title'])); ?>" data-artist="<?php echo htmlspecialchars(addslashes($song['artist'])); ?>" data-thumb="<?php echo $song['thumb']; ?>">
                                            <i class="fas fa-heart"></i>
                                        </button>
                                        <button class="btn btn-sm add-to-playlist" data-platform="<?php echo $song['platform']; ?>" data-id="<?php echo $song['song_id']; ?>" data-title="<?php echo htmlspecialchars(addslashes($song['title'])); ?>" data-artist="<?php echo htmlspecialchars(addslashes($song['artist'])); ?>" data-thumb="<?php echo $song['thumb']; ?>">
                                            <i class="fas fa-plus"></i>
                                        </button>
                                        <button class="btn btn-sm review-song" data-platform="<?php echo $song['platform']; ?>" data-id="<?php echo $song['song_id']; ?>" data-title="<?php echo htmlspecialchars(addslashes($song['title'])); ?>" data-artist="<?php echo htmlspecialchars(addslashes($song['artist'])); ?>">
                                            <i class="fas fa-comment"></i>
                                        </button>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <p class="no-results">No YouTube results found for "<?php echo htmlspecialchars($query); ?>"</p>
                    <?php endif; ?>
                </div>
            </div>
        <?php else: ?>
            <div class="search-instructions">
                <h2>Search for your favorite music</h2>
                <p>Enter a song title, artist name, or keywords to find music from Spotify and YouTube.</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Add to Playlist Modal -->
<div id="playlist-modal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h2>Add to Playlist</h2>
        <div id="playlist-list">
            <?php if (isLoggedIn()): ?>
                <?php $playlists = getUserPlaylists($_SESSION['user_id']); ?>
                <?php if (!empty($playlists)): ?>
                    <ul>
                        <?php foreach ($playlists as $playlist): ?>
                            <li>
                                <button class="btn btn-sm playlist-select" data-id="<?php echo $playlist['id']; ?>">
                                    <?php echo $playlist['name']; ?>
                                </button>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    <p>You don't have any playlists yet.</p>
                <?php endif; ?>
                
                <div class="new-playlist">
                    <h3>Create New Playlist</h3>
                    <form id="new-playlist-form">
                        <div class="form-group">
                            <label for="playlist-name">Name</label>
                            <input type="text" id="playlist-name" name="name" required>
                        </div>
                        <div class="form-group">
                            <label for="playlist-desc">Description (optional)</label>
                            <textarea id="playlist-desc" name="description"></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Create & Add</button>
                    </form>
                </div>
            <?php else: ?>
                <p>Please <a href="login.php">login</a> to add songs to playlists.</p>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Review Modal -->
<div id="review-modal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h2>Write a Review</h2>
        <div id="review-form-container">
            <?php if (isLoggedIn()): ?>
                <form id="review-form">
                    <div class="form-group">
                        <label for="review-text">Your Review</label>
                        <textarea id="review-text" name="text" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Submit Review</button>
                </form>
            <?php else: ?>
                <p>Please <a href="login.php">login</a> to write reviews.</p>
            <?php endif; ?>
        </div>
    </div>
</div>

<script src="../assets/js/search.js"></script>

<?php include('../includes/footer.php'); ?>