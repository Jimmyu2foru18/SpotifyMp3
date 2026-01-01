# SpotifyMP3 - Music Management Web Platform

## Overview

**SpotifyMP3** is a web application for discovering, saving, sharing, and managing music from both **Spotify** and **YouTube**. The platform allows users to search for music across both services, create personalized playlists, save favorite songs, and engage with other users through ratings and reviews.

This full-stack project combines frontend interactivity with backend logic and database storage to deliver a rich music exploration experience.

---

## Features

### Music Discovery
- Search Spotify tracks and YouTube videos  
- Display song metadata including title, artist, album, and artwork  
- Embedded Spotify and YouTube players  
- Randomized playlists for homepage discovery  

### User Interaction
- Save songs from Spotify and YouTube  
- Create, edit, and delete playlists  
- Leave ratings and reviews for tracks  
- Song of the Day feature with automatic daily rotation  

### Account & Profiles
- User registration and login system  
- Profile management with bio and stats  
- Stored search history and activity tracking  

### Social & Community
- Infinite scroll for reviews  
- Like, reply, and sort review feeds  
- Filter reviews by platform or popularity  

### Admin Dashboard
- Manage users, playlists, and reviews  
- Content moderation tools  
- Message inbox with reply and archive capabilities  
- Site analytics and activity graphs  

---

## Tech Stack

- **Frontend**: HTML5, CSS3, JavaScript  
- **Backend**: PHP  
- **Database**: MySQL (managed via phpMyAdmin)  
- **APIs**: Spotify Web API, YouTube Data API v3  

---

## Installation & Setup

### Prerequisites

To run this project locally, you will need:

- Web server (Apache or Nginx)  
- PHP 7 or higher  
- MySQL or MariaDB  
- Spotify Developer account (for API credentials)  
- Google Developer account (for YouTube API keys)

---

## Installation Steps

1. **Clone the repository into your web server directory:**

~~~bash
git clone https://github.com/Jimmyu2foru18/SpotifyMp3.git
~~~

2. **Import the database:**

- Use `database/spotifymp3.sql` to create the necessary tables and seed data.

3. **Configure your database connection:**

- Update `includes/db_connect.php` with your database credentials.

4. **Add your API keys:**

- Edit `includes/functions.php` to set your Spotify and YouTube API credentials.

5. **Set file permissions:**

- Ensure proper read/write permissions for uploads and logs if required.

6. **Open the app in your browser:**

- Navigate to your local server address (e.g., `http://localhost/SpotifyMp3/`).

---

## Page Structure & Features

### 1. **Landing Page**
- Displays a selection of songs from Spotify and YouTube  
- Each card includes artwork, title, artist, and action buttons  
- Users can save tracks, add to playlists, or leave a review  

### 2. **About Page**
- Contains site overview, FAQs, API usage description, and contact info  

### 3. **Song of the Day**
- Highlights a featured track each day  
- Includes a larger display and related songs section  

### 4. **Saved Songs & Playlists**
- Show user-saved songs and user-created playlists  
- Playlist editor supports drag-and-drop reordering  

### 5. **Reviews**
- Infinite scroll feed of user reviews  
- Sort and filter by platform or popularity  

### 6. **Profile Page**
- Displays user stats, saved songs, playlist list, and activity  

---

## Database Structure (MySQL)

SpotifyMP3 uses the following key tables:

- `users` – stores user accounts and profile info  
- `saved_songs` – tracks saved from Spotify/YouTube  
- `playlists` – user playlists and metadata  
- `playlist_songs` – songs within playlists  
- `reviews` – user reviews and ratings  
- `search_history` – user Spotify/YouTube search logs  
- `admin_messages` – user messages to admin

---

## API Integration

- **Spotify Web API**  
  *OAuth 2.0 authentication*, fetch track metadata, search, embed player.  
- **YouTube Data API v3**  
  Fetch video metadata, search results, and embed video player.

---
