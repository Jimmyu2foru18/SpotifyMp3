// SpotifyMP3 Search Functionality

document.addEventListener('DOMContentLoaded', function() {
    const spotifySearchForm = document.getElementById('spotify-search-form');
    const youtubeSearchForm = document.getElementById('youtube-search-form');
    
    if (spotifySearchForm) {
        spotifySearchForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const query = document.getElementById('spotify-search-input').value;
            searchSpotify(query);
        });
    }
    
    if (youtubeSearchForm) {
        youtubeSearchForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const query = document.getElementById('youtube-search-input').value;
            searchYouTube(query);
        });
    }
});

// Search Spotify API
function searchSpotify(query) {
    const resultsContainer = document.getElementById('spotify-results');
    resultsContainer.innerHTML = '<div class="loading">Searching Spotify...</div>';
    
    fetch(`../backend/spotify_search.php?q=${encodeURIComponent(query)}`)
        .then(response => response.json())
        .then(data => {
            if (data.success && data.tracks.length > 0) {
                displaySpotifyResults(data.tracks, resultsContainer);
            } else {
                resultsContainer.innerHTML = '<div class="no-results">No results found</div>';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            resultsContainer.innerHTML = '<div class="error">An error occurred</div>';
        });
}

// Search YouTube API
function searchYouTube(query) {
    const resultsContainer = document.getElementById('youtube-results');
    resultsContainer.innerHTML = '<div class="loading">Searching YouTube...</div>';
    
    fetch(`../backend/youtube_search.php?q=${encodeURIComponent(query)}`)
        .then(response => response.json())
        .then(data => {
            if (data.success && data.videos.length > 0) {
                displayYouTubeResults(data.videos, resultsContainer);
            } else {
                resultsContainer.innerHTML = '<div class="no-results">No results found</div>';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            resultsContainer.innerHTML = '<div class="error">An error occurred</div>';
        });
}

// Display Spotify search results
function displaySpotifyResults(tracks, container) {
    container.innerHTML = '';
    
    const resultsGrid = document.createElement('div');
    resultsGrid.className = 'songs-grid';
    
    tracks.forEach(track => {
        const songCard = document.createElement('div');
        songCard.className = 'song-card';
        songCard.innerHTML = `
            <img src="${track.album.images[0].url}" alt="${track.name}" class="song-thumb">
            <div class="song-info">
                <h3 class="song-title">${track.name}</h3>
                <p class="song-artist">${track.artists[0].name}</p>
                <span class="song-platform platform-spotify">Spotify</span>
                <div class="song-actions">
                    <button class="action-btn play-btn" data-song-id="${track.id}" data-platform="spotify">
                        <i class="fas fa-play"></i>
                    </button>
                    <button class="action-btn save-btn" data-song-id="${track.id}" data-platform="spotify">
                        <i class="fas fa-heart"></i>
                    </button>
                    <button class="action-btn add-to-playlist-btn" data-song-id="${track.id}" data-platform="spotify">
                        <i class="fas fa-plus"></i>
                    </button>
                    <button class="action-btn review-btn" data-song-id="${track.id}" data-platform="spotify">
                        <i class="fas fa-comment"></i>
                    </button>
                </div>
            </div>
        `;
        
        resultsGrid.appendChild(songCard);
    });
    
    container.appendChild(resultsGrid);
    
    // Initialize player controls for new results
    initPlayerControls();
}

// Display YouTube search results
function displayYouTubeResults(videos, container) {
    container.innerHTML = '';
    
    const resultsGrid = document.createElement('div');
    resultsGrid.className = 'songs-grid';
    
    videos.forEach(video => {
        const songCard = document.createElement('div');
        songCard.className = 'song-card';
        songCard.innerHTML = `
            <img src="${video.thumbnail}" alt="${video.title}" class="song-thumb">
            <div class="song-info">
                <h3 class="song-title">${video.title}</h3>
                <p class="song-artist">${video.channelTitle}</p>
                <span class="song-platform platform-youtube">YouTube</span>
                <div class="song-actions">
                    <button class="action-btn play-btn" data-song-id="${video.id}" data-platform="youtube">
                        <i class="fas fa-play"></i>
                    </button>
                    <button class="action-btn save-btn" data-song-id="${video.id}" data-platform="youtube">
                        <i class="fas fa-heart"></i>
                    </button>
                    <button class="action-btn add-to-playlist-btn" data-song-id="${video.id}" data-platform="youtube">
                        <i class="fas fa-plus"></i>
                    </button>
                    <button class="action-btn review-btn" data-song-id="${video.id}" data-platform="youtube">
                        <i class="fas fa-comment"></i>
                    </button>
                </div>
            </div>
        `;
        
        resultsGrid.appendChild(songCard);
    });
    
    container.appendChild(resultsGrid);
    
    // Initialize player controls for new results
    initPlayerControls();
}