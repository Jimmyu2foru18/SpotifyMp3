<!-- Navigation -->
<nav class="navbar">
    <div class="container">
        <a href="index.php" class="logo">SpotifyMP3</a>
        <ul class="nav-links">
            <li><a href="index.php" <?php echo $current_page == 'home' ? 'class="active"' : ''; ?>>Home</a></li>
            <li><a href="songofday.php" <?php echo $current_page == 'songofday' ? 'class="active"' : ''; ?>>Song of Day</a></li>
            <li><a href="playlists.php" <?php echo $current_page == 'playlists' ? 'class="active"' : ''; ?>>Playlists</a></li>
            <li><a href="reviews.php" <?php echo $current_page == 'reviews' ? 'class="active"' : ''; ?>>Reviews</a></li>
            <li><a href="search.php" <?php echo $current_page == 'search' ? 'class="active"' : ''; ?>>Search</a></li>
            
            <?php if(!isset($_SESSION['user_id'])): ?>
                <li><a href="login.php" <?php echo $current_page == 'login' ? 'class="active"' : ''; ?>>Login</a></li>
                <li><a href="register.php" <?php echo $current_page == 'register' ? 'class="active"' : ''; ?>>Register</a></li>
            <?php else: ?>
                <li><a href="profile.php" <?php echo $current_page == 'profile' ? 'class="active"' : ''; ?>>Profile</a></li>
                <?php if(isset($_SESSION['is_admin']) && $_SESSION['is_admin']): ?>
                    <li><a href="admin.php" <?php echo $current_page == 'admin' ? 'class="active"' : ''; ?>>Admin</a></li>
                <?php endif; ?>
                <li><a href="logout.php">Logout</a></li>
            <?php endif; ?>
        </ul>
    </div>
</nav>