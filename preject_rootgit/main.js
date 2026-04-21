/**
 * =========================================
 * TiKiTa - Ticket Marketplace Main Script
 * =========================================
 * 
 * This script handles:
 *   1. Dynamic page loading via AJAX
 *   2. Navigation and UI interactions
 *   3. Buy page initialization with filtering and search
 *   4. Event card rendering and interactions
 */

// =============================================
// SECTION 1: PAGE LOADING & NAVIGATION
// =============================================

/**
 * Load a page fragment via AJAX and inject into the main content area
 * This implements a Single Page Application (SPA) pattern
 * 
 * @param {string} page - Page name to load (e.g., 'buy', 'sell')
 */
function loadPage(page) {
    const mainContent = document.getElementById('mainContent');
    if (!mainContent) return;

    // Add visual loading effect
    mainContent.style.opacity = '0.5';

    // Fetch the PHP fragment file
    fetch(page + '.php')
        .then(response => {
            if (!response.ok) throw new Error('Page not found');
            return response.text();
        })
        .then(html => {
            // Inject HTML into the page
            mainContent.innerHTML = html;
            mainContent.style.opacity = '1';

            // Initialize page-specific behavior after content is injected
            if (page === 'buy') {
                initBuyPage();
            }
            if (page === 'sell') {
                initSellPage();
            }

            // Update the active button in the navigation bar
            updateActiveButton(page);
        })
        .catch(error => {
            console.error('Error loading page:', error);
            if (mainContent) {
                mainContent.innerHTML = '<div class="sell-page"><div class="sell-welcome"><h1>Erreur</h1><p>Impossible de charger la page</p></div></div>';
                mainContent.style.opacity = '1';
            }
        });
}

/**
 * Update the active button state in the top navigation toggle bar
 * 
 * @param {string} page - Current page name
 */
function updateActiveButton(page) {
    const buttons = document.querySelectorAll('.toggle-option');
    buttons.forEach(button => {
        if (button.getAttribute('data-page') === page) {
            button.classList.add('active');
        } else {
            button.classList.remove('active');
        }
    });
}

// =============================================
// SECTION 2: BUY PAGE FUNCTIONALITY
// =============================================

/**
 * Initialize all interactive listeners for the Buy page
 * Handles scrolling, favorites, and other interactions
 */
function initBuyPageListeners() {
    // === HORIZONTAL SCROLL FUNCTIONALITY ===
    const artistScroll = document.getElementById('artistScroll');
    const scrollButtons = document.querySelectorAll('[data-scroll]');

    if (artistScroll && scrollButtons.length > 0) {
        scrollButtons.forEach(button => {
            button.addEventListener('click', function() {
                const direction = this.dataset.scroll;
                const scrollAmount = direction === 'left' ? -400 : 400;
                artistScroll.scrollBy({ left: scrollAmount, behavior: 'smooth' });
            });
        });
    }

    // === SECTION SCROLL CONTROLS (for different sections) ===
    document.querySelectorAll('.nav-arrows').forEach((navArrows, index) => {
        if (index > 0) {
            const scrollContainer = navArrows.parentElement.nextElementSibling;
            const leftBtn = navArrows.children[0];
            const rightBtn = navArrows.children[1];

            if (leftBtn && rightBtn && scrollContainer) {
                leftBtn.addEventListener('click', () => {
                    if (scrollContainer.scrollBy) {
                        scrollContainer.scrollBy({ left: -400, behavior: 'smooth' });
                    }
                });

                rightBtn.addEventListener('click', () => {
                    if (scrollContainer.scrollBy) {
                        scrollContainer.scrollBy({ left: 400, behavior: 'smooth' });
                    }
                });
            }
        }
    });

    // === FAVORITE BUTTON TOGGLE ===
    document.querySelectorAll('.favorite-btn').forEach(btn => {
        btn.addEventListener('click', (e) => {
            e.stopPropagation(); // Prevent event bubbling
            const icon = btn.querySelector('i');
            
            if (icon.classList.contains('bi-heart')) {
                // Change to filled heart (favorited)
                icon.classList.remove('bi-heart');
                icon.classList.add('bi-heart-fill');
                btn.style.color = '#e74c3c'; // Red color for filled heart
            } else {
                // Change to empty heart (unfavorited)
                icon.classList.remove('bi-heart-fill');
                icon.classList.add('bi-heart');
                btn.style.color = '#666'; // Gray color for empty heart
            }
        });
    });
}

/**
 * Initialize Buy page: Fetch and display events and categories
 * Handles search, filtering, and dynamic event rendering
 */
function initBuyPage() {
    // Initialize UI listeners (scrolls, favorite toggles)
    initBuyPageListeners();

    // API endpoints for fetching data
    // Use relative paths (no leading '/') so the API is requested
    // relative to the current site directory. Leading '/' requests
    // the server root which can cause 404 when the site is served
    // from a subfolder (e.g. /preject_rootgit/).
    const apiEvents = 'api/evenement.php';
    const apiCats = 'api/categorie.php';

    // Debug - show which endpoints will be requested
    console.debug('Buy page API endpoints:', { apiEvents, apiCats });

    // DOM elements on the buy page
    const searchInput = document.getElementById('searchInput');
    const searchBtn = document.getElementById('searchBtn');
    const categoryFilter = document.getElementById('categoryFilter');
    const eventsGrid = document.getElementById('eventsGrid');
    const eventsCount = document.getElementById('eventsCount');

    // Verify all required elements exist
    if (!eventsGrid || !eventsCount || !categoryFilter) {
        console.warn('Buy page elements missing, aborting buy init');
        return;
    }

    // Cache for category names (id -> name mapping)
    let categoriesMap = {};

    /**
     * Fetch all categories from the API and populate the filter dropdown
     */
    function fetchCategories() {
        fetch(apiCats)
            .then(r => {
                if (!r.ok) throw new Error('HTTP ' + r.status);
                return r.json();
            })
            .then(data => {
                // Validate response is an array
                if (!Array.isArray(data)) {
                    console.warn('Categories response is not an array:', data);
                    return;
                }
                
                // Populate dropdown and cache category names
                data.forEach(cat => {
                    const opt = document.createElement('option');
                    opt.value = cat.id_categorie;
                    opt.textContent = cat.nom;
                    categoryFilter.appendChild(opt);
                    categoriesMap[cat.id_categorie] = cat.nom;
                });
                console.log('Loaded ' + data.length + ' categories');
            })
            .catch(err => console.error('Failed to load categories', err));
    }

    /**
     * Fetch events from the API with optional filtering
     * @param {object} params - Query parameters (search, category, etc.)
     */
    function fetchEvents(params = {}) {
        let url = apiEvents;
        const queryParams = new URLSearchParams();
        
        // Build query string from parameters
        Object.keys(params).forEach(k => { 
            if (params[k] !== '' && params[k] != null) {
                queryParams.set(k, params[k]);
            }
        });
        
        const queryString = queryParams.toString();
        if (queryString) {
            url += '?' + queryString;
        }

        fetch(url)
            .then(r => {
                if (!r.ok) throw new Error('HTTP ' + r.status);
                return r.json();
            })
            .then(data => renderEvents(data))
            .catch(err => {
                console.error('Failed to load events', err);
                if (eventsGrid) {
                    eventsGrid.innerHTML = '<div class="col-12">Failed to load events: ' + err.message + '</div>';
                }
            });
    }

    /**
     * Render event cards in the events grid
     * Creates interactive event cards with images, details, and action buttons
     * @param {array} events - Array of event objects to render
     */
    function renderEvents(events) {
        eventsGrid.innerHTML = '';
        
        // Normalize events to array
        if (!events) events = [];
        if (!Array.isArray(events)) events = [events];
        
        // Update event count display
        eventsCount.textContent = events.length + ' events';
        
        // Show "no results" message if empty
        if (events.length === 0) {
            eventsGrid.innerHTML = '<div class="col-12">No events found.</div>';
            return;
        }

        // Render each event as a card
        events.forEach(ev => {
            // Create column container (responsive grid)
            const col = document.createElement('div');
            col.className = 'col-12 col-sm-6 col-md-4 col-lg-3';

            // Create card element
            const card = document.createElement('div');
            card.className = 'card h-100 event-card';
            card.style.cssText = `
                border: 1px solid #e0e0e0;
                transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
                cursor: pointer;
                position: relative;
                overflow: hidden;
            `;

            // Create and set event image
            const img = document.createElement('img');
            img.className = 'card-img-top';
            img.alt = ev.titre || 'Event image';
            // Use base64 encoded photo from database or placeholder
            img.src = ev.photo ? 'data:image/jpeg;base64,' + ev.photo : 'https://via.placeholder.com/400x200?text=Event';
            img.style.cssText = `
                transition: transform 0.3s ease-out;
                object-fit: cover;
            `;

            // Create card body container
            const body = document.createElement('div');
            body.className = 'card-body d-flex flex-column';
            body.style.cssText = `
                transition: background-color 0.3s;
            `;

            // Event title
            const title = document.createElement('h5');
            title.className = 'card-title mb-1';
            title.textContent = ev.titre || 'Untitled Event';
            title.style.cssText = `
                transition: color 0.3s;
            `;

            // Event date and location
            const date = document.createElement('p');
            date.className = 'card-text text-muted mb-1 small';
            date.textContent = (ev.date_event ? new Date(ev.date_event).toLocaleDateString() : '') + ' • ' + (ev.lieu || '');

            // Event category
            const cat = document.createElement('p');
            cat.className = 'card-text mb-2 small';
            cat.textContent = categoriesMap[ev.id_categorie] ? categoriesMap[ev.id_categorie] : '';

            // Capacity information
            const capacity = document.createElement('p');
            capacity.className = 'card-text mt-auto small text-muted';
            capacity.textContent = 'Capacity: ' + (ev.nb_max_part || 0);

            // Create footer with action buttons
            const footer = document.createElement('div');
            footer.className = 'mt-2 d-flex justify-content-between align-items-center';
            
            const viewBtn = document.createElement('a');
            viewBtn.href = 'event.php?id=' + (ev.id_event || '');
            viewBtn.className = 'btn btn-outline-primary btn-sm';
            viewBtn.textContent = 'View';

            const buyBtn = document.createElement('a');
            buyBtn.href = 'buy.php?event=' + (ev.id_event || '');
            buyBtn.className = 'btn btn-primary btn-sm';
            buyBtn.textContent = 'Buy';

            footer.appendChild(viewBtn);
            footer.appendChild(buyBtn);

            // Assemble card structure
            body.appendChild(title);
            body.appendChild(date);
            body.appendChild(cat);
            body.appendChild(capacity);
            body.appendChild(footer);

            card.appendChild(img);
            card.appendChild(body);
            col.appendChild(card);
            eventsGrid.appendChild(col);

            // Add interactive effects
            card.addEventListener('mouseenter', function() {
                this.style.cursor = 'pointer';
            });

            card.addEventListener('mouseleave', function() {
                this.style.cursor = 'default';
            });

            // Make entire card clickable to view event details
            card.addEventListener('click', function(e) {
                if (!e.target.classList.contains('btn')) {
                    window.location.href = viewBtn.href;
                }
            });
        });
    }

    /**
     * Debounce utility function
     * Delays function execution until user stops triggering the event
     * Prevents excessive API calls during typing
     * @param {function} fn - Function to debounce
     * @param {number} wait - Delay in milliseconds
     * @return {function} Debounced function
     */
    function debounce(fn, wait) {
        let t;
        return function(...args) {
            clearTimeout(t);
            t = setTimeout(() => fn.apply(this, args), wait);
        }
    }

    /**
     * Execute search with debouncing
     * Triggered by search input, category filter change, or search button click
     */
    const doSearch = debounce(() => {
        const q = searchInput ? searchInput.value.trim() : '';
        const cat = categoryFilter ? categoryFilter.value : '';
        const params = {};
        
        if (q) params.search = q;
        if (cat) params.category = cat;
        
        fetchEvents(params);
    }, 300); // 300ms delay after user stops typing

    // === ATTACH EVENT LISTENERS ===
    if (searchInput) searchInput.addEventListener('input', doSearch);
    if (searchBtn) searchBtn.addEventListener('click', () => doSearch());
    if (categoryFilter) categoryFilter.addEventListener('change', () => doSearch());

    // === INITIAL DATA LOAD ===
    fetchCategories();
    fetchEvents();
}

/**
 * Initialize Sell page
 * Placeholder for future sell page functionality
 */
function initSellPage() {
    console.log('Sell page initialized');
    // Sell page specific initialization goes here
}

// =============================================
// SECTION 3: SIDEBAR TOGGLE FUNCTIONALITY
// =============================================

const sidebar = document.getElementById('sidebar');
const sidebarToggle = document.getElementById('sidebarToggle');
const toggleIcon = sidebarToggle.querySelector('i');

/**
 * Toggle sidebar expand/collapse on button click
 */
sidebarToggle.addEventListener('click', function() {
    sidebar.classList.toggle('expanded');
    
    if (sidebar.classList.contains('expanded')) {
        toggleIcon.classList.remove('bi-chevron-right');
        toggleIcon.classList.add('bi-chevron-left');
    } else {
        toggleIcon.classList.remove('bi-chevron-left');
        toggleIcon.classList.add('bi-chevron-right');
    }
});

/**
 * Auto-expand sidebar on mouse enter
 */
sidebar.addEventListener('mouseenter', function() {
    if (!sidebar.classList.contains('expanded')) {
        sidebar.classList.add('expanded');
        toggleIcon.classList.remove('bi-chevron-right');
        toggleIcon.classList.add('bi-chevron-left');
    }
});

/**
 * Auto-collapse sidebar on mouse leave
 */
sidebar.addEventListener('mouseleave', function() {
    if (sidebar.classList.contains('expanded')) {
        sidebar.classList.remove('expanded');
        toggleIcon.classList.remove('bi-chevron-left');
        toggleIcon.classList.add('bi-chevron-right');
    }
});

// =============================================
// SECTION 4: NAVIGATION BAR SETUP
// =============================================

/**
 * Attach click handlers to all toggle buttons in the navigation bar
 * Allows users to switch between pages
 */
document.querySelectorAll('.toggle-option').forEach(button => {
    button.addEventListener('click', function() {
        const page = this.getAttribute('data-page');
        loadPage(page);
    });
});

/**
 * Load the default (Buy) page when the document is ready
 */
document.addEventListener('DOMContentLoaded', function() {
    loadPage('buy'); // Load Buy page by default
});