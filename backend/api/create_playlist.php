<?php
// Include necessary files
require_once '../../includes/auth.php';
require_once '../../includes/functions.php';

// Set content type to JSON
header('Content-Type: application/json');

// Check if user is logged in
if (!isLoggedIn()) {
    echo json_encode(['success' => false, 'message' => 'You must be logged in to create playlists']);
    exit;
}

// Check if request is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

// Get POST data
$name = $_POST['name'] ?? '';
$description = $_POST['description'] ?? '';
$song_id = isset($_POST['song_id']) ? (int)$_POST['song_id'] : 0;

// Validate data
if (empty($name)) {
    echo json_encode(['success' => false, 'message' => 'Playlist name is required']);
    exit;
}

// Create playlist
$user_id = $_SESSION['user_id'];
$playlist_id = createPlaylist($user_id, $name, $description);

if (!$playlist_id) {
    echo json_encode(['success' => false, 'message' => 'Failed to create playlist']);
    exit;
}

// Add song to playlist if provided
if ($song_id > 0) {
    addSongToPlaylist($playlist_id, $song_id);
}

echo json_encode([
    'success' => true, 
    'message' => 'Playlist created successfully',
    'playlist_id' => $playlist_id,
    'playlist_name' => $name
]);
?>