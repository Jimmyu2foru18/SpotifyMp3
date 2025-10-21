<?php
// Include necessary files
require_once '../../includes/auth.php';
require_once '../../includes/functions.php';

// Set content type to JSON
header('Content-Type: application/json');

// Check if user is logged in
if (!isLoggedIn()) {
    echo json_encode(['success' => false, 'message' => 'You must be logged in to add reviews']);
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
$text = $_POST['text'] ?? '';

// Validate data
if (empty($platform) || empty($external_id) || empty($title) || empty($artist) || empty($text)) {
    echo json_encode(['success' => false, 'message' => 'Missing required fields']);
    exit;
}

// Get or create song in database
$song_id = getOrCreateSong($platform, $external_id, $title, $artist, $thumb);

if (!$song_id) {
    echo json_encode(['success' => false, 'message' => 'Failed to save song']);
    exit;
}

// Add review
$user_id = $_SESSION['user_id'];
if (addReview($user_id, $song_id, $text)) {
    echo json_encode(['success' => true, 'message' => 'Review added successfully']);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to add review']);
}
?>