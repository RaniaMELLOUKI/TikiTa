<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>buy</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="buy.css">
</head>
<body>
<!-- Hero Section -->
<section class="hero-section">
    <h1 class="hero-title">Fans Know</h1>
    <p class="hero-subtitle">The best memories start with the best prices.</p>
    
    <div class="search-container">
        <div class="search-wrapper">
            <div class="d-flex w-100 gap-2">
                <input type="text" class="form-control search-input" id="searchInput" placeholder="Search by artist, team, event or venue">
                <select id="categoryFilter" class="form-select" style="max-width:220px">
                    <option value="">All Categories</option>
                </select>
                <button class="btn btn-primary search-btn" id="searchBtn">
                    <i class="bi bi-search"></i>
                </button>
            </div>
            <div id="searchResults" class="search-results-list"></div>
        </div>
    </div>
</section>

<!-- Available Events Section -->
<section class="events-section mt-4">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2 class="section-title">Available Events</h2>
            <small id="eventsCount" class="text-muted"></small>
        </div>

        <div id="eventsGrid" class="row g-3">
            <!-- Event cards will be injected here -->
        </div>
    </div>
</section>

<!-- Suggested Section -->
<section class="suggested-section">
    <div class="section-header">
        <h2 class="section-title">Suggested</h2>
        <div class="nav-arrows">
            <button class="arrow-btn" data-scroll="left">
                <i class="bi bi-chevron-left"></i>
            </button>
            <button class="arrow-btn" data-scroll="right">
                <i class="bi bi-chevron-right"></i>
            </button>
        </div>
    </div>

    <div class="artist-scroll" id="artistScroll">
        <div class="artist-card">
            <img src="https://images.unsplash.com/photo-1493225457124-a3eb161ffa5f?w=200&h=200&fit=crop" alt="Bad Bunny" class="artist-image">
            <div class="artist-info">
                <div class="artist-name">Bad Bunny</div>
                <div class="artist-meta">10 Events Nearby</div>
            </div>
            <i class="bi bi-chevron-right arrow-icon"></i>
        </div>

        <div class="artist-card">
            <img src="https://images.unsplash.com/photo-1516280440614-37939bbacd81?w=200&h=200&fit=crop" alt="Olivia Dean" class="artist-image">
            <div class="artist-info">
                <div class="artist-name">Olivia Dean</div>
                <div class="artist-meta">Trending</div>
            </div>
            <i class="bi bi-chevron-right arrow-icon"></i>
        </div>

        <div class="artist-card">
            <img src="https://images.unsplash.com/photo-1511367461989-f85a21fda167?w=200&h=200&fit=crop" alt="Playboi Carti" class="artist-image">
            <div class="artist-info">
                <div class="artist-name">Playboi Carti</div>
                <div class="artist-meta">Trending</div>
            </div>
            <i class="bi bi-chevron-right arrow-icon"></i>
        </div>

        <div class="artist-card">
            <img src="https://images.unsplash.com/photo-1493225457124-a3eb161ffa5f?w=200&h=200&fit=crop" alt="Gunna" class="artist-image">
            <div class="artist-info">
                <div class="artist-name">Gunna</div>
                <div class="artist-meta">Trending</div>
            </div>
            <i class="bi bi-chevron-right arrow-icon"></i>
        </div>

        <div class="artist-card">
            <img src="https://images.unsplash.com/photo-1516280440614-37939bbacd81?w=200&h=200&fit=crop" alt="Taylor Swift" class="artist-image">
            <div class="artist-info">
                <div class="artist-name">Taylor Swift</div>
                <div class="artist-meta">8 Events Nearby</div>
            </div>
            <i class="bi bi-chevron-right arrow-icon"></i>
        </div>
    </div>
</section>

<!-- Concerts Section -->
<section class="concerts-section">
    <div class="section-header">
        <h2 class="section-title">Concerts</h2>
        <div class="nav-arrows">
            <button class="arrow-btn">
                <i class="bi bi-chevron-left"></i>
            </button>
            <button class="arrow-btn">
                <i class="bi bi-chevron-right"></i>
            </button>
        </div>
    </div>

    <div class="featured-artist">
        <img src="https://images.unsplash.com/photo-1493225457124-a3eb161ffa5f?w=1400&h=400&fit=crop" alt="Bad Bunny" class="featured-image">
        <button class="favorite-btn">
            <i class="bi bi-heart"></i>
        </button>
        <div class="featured-info">
            <div class="featured-artist-name">Bad Bunny</div>
            <div class="featured-events">10 Events Nearby</div>
        </div>
        <button class="view-more-btn">
            <i class="bi bi-chevron-right"></i>
        </button>
    </div>

    <!-- Trending Performers -->
    <div class="trending-header">
        <h3 class="trending-title">Trending Performers</h3>
        <span class="trending-subtitle">Top Nationwide</span>
    </div>

    <div class="performers-grid">
        <div class="performer-card">
            <img src="https://images.unsplash.com/photo-1516280440614-37939bbacd81?w=600&h=400&fit=crop" alt="Performer" class="performer-image">
            <div class="performer-rank">#1</div>
            <button class="favorite-btn">
                <i class="bi bi-heart"></i>
            </button>
        </div>

        <div class="performer-card">
            <img src="https://images.unsplash.com/photo-1511367461989-f85a21fda167?w=600&h=400&fit=crop" alt="Performer" class="performer-image">
            <div class="performer-rank">#2</div>
            <button class="favorite-btn">
                <i class="bi bi-heart"></i>
            </button>
        </div>

        <div class="performer-card">
            <img src="https://images.unsplash.com/photo-1493225457124-a3eb161ffa5f?w=600&h=400&fit=crop" alt="Performer" class="performer-image">
            <div class="performer-rank">#3</div>
            <button class="favorite-btn">
                <i class="bi bi-heart"></i>
            </button>
        </div>

        <div class="performer-card">
            <img src="https://images.unsplash.com/photo-1516280440614-37939bbacd81?w=600&h=400&fit=crop" alt="Performer" class="performer-image">
            <div class="performer-rank">#4</div>
            <button class="favorite-btn">
                <i class="bi bi-heart"></i>
            </button>
        </div>
    </div>
</section>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="main.js"></script>

</body>
</html>