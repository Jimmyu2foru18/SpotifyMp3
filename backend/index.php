<?php
include('../includes/header.php');
include('../includes/navbar.php');
require_once('../includes/functions.php');

// Get random songs
$spotify_songs = getRandomSongs('spotify', 12);
$youtube_songs = getRandomSongs('youtube', 12);

// If no songs in database, use mock data
if (empty($spotify_songs)) {
    $spotify_songs = [
        ['id' => 1, 'platform' => 'spotify', 'song_id' => '0VjIjW4GlUZAMYd2vXMi3b', 'title' => 'Blinding Lights', 'artist' => 'The Weeknd', 'thumb' => 'https://i.scdn.co/image/ab67616d0000b273af52c228c9619ff6298b08cd', 'preview' => ''],
        ['id' => 2, 'platform' => 'spotify', 'song_id' => '39LLxExYz6ewLAcYrzQQyP', 'title' => 'Levitating', 'artist' => 'Dua Lipa', 'thumb' => 'https://i.scdn.co/image/ab67616d0000b273e6f407c7f3a0ec98845e4431', 'preview' => ''],
        ['id' => 3, 'platform' => 'spotify', 'song_id' => '5QO79kh1waicV47BqGRL3g', 'title' => 'Save Your Tears', 'artist' => 'The Weeknd', 'thumb' => 'https://i.scdn.co/image/ab67616d0000b273af52c228c9619ff6298b08cd', 'preview' => '']
    ];
    
    // Duplicate to get 12 songs
    $spotify_songs = array_merge($spotify_songs, $spotify_songs, $spotify_songs, $spotify_songs);
    $spotify_songs = array_slice($spotify_songs, 0, 12);
}

if (empty($youtube_songs)) {
    $youtube_songs = [
        ['id' => 4, 'platform' => 'youtube', 'song_id' => 'kTJczUoc26U', 'title' => 'The Hills', 'artist' => 'The Weeknd', 'thumb' => 'https://i.ytimg.com/vi/kTJczUoc26U/maxresdefault.jpg', 'preview' => ''],
        ['id' => 5, 'platform' => 'youtube', 'song_id' => 'JGwWNGJdvx8', 'title' => 'Shape of You', 'artist' => 'Ed Sheeran', 'thumb' => 'https://i.ytimg.com/vi/JGwWNGJdvx8/maxresdefault.jpg', 'preview' => ''],
        ['id' => 6, 'platform' => 'youtube', 'song_id' => 'fHI8X4OXluQ', 'title' => 'Watermelon Sugar', 'artist' => 'Harry Styles', 'thumb' => 'https://i.ytimg.com/vi/fHI8X4OXluQ/maxresdefault.jpg', 'preview' => '']
    ];
    
    // Duplicate to get 12 songs
    $youtube_songs = array_merge($youtube_songs, $youtube_songs, $youtube_songs, $youtube_songs);
    $youtube_songs = array_slice($youtube_songs, 0, 12);
}
?>

<!-- Hero Section -->
<section class="hero">
    <div class="container">
        <h1>Welcome to SpotifyMP3</h1>
        <p>Listen, save, and share your favorite music from Spotify and YouTube</p>
        <div class="search-box">
            <form action="search.php" method="GET">
                <input type="text" name="q" placeholder="Search for songs...">
                <button type="submit" class="btn btn-primary">Search</button>
            </form>
        </div>
    </div>
</section>

<!-- Spotify Songs Section -->
<section class="songs-section">
    <div class="container">
        <h2>Trending on Spotify</h2>
        <div class="song-grid">
            <?php foreach ($spotify_songs as $song): ?>
                <div class="song-card" data-id="<?php echo $song['id']; ?>" data-platform="<?php echo $song['platform']; ?>" data-external-id="<?php echo $song['song_id']; ?>">
                    <div class="song-thumb">
                        <img src="<?php echo $song['thumb']; ?>" alt="<?php echo $song['title']; ?>">
                        <div class="play-overlay">
                            <button class="play-btn" onclick="playSong(<?php echo $song['id']; ?>, '<?php echo $song['platform']; ?>', '<?php echo $song['song_id']; ?>', '<?php echo $song['title']; ?>', '<?php echo $song['artist']; ?>', '<?php echo $song['thumb']; ?>')">
                                <i class="fas fa-play"></i>
                            </button>
                        </div>
                    </div>
                    <div class="song-info">
                        <h3><?php echo $song['title']; ?></h3>
                        <p><?php echo $song['artist']; ?></p>
                    </div>
                    <div class="song-actions">
                        <button class="btn btn-sm" onclick="saveSong(<?php echo $song['id']; ?>)">
                            <i class="fas fa-heart"></i>
                        </button>
                        <button class="btn btn-sm" onclick="addToPlaylist(<?php echo $song['id']; ?>)">
                            <i class="fas fa-plus"></i>
                        </button>
                        <button class="btn btn-sm" onclick="reviewSong(<?php echo $song['id']; ?>)">
                            <i class="fas fa-comment"></i>
                        </button>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- YouTube Songs Section -->
<section class="songs-section">
    <div class="container">
        <h2>Trending on YouTube</h2>
        <div class="song-grid">
            <?php foreach ($youtube_songs as $song): ?>
                <div class="song-card" data-id="<?php echo $song['id']; ?>" data-platform="<?php echo $song['platform']; ?>" data-external-id="<?php echo $song['song_id']; ?>">
                    <div class="song-thumb">
                        <img src="<?php echo $song['thumb']; ?>" alt="<?php echo $song['title']; ?>">
                        <div class="play-overlay">
                            <button class="play-btn" onclick="playSong(<?php echo $song['id']; ?>, '<?php echo $song['platform']; ?>', '<?php echo $song['song_id']; ?>', '<?php echo $song['title']; ?>', '<?php echo $song['artist']; ?>', '<?php echo $song['thumb']; ?>')">
                                <i class="fas fa-play"></i>
                            </button>
                        </div>
                    </div>
                    <div class="song-info">
                        <h3><?php echo $song['title']; ?></h3>
                        <p><?php echo $song['artist']; ?></p>
                    </div>
                    <div class="song-actions">
                        <button class="btn btn-sm" onclick="saveSong(<?php echo $song['id']; ?>)">
                            <i class="fas fa-heart"></i>
                        </button>
                        <button class="btn btn-sm" onclick="addToPlaylist(<?php echo $song['id']; ?>)">
                            <i class="fas fa-plus"></i>
                        </button>
                        <button class="btn btn-sm" onclick="reviewSong(<?php echo $song['id']; ?>)">
                            <i class="fas fa-comment"></i>
                        </button>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

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
                                <button class="btn btn-sm" onclick="addSongToPlaylist(<?php echo $playlist['id']; ?>)">
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

<?php include('../includes/footer.php'); ?>