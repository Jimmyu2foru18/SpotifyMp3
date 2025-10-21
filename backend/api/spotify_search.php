<?php
// Include necessary files
require_once '../../includes/functions.php';

// Set content type to JSON
header('Content-Type: application/json');

// Get query parameter
$query = $_GET['q'] ?? '';

if (empty($query)) {
    echo json_encode(['success' => false, 'message' => 'Search query is required']);
    exit;
}

// Search Spotify API
$results = searchSpotify($query);

// Return results
echo json_encode($results);
?>