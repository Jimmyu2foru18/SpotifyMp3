<?php
// Include database connection
require_once 'db_connect.php';

// Get random songs from database
function getRandomSongs($platform, $limit = 12) {
    global $conn;
    
    $platform = $conn->real_escape_string($platform);
    $limit = (int)$limit;
    
    $sql = "SELECT * FROM songs WHERE platform = '$platform' ORDER BY RAND() LIMIT $limit";
    $result = $conn->query($sql);
    
    $songs = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $songs[] = $row;
        }
    }
    
    return $songs;
}

// Get song of the day
function getSongOfDay() {
    global $conn;
    
    // Get current date
    $date = date('Y-m-d');
    
    // Check if we already have a song for today
    $sql = "SELECT * FROM songs WHERE id = (SELECT song_id FROM song_of_day WHERE date = '$date')";
    $result = $conn->query($sql);
    
    if ($result && $result->num_rows > 0) {
        return $result->fetch_assoc();
    }
    
    // If not, select a random song and save it
    $sql = "SELECT * FROM songs ORDER BY RAND() LIMIT 1";
    $result = $conn->query($sql);
    
    if ($result && $result->num_rows > 0) {
        $song = $result->fetch_assoc();
        
        // Save as song of the day
        $song_id = $song['id'];
        $conn->query("INSERT INTO song_of_day (song_id, date) VALUES ($song_id, '$date')");
        
        return $song;
    }
    
    return null;
}

// Get user playlists
function getUserPlaylists($user_id) {
    global $conn;
    
    $user_id = (int)$user_id;
    
    $sql = "SELECT * FROM playlists WHERE user_id = $user_id ORDER BY created_at DESC";
    $result = $conn->query($sql);
    
    $playlists = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $playlists[] = $row;
        }
    }
    
    return $playlists;
}

// Get playlist songs
function getPlaylistSongs($playlist_id) {
    global $conn;
    
    $playlist_id = (int)$playlist_id;
    
    $sql = "SELECT s.* FROM songs s 
            JOIN playlist_songs ps ON s.id = ps.song_id 
            WHERE ps.playlist_id = $playlist_id";
    $result = $conn->query($sql);
    
    $songs = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $songs[] = $row;
        }
    }
    
    return $songs;
}

// Create new playlist
function createPlaylist($user_id, $name, $description = '') {
    global $conn;
    
    $user_id = (int)$user_id;
    $name = $conn->real_escape_string($name);
    $description = $conn->real_escape_string($description);
    
    $sql = "INSERT INTO playlists (user_id, name, `desc`) VALUES ($user_id, '$name', '$description')";
    
    if ($conn->query($sql)) {
        return $conn->insert_id;
    }
    
    return false;
}

// Add song to playlist
function addSongToPlaylist($playlist_id, $song_id) {
    global $conn;
    
    $playlist_id = (int)$playlist_id;
    $song_id = (int)$song_id;
    
    // Check if song already exists in playlist
    $check_sql = "SELECT * FROM playlist_songs WHERE playlist_id = $playlist_id AND song_id = $song_id";
    $check_result = $conn->query($check_sql);
    
    if ($check_result->num_rows > 0) {
        return true; // Song already in playlist
    }
    
    $sql = "INSERT INTO playlist_songs (playlist_id, song_id) VALUES ($playlist_id, $song_id)";
    
    return $conn->query($sql);
}

// Remove song from playlist
function removeSongFromPlaylist($playlist_id, $song_id) {
    global $conn;
    
    $playlist_id = (int)$playlist_id;
    $song_id = (int)$song_id;
    
    $sql = "DELETE FROM playlist_songs WHERE playlist_id = $playlist_id AND song_id = $song_id";
    
    return $conn->query($sql);
}

// Get user reviews
function getUserReviews($user_id) {
    global $conn;
    
    $user_id = (int)$user_id;
    
    $sql = "SELECT r.*, s.title, s.artist, s.thumb, s.platform, s.song_id as external_id 
            FROM reviews r 
            JOIN songs s ON r.song_id = s.id 
            WHERE r.user_id = $user_id 
            ORDER BY r.created_at DESC";
    $result = $conn->query($sql);
    
    $reviews = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $reviews[] = $row;
        }
    }
    
    return $reviews;
}

// Get song reviews
function getSongReviews($song_id) {
    global $conn;
    
    $song_id = (int)$song_id;
    
    $sql = "SELECT r.*, u.username, u.image 
            FROM reviews r 
            JOIN users u ON r.user_id = u.id 
            WHERE r.song_id = $song_id 
            ORDER BY r.created_at DESC";
    $result = $conn->query($sql);
    
    $reviews = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $reviews[] = $row;
        }
    }
    
    return $reviews;
}

// Add review
function addReview($user_id, $song_id, $text) {
    global $conn;
    
    $user_id = (int)$user_id;
    $song_id = (int)$song_id;
    $text = $conn->real_escape_string($text);
    
    $sql = "INSERT INTO reviews (user_id, song_id, text) VALUES ($user_id, $song_id, '$text')";
    
    return $conn->query($sql);
}

// Get or create song
function getOrCreateSong($platform, $external_id, $title, $artist, $thumb, $preview = '') {
    global $conn;
    
    $platform = $conn->real_escape_string($platform);
    $external_id = $conn->real_escape_string($external_id);
    $title = $conn->real_escape_string($title);
    $artist = $conn->real_escape_string($artist);
    $thumb = $conn->real_escape_string($thumb);
    $preview = $conn->real_escape_string($preview);
    
    // Check if song exists
    $sql = "SELECT * FROM songs WHERE platform = '$platform' AND song_id = '$external_id'";
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
        return $result->fetch_assoc()['id'];
    }
    
    // Create new song
    $sql = "INSERT INTO songs (platform, song_id, title, artist, thumb, preview) 
            VALUES ('$platform', '$external_id', '$title', '$artist', '$thumb', '$preview')";
    
    if ($conn->query($sql)) {
        return $conn->insert_id;
    }
    
    return false;
}

// Search Spotify API
function searchSpotify($query) {
    global $conn;
    $query = sanitizeInput($query);
    
    // In a production environment, this would use the actual Spotify API
    // For demonstration purposes, we're using mock data
    
    // Log search query in search_history if user is logged in
    if (isset($_SESSION['user_id'])) {
        $user_id = (int)$_SESSION['user_id'];
        $sanitized_query = $conn->real_escape_string($query);
        $conn->query("INSERT INTO search_history (user_id, platform, search_query) VALUES ($user_id, 'spotify', '$sanitized_query')");
    }
    
    $tracks = [
        [
            'id' => '0VjIjW4GlUZAMYd2vXMi3b',
            'name' => 'Blinding Lights',
            'artists' => [['name' => 'The Weeknd']],
            'album' => ['images' => [['url' => 'https://i.scdn.co/image/ab67616d0000b273af52c228c9619ff6298b08cd']]],
            'preview_url' => 'https://p.scdn.co/mp3-preview/5ee8f1a252f8f7c9f9eaf11e5b60f75a44fb9a3b'
        ],
        [
            'id' => '39LLxExYz6ewLAcYrzQQyP',
            'name' => 'Levitating',
            'artists' => [['name' => 'Dua Lipa']],
            'album' => ['images' => [['url' => 'https://i.scdn.co/image/ab67616d0000b273e6f407c7f3a0ec98845e4431']]],
            'preview_url' => 'https://p.scdn.co/mp3-preview/a690531d5152598f2ed9e9b7e8bb1820cb5a56be'
        ],
        [
            'id' => '5QO79kh1waicV47BqGRL3g',
            'name' => 'Save Your Tears',
            'artists' => [['name' => 'The Weeknd']],
            'album' => ['images' => [['url' => 'https://i.scdn.co/image/ab67616d0000b273af52c228c9619ff6298b08cd']]],
            'preview_url' => 'https://p.scdn.co/mp3-preview/8b6e9a3be5162b76d0fb1387152cad57ad6b56f7'
        ]
    ];
    
    return ['success' => true, 'tracks' => $tracks];
}

// Search YouTube API
function searchYouTube($query) {
    global $conn;
    $query = sanitizeInput($query);
    
    // In a production environment, this would use the actual YouTube Data API v3
    // For demonstration purposes, we're using mock data
    
    // Log search query in search_history if user is logged in
    if (isset($_SESSION['user_id'])) {
        $user_id = (int)$_SESSION['user_id'];
        $sanitized_query = $conn->real_escape_string($query);
        $conn->query("INSERT INTO search_history (user_id, platform, search_query) VALUES ($user_id, 'youtube', '$sanitized_query')");
    }
    
    $videos = [
        [
            'id' => 'kTJczUoc26U',
            'title' => 'The Hills',
            'channelTitle' => 'The Weeknd',
            'thumbnail' => 'https://i.ytimg.com/vi/kTJczUoc26U/maxresdefault.jpg',
            'embedUrl' => 'https://www.youtube.com/embed/kTJczUoc26U'
        ],
        [
            'id' => 'JGwWNGJdvx8',
            'title' => 'Shape of You',
            'channelTitle' => 'Ed Sheeran',
            'thumbnail' => 'https://i.ytimg.com/vi/JGwWNGJdvx8/maxresdefault.jpg',
            'embedUrl' => 'https://www.youtube.com/embed/JGwWNGJdvx8'
        ],
        [
            'id' => 'fHI8X4OXluQ',
            'title' => 'Watermelon Sugar',
            'channelTitle' => 'Harry Styles',
            'thumbnail' => 'https://i.ytimg.com/vi/fHI8X4OXluQ/maxresdefault.jpg',
            'embedUrl' => 'https://www.youtube.com/embed/fHI8X4OXluQ'
        ]
    ];
    
    return ['success' => true, 'videos' => $videos];
}
?>