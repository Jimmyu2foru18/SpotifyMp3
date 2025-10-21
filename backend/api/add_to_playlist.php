<?php
// Include necessary files
require_once '../../includes/auth.php';
require_once '../../includes/functions.php';

// Set content type to JSON
header('Content-Type: application/json');

// Check if user is logged in
if (!isLoggedIn()) {
    echo json_encode(['success' => false, 'message' => 'You must be logged in to add songs to playlists']);
    exit;
}

// Check if request is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

// Get POST data
$playlist_id = isset($_POST['playlist_id']) ? (int)$_POST['playlist_id'] : 0;
$platform = $_POST['platform'] ?? '';
$external_id = $_POST['external_id'] ?? '';
$title = $_POST['title'] ?? '';
$artist = $_POST['artist'] ?? '';
$thumb = $_POST['thumb'] ?? '';
$preview = $_POST['preview'] ?? '';

// Validate data
if ($playlist_id <= 0) {
    echo json_encode(['success' => false, 'message' => 'Invalid playlist ID']);
    exit;
}

if (empty($platform) || empty($external_id) || empty($title) || empty($artist) || empty($thumb)) {
    echo json_encode(['success' => false, 'message' => 'Missing required fields']);
    exit;
}

// Get or create song in database
$song_id = getOrCreateSong($platform, $external_id, $title, $artist, $thumb, $preview);

if (!$song_id) {
    echo json_encode(['success' => false, 'message' => 'Failed to save song']);
    exit;
}

// Add song to playlist
if (addSongToPlaylist($playlist_id, $song_id)) {
    echo json_encode(['success' => true, 'message' => 'Song added to playlist']);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to add song to playlist']);
}
?>