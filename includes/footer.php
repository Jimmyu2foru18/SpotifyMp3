<!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-section">
                    <h3>SpotifyMP3</h3>
                    <p>Discover and save your favorite music from Spotify and YouTube.</p>
                </div>
                <div class="footer-section">
                    <h3>Quick Links</h3>
                    <ul class="footer-links">
                        <li><a href="index.php">Home</a></li>
                        <li><a href="songofday.php">Song of Day</a></li>
                        <li><a href="playlists.php">Playlists</a></li>
                        <li><a href="reviews.php">Reviews</a></li>
                        <li><a href="search.php">Search</a></li>
                    </ul>
                </div>
                <div class="footer-section">
                    <h3>Account</h3>
                    <ul class="footer-links">
                        <?php if(!isset($_SESSION['user_id'])): ?>
                            <li><a href="login.php">Login</a></li>
                            <li><a href="register.php">Register</a></li>
                        <?php else: ?>
                            <li><a href="profile.php">Profile</a></li>
                            <li><a href="logout.php">Logout</a></li>
                        <?php endif; ?>
                    </ul>
                </div>
                <div class="footer-section">
                    <h3>Help</h3>
                    <ul class="footer-links">
                        <li><a href="about.php">About</a></li>
                        <li><a href="#">Contact</a></li>
                        <li><a href="#">FAQ</a></li>
                    </ul>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; <?php echo date('Y'); ?> SpotifyMP3. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- Music Player -->
    <div id="player-container" class="player-container">
        <div id="music-player" class="music-player"></div>
        <div id="player-controls" class="player-controls">
            <div id="now-playing" class="now-playing"></div>
            <div class="controls">
                <button id="play-pause-btn" class="control-btn">
                    <i class="fas fa-play"></i>
                </button>
                <div class="progress">
                    <div id="progress-bar" class="progress-bar">
                        <div id="progress-fill" class="progress-fill"></div>
                    </div>
                    <div id="time-display" class="time-display">0:00 / 0:00</div>
                </div>
                <div class="volume">
                    <i class="fas fa-volume-up"></i>
                    <input type="range" id="volume-control" min="0" max="100" value="80">
                </div>
            </div>
        </div>
    </div>

    <script src="../assets/js/main.js"></script>
    <?php if(isset($page_scripts)): ?>
        <?php foreach($page_scripts as $script): ?>
            <script src="<?php echo $script; ?>"></script>
        <?php endforeach; ?>
    <?php endif; ?>
</body>
</html>