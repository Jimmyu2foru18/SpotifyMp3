// SpotifyMP3 Player Functionality

let currentSong = null;
let isPlaying = false;
let volume = 0.8;

document.addEventListener('DOMContentLoaded', function() {
    initPlayer();
});

function initPlayer() {
    const player = document.getElementById('music-player');
    const playerControls = document.getElementById('player-controls');
    
    if (!player || !playerControls) return;
    
    // Volume control
    const volumeControl = document.getElementById('volume-control');
    if (volumeControl) {
        volumeControl.value = volume * 100;
        volumeControl.addEventListener('input', function() {
            setVolume(this.value / 100);
        });
    }
    
    // Play/Pause button
    const playPauseBtn = document.getElementById('play-pause-btn');
    if (playPauseBtn) {
        playPauseBtn.addEventListener('click', function() {
            togglePlayPause();
        });
    }
    
    // Progress bar
    const progressBar = document.getElementById('progress-bar');
    if (progressBar) {
        progressBar.addEventListener('click', function(e) {
            const percent = e.offsetX / this.offsetWidth;
            seekToPosition(percent);
        });
    }
}

// Load and play a song
function loadSong(songId, platform, title, artist, thumbnail) {
    const player = document.getElementById('music-player');
    const nowPlaying = document.getElementById('now-playing');
    
    if (!player) return;
    
    currentSong = {
        id: songId,
        platform: platform,
        title: title,
        artist: artist,
        thumbnail: thumbnail
    };
    
    // Update player iframe based on platform
    if (platform === 'spotify') {
        player.innerHTML = `
            <iframe id="spotify-player" src="https://open.spotify.com/embed/track/${songId}" 
                width="100%" height="80" frameborder="0" allowtransparency="true" 
                allow="encrypted-media"></iframe>
        `;
    } else if (platform === 'youtube') {
        player.innerHTML = `
            <iframe id="youtube-player" width="100%" height="80" 
                src="https://www.youtube.com/embed/${songId}?enablejsapi=1&autoplay=1" 
                frameborder="0" allow="accelerometer; autoplay; clipboard-write; 
                encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
        `;
    }
    
    // Update now playing info
    if (nowPlaying) {
        nowPlaying.innerHTML = `
            <img src="${thumbnail}" alt="${title}" class="now-playing-thumb">
            <div class="now-playing-info">
                <h4>${title}</h4>
                <p>${artist}</p>
            </div>
        `;
    }
    
    isPlaying = true;
    updatePlayPauseButton();
    
    // Show player container
    const playerContainer = document.getElementById('player-container');
    if (playerContainer) {
        playerContainer.classList.add('active');
    }
}

// Toggle play/pause
function togglePlayPause() {
    if (!currentSong) return;
    
    isPlaying = !isPlaying;
    
    if (currentSong.platform === 'spotify') {
        const spotifyPlayer = document.getElementById('spotify-player');
        // Spotify Web Player API doesn't work in iframes without premium
        // This is just a placeholder for the functionality
    } else if (currentSong.platform === 'youtube') {
        const youtubePlayer = document.getElementById('youtube-player');
        if (youtubePlayer && youtubePlayer.contentWindow) {
            if (isPlaying) {
                youtubePlayer.contentWindow.postMessage('{"event":"command","func":"playVideo","args":""}', '*');
            } else {
                youtubePlayer.contentWindow.postMessage('{"event":"command","func":"pauseVideo","args":""}', '*');
            }
        }
    }
    
    updatePlayPauseButton();
}

// Update play/pause button icon
function updatePlayPauseButton() {
    const playPauseBtn = document.getElementById('play-pause-btn');
    if (!playPauseBtn) return;
    
    if (isPlaying) {
        playPauseBtn.innerHTML = '<i class="fas fa-pause"></i>';
    } else {
        playPauseBtn.innerHTML = '<i class="fas fa-play"></i>';
    }
}

// Set volume
function setVolume(value) {
    volume = value;
    
    if (!currentSong) return;
    
    if (currentSong.platform === 'youtube') {
        const youtubePlayer = document.getElementById('youtube-player');
        if (youtubePlayer && youtubePlayer.contentWindow) {
            youtubePlayer.contentWindow.postMessage(`{"event":"command","func":"setVolume","args":[${value * 100}]}`, '*');
        }
    }
}

// Seek to position
function seekToPosition(percent) {
    if (!currentSong) return;
    
    if (currentSong.platform === 'youtube') {
        const youtubePlayer = document.getElementById('youtube-player');
        if (youtubePlayer && youtubePlayer.contentWindow) {
            // First get duration, then seek
            youtubePlayer.contentWindow.postMessage('{"event":"command","func":"getDuration","args":""}', '*');
            // This is simplified - in a real app you'd need to handle the response
            // and then calculate the seek time based on duration * percent
        }
    }
}

// Update progress bar
function updateProgressBar(currentTime, duration) {
    const progressBar = document.getElementById('progress-bar');
    const progressFill = document.getElementById('progress-fill');
    const timeDisplay = document.getElementById('time-display');
    
    if (!progressBar || !progressFill || !timeDisplay) return;
    
    const percent = (currentTime / duration) * 100;
    progressFill.style.width = `${percent}%`;
    
    // Format time display
    const currentMinutes = Math.floor(currentTime / 60);
    const currentSeconds = Math.floor(currentTime % 60);
    const totalMinutes = Math.floor(duration / 60);
    const totalSeconds = Math.floor(duration % 60);
    
    timeDisplay.textContent = `${currentMinutes}:${currentSeconds < 10 ? '0' : ''}${currentSeconds} / ${totalMinutes}:${totalSeconds < 10 ? '0' : ''}${totalSeconds}`;
}