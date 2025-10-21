<?php
// Include necessary files
require_once '../../includes/auth.php';
require_once '../../includes/functions.php';

// Set content type to JSON
header('Content-Type: application/json');

// Check if user is logged in
if (!isLoggedIn()) {
    echo json_encode(['success' => false, 'message' => 'You must be logged in to save songs']);
    exit;
}

// Check if request is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

// Get POST data
$platform = $_POST['platform'] ?? '';
$external_id = $_POST['external_id'] ?? '';
$title = $_POST['title'] ?? '';
$artist = $_POST['artist'] ?? '';
$thumb = $_POST['thumb'] ?? '';
$preview = $_POST['preview'] ?? '';

// Validate data
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

// Save song to user's saved songs
$user_id = $_SESSION['user_id'];

// Check if song is already saved
$check_sql = "SELECT * FROM saved_songs WHERE user_id = $user_id AND song_id = $song_id";
$check_result = $conn->query($check_sql);

if ($check_result && $check_result->num_rows > 0) {
    echo json_encode(['success' => true, 'message' => 'Song already saved']);
    exit;
}

// Save song
$sql = "INSERT INTO saved_songs (user_id, song_id) VALUES ($user_id, $song_id)";
if ($conn->query($sql)) {
    echo json_encode(['success' => true, 'message' => 'Song saved successfully']);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to save song']);
}
?>