# SpotifyMP3 - Music Management Web Platform

## Project Overview
SpotifyMP3 is a comprehensive web application for discovering, saving, sharing, and managing music from Spotify and YouTube with social playlist features and user reviews. The platform allows users to search for music across both platforms, create personalized playlists, save favorite songs, and engage with other users through reviews.

## Technology Stack
- **Frontend:** HTML5, CSS3, JavaScript
- **Backend:** PHP
- **Database:** MySQL (managed via phpMyAdmin)
- **APIs:** Spotify Web API, YouTube Data API v3

## Installation & Setup

### Prerequisites
- HTML 5
- CSS 3
- PHP 7
- MySQL 5
- Web server (Apache/Nginx)
- Spotify Developer Account (for API keys)
- Google Developer Account (for YouTube API keys)

### Installation Steps
1. Clone the repository to your web server directory
2. Import the database schema from `database/spotifymp3.sql`
3. Configure database connection in `includes/db_connect.php`
4. Set up API credentials in `includes/functions.php`
5. Ensure proper file permissions
6. Access the application through your web browser

## User System & Authentication

### User Roles

#### Admin Account (Default)
- **Username:** admin
- **Password:** pass123
- Full CRUD access to all content
- Message management system
- User management capabilities

#### Standard Users
- Registration with email verification
- Profile creation and management
- Personal content management
- Playlist creation and sharing

### Authentication Features
- Secure login/logout system
- Session management
- Password encryption (bcrypt/hashing)
- Remember me functionality
- Password reset capability

## Page Structure & Features

### 1. Landing Page (Home)
- **Spotify Section:**
  - 12 randomly selected Spotify tracks displayed as interactive cards
  - Each card includes album artwork, song title, artist, and interactive buttons
  - Play/pause functionality with embedded player
  - "Save Song", "Add to Playlist", and "Leave Review" options
  - Random song rotation on page refresh

- **YouTube Section:**
  - 12 randomly selected YouTube music videos displayed as cards
  - Each card includes video thumbnail, title, channel name, and interactive buttons
  - Embedded YouTube player
  - "Save Song", "Add to Playlist", and "Leave Review" options
  - Random video rotation on page refresh

### 2. About Page
- **Content Sections:**
  - Welcome message and platform overview
  - Step-by-step user guide
  - FAQ section
  - Troubleshooting common issues
  - API integration explanation
  - Terms of service
  - Privacy policy
  - Contact information

### 3. Song of the Day Page
- **Featured Section (Top):**
  - One large, prominently displayed song card from hardcoded Spotify playlist
  - Automatically rotates daily (server-side logic)
  - Full playback controls
  - Enhanced visual design with gradient backgrounds

- **More Picks Section (Bottom):**
  - 8 smaller song cards from the same playlist
  - Randomly selected from remaining playlist songs
  - Standard interaction buttons (save, add to playlist, review)
  - Responsive grid layout

### 4. Saved Songs & Playlists Page
- **Saved Songs Section:**
  - Display all user-saved songs from Spotify and YouTube
  - Sortable by date added, song title, artist/channel name, platform
  - Bulk actions (delete, move to playlist)

- **Playlists Section:**
  - Create new playlists with custom names and descriptions
  - Display all user-created playlists as cards
  - Each playlist card shows name, song count, thumbnail, and edit/delete buttons
  - Playlist editor with drag-and-drop reordering
  - Privacy settings and sharing functionality

### 5. Reviews Page
- **Social Feed Layout:**
  - Infinite scroll feed
  - Each review post displays user info, song details, rating, and review text
  - Like/helpful counter
  - Reply/comment functionality

- **Filtering Options:**
  - Filter by platform (Spotify/YouTube/Both)
  - Sort by newest/oldest/most helpful
  - Search reviews by song title or username

### 6. Search Page
- **Split Layout (Vertical Division):**
  - **YouTube Search:**
    - Search bar with autocomplete
    - Video results with thumbnails, title, channel, duration, and view count
    - Interactive buttons for saving, playlists, and reviews
    - Pagination (20 results per page)

  - **Spotify Search:**
    - Search bar with autocomplete
    - Song results with album artwork, title, artist, album, and duration
    - Interactive buttons for saving, playlists, and reviews
    - Pagination (20 results per page)

  - **Search History:**
    - Display recent searches for each platform
    - Clear search history option

### 7. Profile Page
- **Profile Header:**
  - Profile picture with upload/edit functionality
  - Username and email address
  - Member since date
  - Bio/description (editable, 250 character limit)
  - "Edit Profile" button

- **Statistics Dashboard:**
  - Total saved songs, playlists created, and reviews written
  - Account activity graph

- **Quick Access Sections:**
  - Saved Songs, My Playlists, Search History, and My Reviews buttons
  - Profile management options (password, email, profile picture, bio)
  - Delete account option

- **Contact Admin:**
  - Form to send messages to admin
  - Subject line, message body, and attachment option
  - User info auto-populated

## Navigation System

### Header Navigation
- Logo (links to landing page)
- Home
- Song of the Day
- Search
- Saved & Playlists
- Reviews
- About
- Profile icon/name (logged in) or Login button

### Footer Navigation
- About
- Contact Admin
- Privacy Policy
- Terms of Service
- Social media links
- Copyright information

## Admin Panel Features

### Admin Dashboard
- **User Management:**
  - View all registered users
  - Edit user information
  - Delete user accounts
  - Ban/suspend users

- **Message Management System:**
  - Inbox for user-submitted contact messages
  - Message details (sender, subject, content, timestamp)
  - Mark as read/unread
  - Reply functionality
  - Archive/delete messages

- **Content Moderation:**
  - Review management:
    - Flag inappropriate reviews
    - Delete reviews
    - Edit reviews if necessary
  - Playlist moderation for public playlists

- **Site Analytics:**
  - Total users registered
  - Total songs saved
  - Total playlists created
  - Total reviews submitted
  - Daily/weekly/monthly activity graphs

## Database Structure (MySQL)

### Tables Required

#### users
- user_id (PRIMARY KEY, AUTO_INCREMENT)
- username (UNIQUE)
- email (UNIQUE)
- password_hash
- role (admin/user)
- profile_picture
- bio
- created_at
- last_login

#### saved_songs
- saved_id (PRIMARY KEY, AUTO_INCREMENT)
- user_id (FOREIGN KEY)
- platform (spotify/youtube)
- song_id (API ID)
- song_title
- artist_name
- thumbnail_url
- saved_at

#### playlists
- playlist_id (PRIMARY KEY, AUTO_INCREMENT)
- user_id (FOREIGN KEY)
- playlist_name
- description
- is_public (BOOLEAN)
- created_at
- updated_at

#### playlist_songs
- id (PRIMARY KEY, AUTO_INCREMENT)
- playlist_id (FOREIGN KEY)
- saved_song_id (FOREIGN KEY)
- song_order
- added_at

#### reviews
- review_id (PRIMARY KEY, AUTO_INCREMENT)
- user_id (FOREIGN KEY)
- platform (spotify/youtube)
- song_id (API ID)
- song_title
- artist_name
- rating (1-5)
- review_text
- helpful_count
- created_at

#### search_history
- search_id (PRIMARY KEY, AUTO_INCREMENT)
- user_id (FOREIGN KEY)
- platform (spotify/youtube)
- search_query
- searched_at

#### admin_messages
- message_id (PRIMARY KEY, AUTO_INCREMENT)
- user_id (FOREIGN KEY)
- subject
- message_body
- is_read (BOOLEAN)
- created_at
- admin_response

## CRUD Operations

### Create
- New user accounts
- Playlists with custom names
- Reviews for songs
- Saved songs
- Admin messages

### Read
- Display all saved songs
- View playlists and contents
- Read reviews feed
- View search history
- Check profile information

### Update
- Edit profile information (bio, picture, password)
- Modify playlist details (name, description, songs)
- Reorder songs within playlists
- Update review ratings/text
- Change account settings

### Delete
- Remove saved songs
- Delete entire playlists
- Remove songs from playlists
- Delete reviews
- Clear search history
- Delete user account
- Admin: remove users, reviews, or messages

## API Integration

### Spotify Web API
- Authentication (OAuth 2.0)
- Fetch random tracks for landing page
- Search functionality
- Fetch hardcoded playlist for Song of the Day
- Retrieve track metadata (artwork, artist, duration)
- Embedded playback (Spotify Player SDK)

### YouTube Data API v3
- API key authentication
- Fetch random music videos for landing page
- Search functionality
- Retrieve video metadata (thumbnail, channel, duration)
- Embedded video player (iframe)

## Additional Features

### Responsive Design
- Mobile-friendly layouts
- Touch-optimized controls
- Collapsible navigation menu
- Adaptive grid systems

### Security Measures
- SQL injection prevention (prepared statements)
- XSS protection
- CSRF tokens for forms
- Secure session management
- Rate limiting on API calls
- Input validation and sanitization

### Performance Optimization
- Lazy loading images
- Pagination for large datasets
- Caching API responses
- Minified CSS/JS files
- Optimized database queries with indexes

### User Experience
- Toast notifications for actions (saved, deleted, etc.)
- Loading spinners during API calls
- Smooth transitions and animations
- Error handling with user-friendly messages
- Form validation with real-time feedback

## Form Validation
- Real-time form validation with immediate feedback
- Client-side validation for all forms:
  - Registration and login forms
  - Playlist creation
  - Review submission
  - Profile updates
  - Contact forms
- Visual indicators for valid/invalid inputs
- Helpful error messages

## Security Features
- CSRF protection
- Input sanitization
- Prepared SQL statements
- Session security
- Password hashing

"# Spotify-mp3-website-Type-2" 
