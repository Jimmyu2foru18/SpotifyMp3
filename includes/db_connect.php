<?php
// Database connection
$conn = new mysqli('localhost', 'root', '', 'spotifymp3');

// Check connection
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}
?>