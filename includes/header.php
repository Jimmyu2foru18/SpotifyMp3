<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SpotifyMP3 - <?php echo $page_title ?? 'Music Platform'; ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
    <?php if(isset($is_admin) && $is_admin): ?>
    <link rel="stylesheet" href="../assets/css/admin.css">
    <?php endif; ?>
</head>
<body<?php echo isset($_SESSION['user_id']) ? ' class="logged-in"' : ''; ?><?php echo isset($_SESSION['is_admin']) && $_SESSION['is_admin'] ? ' class="is-admin"' : ''; ?>>