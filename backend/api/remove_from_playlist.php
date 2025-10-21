<?php
// Include necessary files
require_once '../../includes/auth.php';
require_once '../../includes/functions.php';

// Set content type to JSON
header('Content-Type: application/json');

// Check if user is logged in
if (!isLoggedIn()) {
    echo json_encode(['success' => false, 'message' => 'You must be logged in to remove songs from playlists']);
    exit;
}

// Check if request is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

// Get POST data
$playlist_id = isset($_POST['playlist_id']) ? (int)$_POST['playlist_id'] : 0;
$song_id = isset($_POST['song_id']) ? (int)$_POST['song_id'] : 0;

// Validate data
if ($playlist_id <= 0 || $song_id <= 0) {
    echo json_encode(['success' => false, 'message' => 'Invalid playlist or song ID']);
    exit;
}

// Check if user owns the playlist
$user_id = $_SESSION['user_id'];
$check_sql = "SELECT * FROM playlists WHERE id = $playlist_id AND user_id = $user_id";
$check_result = $conn->query($check_sql);

if (!$check_result || $check_result->num_rows == 0) {
    echo json_encode(['success' => false, 'message' => 'You do not have permission to modify this playlist']);
    exit;
}

// Remove song from playlist
if (removeSongFromPlaylist($playlist_id, $song_id)) {
    echo json_encode(['success' => true, 'message' => 'Song removed from playlist']);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to remove song from playlist']);
}
?>