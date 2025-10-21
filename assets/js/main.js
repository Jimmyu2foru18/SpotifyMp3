// SpotifyMP3 Main JavaScript

document.addEventListener('DOMContentLoaded', function() {
    // Initialize components
    initNavbar();
    initPlayerControls();
    
    // Check if user is logged in
    checkAuthStatus();
});

// Navbar functionality
function initNavbar() {
    // Mobile menu toggle
    const menuToggle = document.querySelector('.menu-toggle');
    const navLinks = document.querySelector('.nav-links');
    
    if (menuToggle) {
        menuToggle.addEventListener('click', function() {
            navLinks.classList.toggle('active');
        });
    }
}

// Player controls
function initPlayerControls() {
    const playButtons = document.querySelectorAll('.play-btn');
    const saveButtons = document.querySelectorAll('.save-btn');
    const addToPlaylistButtons = document.querySelectorAll('.add-to-playlist-btn');
    const reviewButtons = document.querySelectorAll('.review-btn');
    
    // Play button functionality
    playButtons.forEach(button => {
        button.addEventListener('click', function() {
            const songId = this.dataset.songId;
            const platform = this.dataset.platform;
            playSong(songId, platform);
        });
    });
    
    // Save button functionality
    saveButtons.forEach(button => {
        button.addEventListener('click', function() {
            const songId = this.dataset.songId;
            const platform = this.dataset.platform;
            saveSong(songId, platform);
        });
    });
    
    // Add to playlist functionality
    addToPlaylistButtons.forEach(button => {
        button.addEventListener('click', function() {
            const songId = this.dataset.songId;
            showPlaylistModal(songId);
        });
    });
    
    // Review button functionality
    reviewButtons.forEach(button => {
        button.addEventListener('click', function() {
            const songId = this.dataset.songId;
            showReviewModal(songId);
        });
    });
}

// Play song function
function playSong(songId, platform) {
    const player = document.getElementById('music-player');
    
    if (platform === 'spotify') {
        // Spotify playback
        if (player) {
            player.innerHTML = `<iframe src="https://open.spotify.com/embed/track/${songId}" 
                width="100%" height="80" frameborder="0" allowtransparency="true" 
                allow="encrypted-media"></iframe>`;
        }
    } else if (platform === 'youtube') {
        // YouTube playback
        if (player) {
            player.innerHTML = `<iframe width="100%" height="80" 
                src="https://www.youtube.com/embed/${songId}?autoplay=1" 
                frameborder="0" allow="accelerometer; autoplay; clipboard-write; 
                encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>`;
        }
    }
    
    // Show player if hidden
    if (player) {
        player.style.display = 'block';
    }
}

// Save song function
function saveSong(songId, platform) {
    // Check if user is logged in
    if (!isLoggedIn()) {
        showLoginPrompt();
        return;
    }
    
    // AJAX request to save song
    fetch('../backend/save_song.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `song_id=${songId}&platform=${platform}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification('Song saved successfully!', 'success');
        } else {
            showNotification(data.message || 'Error saving song', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('An error occurred', 'error');
    });
}

// Show playlist modal
function showPlaylistModal(songId) {
    // Check if user is logged in
    if (!isLoggedIn()) {
        showLoginPrompt();
        return;
    }
    
    // Get user playlists
    fetch('../backend/get_playlists.php')
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Create modal with playlists
            let playlistOptions = '';
            data.playlists.forEach(playlist => {
                playlistOptions += `<div class="playlist-option" data-id="${playlist.id}">
                    <span>${playlist.name}</span>
                </div>`;
            });
            
            const modal = document.createElement('div');
            modal.className = 'modal';
            modal.innerHTML = `
                <div class="modal-content">
                    <span class="close-modal">&times;</span>
                    <h3>Add to Playlist</h3>
                    <div class="playlist-options">
                        ${playlistOptions}
                        <div class="playlist-option new-playlist">
                            <span>+ Create New Playlist</span>
                        </div>
                    </div>
                </div>
            `;
            
            document.body.appendChild(modal);
            
            // Add event listeners
            const closeBtn = modal.querySelector('.close-modal');
            closeBtn.addEventListener('click', () => {
                modal.remove();
            });
            
            const playlistOptionElements = modal.querySelectorAll('.playlist-option');
            playlistOptionElements.forEach(option => {
                option.addEventListener('click', function() {
                    if (this.classList.contains('new-playlist')) {
                        showCreatePlaylistModal(songId);
                        modal.remove();
                    } else {
                        const playlistId = this.dataset.id;
                        addSongToPlaylist(songId, playlistId);
                        modal.remove();
                    }
                });
            });
            
            // Show modal
            modal.style.display = 'block';
        } else {
            showNotification(data.message || 'Error loading playlists', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('An error occurred', 'error');
    });
}

// Add song to playlist
function addSongToPlaylist(songId, playlistId) {
    fetch('../backend/add_to_playlist.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `song_id=${songId}&playlist_id=${playlistId}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification('Added to playlist!', 'success');
        } else {
            showNotification(data.message || 'Error adding to playlist', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('An error occurred', 'error');
    });
}

// Show review modal
function showReviewModal(songId) {
    // Check if user is logged in
    if (!isLoggedIn()) {
        showLoginPrompt();
        return;
    }
    
    const modal = document.createElement('div');
    modal.className = 'modal';
    modal.innerHTML = `
        <div class="modal-content">
            <span class="close-modal">&times;</span>
            <h3>Write a Review</h3>
            <form id="review-form">
                <div class="form-group">
                    <textarea class="form-control" id="review-text" rows="4" placeholder="Write your review here..."></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Submit Review</button>
            </form>
        </div>
    `;
    
    document.body.appendChild(modal);
    
    // Add event listeners
    const closeBtn = modal.querySelector('.close-modal');
    closeBtn.addEventListener('click', () => {
        modal.remove();
    });
    
    const form = modal.querySelector('#review-form');
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        const reviewText = document.getElementById('review-text').value;
        submitReview(songId, reviewText);
        modal.remove();
    });
    
    // Show modal
    modal.style.display = 'block';
}

// Submit review
function submitReview(songId, reviewText) {
    fetch('../backend/submit_review.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `song_id=${songId}&review=${reviewText}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification('Review submitted!', 'success');
        } else {
            showNotification(data.message || 'Error submitting review', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('An error occurred', 'error');
    });
}

// Check if user is logged in
function isLoggedIn() {
    return document.body.classList.contains('logged-in');
}

// Show login prompt
function showLoginPrompt() {
    const modal = document.createElement('div');
    modal.className = 'modal';
    modal.innerHTML = `
        <div class="modal-content">
            <span class="close-modal">&times;</span>
            <h3>Login Required</h3>
            <p>You need to be logged in to perform this action.</p>
            <div class="modal-actions">
                <a href="../backend/login.php" class="btn btn-primary">Login</a>
                <a href="../backend/register.php" class="btn btn-outline">Register</a>
            </div>
        </div>
    `;
    
    document.body.appendChild(modal);
    
    // Add event listeners
    const closeBtn = modal.querySelector('.close-modal');
    closeBtn.addEventListener('click', () => {
        modal.remove();
    });
    
    // Show modal
    modal.style.display = 'block';
}

// Show notification
function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.className = `notification ${type}`;
    notification.textContent = message;
    
    document.body.appendChild(notification);
    
    // Auto remove after 3 seconds
    setTimeout(() => {
        notification.classList.add('fade-out');
        setTimeout(() => {
            notification.remove();
        }, 500);
    }, 3000);
}

// Check auth status
function checkAuthStatus() {
    fetch('../backend/check_auth.php')
    .then(response => response.json())
    .then(data => {
        if (data.logged_in) {
            document.body.classList.add('logged-in');
            if (data.is_admin) {
                document.body.classList.add('is-admin');
            }
        } else {
            document.body.classList.remove('logged-in', 'is-admin');
        }
    })
    .catch(error => {
        console.error('Error checking auth status:', error);
    });
}