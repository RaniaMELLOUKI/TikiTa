<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TiKiTa - No Fee Tickets</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="index.css">
</head>
<body>
    <!-- Top Navigation -->
    <nav class="top-nav">
        <a href="index.php" class="logo">
            <div class="logo-icon">T</div>
            <span>TiKiTa</span>
        </a>
        
        <div class="toggle-bar">
            <button class="toggle-option active" data-page="buy">Buy</button>
            <button class="toggle-option" data-page="sell">Sell</button>
        </div>
        
        <div class="nav-right">
            <a href="#" class="sign-up-link">Sign Up</a>
            <button class="log-in-btn">Log In</button>
        </div>
    </nav>

    <!-- Left Sidebar (Expandable) -->
    <div class="left-sidebar" id="sidebar">
        <a href="#" class="sidebar-item">
            <div class="sidebar-icon">
                <i class="bi bi-dribbble"></i>
            </div>
            <span class="sidebar-label">Sports</span>
        </a>
        <a href="#" class="sidebar-item">
            <div class="sidebar-icon">
                <i class="bi bi-tv"></i>
            </div>
            <span class="sidebar-label">NBA</span>
        </a>
        <a href="#" class="sidebar-item">
            <div class="sidebar-icon">
                <i class="bi bi-music-note-beamed"></i>
            </div>
            <span class="sidebar-label">Concerts</span>
        </a>
        <a href="#" class="sidebar-item">
            <div class="sidebar-icon">
                <i class="bi bi-mask"></i>
            </div>
            <span class="sidebar-label">Theater</span>
        </a>
        <a href="#" class="sidebar-item">
            <div class="sidebar-icon">
                <i class="bi bi-trophy"></i>
            </div>
            <span class="sidebar-label">NFL</span>
        </a>
        <a href="#" class="sidebar-item">
            <div class="sidebar-icon">
                <i class="bi bi-mic-fill"></i>
            </div>
            <span class="sidebar-label">Comedy</span>
        </a>
        <div class="sidebar-toggle" id="sidebarToggle">
            <i class="bi bi-chevron-right"></i>
        </div>
    </div>

    <!-- Main Content Area -->
    <div id="mainContent">
        <!-- Le contenu sera chargé ici dynamiquement -->
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="main.js"></script>
</body>
</html>