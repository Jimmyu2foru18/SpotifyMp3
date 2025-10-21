# SpotifyMP3 - Music Management Web Platform

## Project Overview
A comprehensive web application for discovering, saving, sharing, and managing music from Spotify and YouTube with social playlist features and user reviews.

## Technology Stack
- **Frontend:** HTML5, CSS3, JavaScript
- **Backend:** PHP
- **Database:** MySQL (managed via phpMyAdmin)
- **APIs:** Spotify Web API, YouTube Data API v3

---

## User System & Authentication

### User Roles
- **Admin Account (Default)**
  - Username: `admin`
  - Password: `pass123`
  - Full CRUD access to all content
  - Message management system

- **Standard Users**
  - Registration with email verification
  - Profile creation and management
  - Personal content management

### Authentication Features
- Secure login/logout system
- Session management
- Password encryption (bcrypt/hashing)
- Remember me functionality
- Password reset capability

---

## Page Structure & Features

### 1. Landing Page (Home)
**Spotify Section:**
- 12 randomly selected Spotify tracks displayed as interactive cards/GUIs
- Each card includes:
  - Album artwork
  - Song title and artist
  - Play/pause button (embedded player)
  - "Save Song" button
  - "Add to Playlist" dropdown
  - "Leave Review" button
- Random song rotation on page refresh

**YouTube Section:**
- 12 randomly selected YouTube music videos displayed as cards/GUIs
- Each card includes:
  - Video thumbnail
  - Video title and channel name
  - Play button (embedded YouTube player)
  - "Save Song" button
  - "Add to Playlist" dropdown
  - "Leave Review" button
- Random video rotation on page refresh

### 2. About Page
**Content Sections:**
- Welcome message and platform overview
- Step-by-step user guide
  - How to create an account
  - How to search for music
  - How to create and manage playlists
  - How to save songs
  - How to leave reviews
- FAQ section
- Troubleshooting common issues
- API integration explanation
- Terms of service
- Privacy policy
- Contact information

### 3. Song of the Day Page
**Layout:**
- **Featured Section (Top):**
  - One large, prominently displayed song card from hardcoded Spotify playlist
  - Automatically rotates daily (server-side logic)
  - Full playback controls
  - Enhanced visual design with gradient backgrounds
  
- **More Picks Section (Bottom):**
  - 8 smaller song cards from the same playlist
  - Randomly selected from remaining playlist songs
  - Standard interaction buttons (save, add to playlist, review)
  - Grid layout (4x2 or responsive)

### 4. Saved Songs & Playlists Page
**Saved Songs Section:**
- Display all user-saved songs from Spotify and YouTube
- Sortable by:
  - Date added
  - Song title
  - Artist/channel name
  - Platform (Spotify/YouTube)
- Bulk actions (delete, move to playlist)

**Playlists Section:**
- Create new playlists with custom names and descriptions
- Display all user-created playlists as cards
- Each playlist card shows:
  - Playlist name
  - Song count
  - Thumbnail (first 4 songs or custom image)
  - Edit/Delete buttons
  
**Playlist Editor:**
- Drag-and-drop song reordering
- Remove songs from playlist
- Add/edit playlist description
- Set playlist privacy (public/private)
- Share playlist functionality
- Export playlist option

### 5. Reviews Page
**Social Feed Layout:**
- Infinite scroll feed (Facebook/Twitter-style)
- Each review post displays:
  - User profile picture and username
  - Song information (title, artist, album art)
  - Platform indicator (Spotify/YouTube badge)
  - Star rating (1-5 stars)
  - Written review text
  - Timestamp
  - Like/helpful counter
  - Reply/comment functionality

**Filtering Options:**
- Filter by platform (Spotify/YouTube/Both)
- Sort by newest/oldest/most helpful
- Search reviews by song title or username

### 6. Search Page
**Split Layout (Vertical Division):**

**Left Side - YouTube Search:**
- Search bar with autocomplete
- Search results display:
  - Video thumbnail
  - Title and channel
  - Duration and view count
  - "Save Song" button
  - "Add to Playlist" dropdown
  - "Leave Review" button
- Pagination for results (20 per page)

**Right Side - Spotify Search:**
- Search bar with autocomplete
- Search results display:
  - Album artwork
  - Song title and artist
  - Album name
  - Duration
  - "Save Song" button
  - "Add to Playlist" dropdown
  - "Leave Review" button
- Pagination for results (20 per page)

**Search History:**
- Display recent searches for each platform
- Clear search history option

### 7. Profile Page
**Profile Header:**
- Profile picture (upload/edit functionality)
- Username
- Email address
- Member since date
- Bio/description (editable text area, 250 character limit)
- "Edit Profile" button

**Statistics Dashboard:**
- Total saved songs
- Total playlists created
- Total reviews written
- Account activity graph

**Quick Access Sections:**
- **Saved Songs Button:** Links to full saved songs page with count badge
- **My Playlists Button:** Links to full playlists page with count badge
- **Search History Button:** Expandable section showing recent searches
- **My Reviews Button:** Links to user's review history

**Profile Management:**
- Change password
- Update email address
- Upload/change profile picture
- Edit bio
- Delete account option

**Contact Admin Button:**
- Small, accessible button (bottom of page or sidebar)
- Opens modal/form to send message to admin
- Message includes:
  - Subject line
  - Message body
  - Attachment option (optional)
  - User info auto-populated

---

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
- Social media links (optional)
- Copyright information

---

## Admin Panel Features

### Admin Dashboard
- User management
  - View all registered users
  - Edit user information
  - Delete user accounts
  - Ban/suspend users

### Message Management System
- Inbox for user-submitted contact messages
- Message details:
  - Sender username and email
  - Subject and message content
  - Timestamp
  - Mark as read/unread
  - Reply functionality (sends email to user)
  - Archive/delete messages

### Content Moderation
- Review management:
  - Flag inappropriate reviews
  - Delete reviews
  - Edit reviews if necessary
- Playlist moderation (public playlists)

### Site Analytics
- Total users registered
- Total songs saved
- Total playlists created
- Total reviews submitted
- Daily/weekly/monthly activity graphs

---

## Database Structure (MySQL)

### Tables Required

**users**
- user_id (PRIMARY KEY, AUTO_INCREMENT)
- username (UNIQUE)
- email (UNIQUE)
- password_hash
- role (admin/user)
- profile_picture
- bio
- created_at
- last_login

**saved_songs**
- saved_id (PRIMARY KEY, AUTO_INCREMENT)
- user_id (FOREIGN KEY)
- platform (spotify/youtube)
- song_id (API ID)
- song_title
- artist_name
- thumbnail_url
- saved_at

**playlists**
- playlist_id (PRIMARY KEY, AUTO_INCREMENT)
- user_id (FOREIGN KEY)
- playlist_name
- description
- is_public (BOOLEAN)
- created_at
- updated_at

**playlist_songs**
- id (PRIMARY KEY, AUTO_INCREMENT)
- playlist_id (FOREIGN KEY)
- saved_song_id (FOREIGN KEY)
- song_order
- added_at

**reviews**
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

**search_history**
- search_id (PRIMARY KEY, AUTO_INCREMENT)
- user_id (FOREIGN KEY)
- platform (spotify/youtube)
- search_query
- searched_at

**admin_messages**
- message_id (PRIMARY KEY, AUTO_INCREMENT)
- user_id (FOREIGN KEY)
- subject
- message_body
- is_read (BOOLEAN)
- created_at
- admin_response

---

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

---

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

---

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