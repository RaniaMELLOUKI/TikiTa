<!-- 
    Sell Page - Event Creation Form
    Purpose: Allow users to create and list events for selling tickets
    This page allows users to create new events that will be listed on the Buy page
-->

<div class="sell-page">
    <!-- Welcome Section -->
    <div class="sell-welcome" style="padding: 3rem 2rem; text-align: center; background: linear-gradient(to bottom, #ffffff 0%, #f5f5f5 100%);">
        <h1 style="font-size: 3rem; font-weight: 900; margin-bottom: 1rem;">Create Your Event</h1>
        <p style="font-size: 1.1rem; color: #666;">Start selling tickets and reach thousands of potential buyers</p>
    </div>

    <!-- Main Content Container -->
    <div class="sell-container" style="max-width: 800px; margin: 3rem auto; padding: 0 2rem;">
        
        <!-- Event Creation Form -->
        <form id="eventForm" class="event-form" style="background: white; padding: 2rem; border-radius: 12px; box-shadow: 0 2px 12px rgba(0,0,0,0.08);">
            
            <!-- Form Title -->
            <h2 style="font-size: 1.8rem; font-weight: 700; margin-bottom: 2rem; color: #000;">Event Details</h2>

            <!-- Event Title Input -->
            <div class="form-group mb-3">
                <label for="eventTitle" class="form-label" style="font-weight: 600; margin-bottom: 0.5rem;">Event Title <span style="color: #e74c3c;">*</span></label>
                <input 
                    type="text" 
                    class="form-control" 
                    id="eventTitle" 
                    placeholder="e.g., Summer Music Festival 2025"
                    required
                    style="padding: 0.75rem; border: 1px solid #ddd; border-radius: 6px;"
                >
                <small class="form-text text-muted">Give your event a catchy name</small>
            </div>

            <!-- Event Description -->
            <div class="form-group mb-3">
                <label for="eventDescription" class="form-label" style="font-weight: 600; margin-bottom: 0.5rem;">Description</label>
                <textarea 
                    class="form-control" 
                    id="eventDescription" 
                    rows="4"
                    placeholder="Describe your event, what to expect, and any special details..."
                    style="padding: 0.75rem; border: 1px solid #ddd; border-radius: 6px; resize: vertical;"
                ></textarea>
                <small class="form-text text-muted">Max 255 characters</small>
            </div>

            <!-- Event Category -->
            <div class="form-group mb-3">
                <label for="eventCategory" class="form-label" style="font-weight: 600; margin-bottom: 0.5rem;">Category <span style="color: #e74c3c;">*</span></label>
                <select 
                    class="form-select" 
                    id="eventCategory"
                    required
                    style="padding: 0.75rem; border: 1px solid #ddd; border-radius: 6px;"
                >
                    <option value="">-- Select a category --</option>
                    <!-- Options will be populated by JavaScript -->
                </select>
            </div>

            <!-- Event Date and Location Row -->
            <div class="row mb-3">
                <!-- Event Date -->
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="eventDate" class="form-label" style="font-weight: 600; margin-bottom: 0.5rem;">Event Date <span style="color: #e74c3c;">*</span></label>
                        <input 
                            type="date" 
                            class="form-control" 
                            id="eventDate"
                            required
                            style="padding: 0.75rem; border: 1px solid #ddd; border-radius: 6px;"
                        >
                    </div>
                </div>

                <!-- Event Location -->
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="eventLocation" class="form-label" style="font-weight: 600; margin-bottom: 0.5rem;">Location <span style="color: #e74c3c;">*</span></label>
                        <input 
                            type="text" 
                            class="form-control" 
                            id="eventLocation"
                            placeholder="e.g., Madison Square Garden, New York"
                            required
                            style="padding: 0.75rem; border: 1px solid #ddd; border-radius: 6px;"
                        >
                    </div>
                </div>
            </div>

            <!-- Capacity and Price Row -->
            <div class="row mb-3">
                <!-- Max Participants -->
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="eventCapacity" class="form-label" style="font-weight: 600; margin-bottom: 0.5rem;">Max Participants <span style="color: #e74c3c;">*</span></label>
                        <input 
                            type="number" 
                            class="form-control" 
                            id="eventCapacity"
                            min="1"
                            placeholder="e.g., 5000"
                            required
                            style="padding: 0.75rem; border: 1px solid #ddd; border-radius: 6px;"
                        >
                        <small class="form-text text-muted">Total number of tickets available</small>
                    </div>
                </div>

                <!-- Event Photo -->
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="eventPhoto" class="form-label" style="font-weight: 600; margin-bottom: 0.5rem;">Event Poster</label>
                        <input 
                            type="file" 
                            class="form-control" 
                            id="eventPhoto"
                            accept="image/*"
                            style="padding: 0.75rem; border: 1px solid #ddd; border-radius: 6px;"
                        >
                        <small class="form-text text-muted">Upload a poster image (JPG, PNG)</small>
                    </div>
                </div>
            </div>

            <!-- Info Alert -->
            <div class="alert alert-info" style="background-color: #e3f2fd; border: 1px solid #2196f3; color: #1565c0; border-radius: 6px; padding: 1rem; margin-bottom: 2rem;">
                <strong>Note:</strong> Your event will appear on the Buy page once submitted. You can edit or delete it anytime from your dashboard.
            </div>

            <!-- Form Actions -->
            <div class="form-actions" style="display: flex; gap: 1rem; justify-content: flex-end;">
                <button 
                    type="reset" 
                    class="btn btn-secondary"
                    style="padding: 0.75rem 2rem; border-radius: 50px; border: 1px solid #ddd; background: white; color: #666; font-weight: 600; cursor: pointer; transition: all 0.3s;"
                >
                    Clear Form
                </button>
                <button 
                    type="submit" 
                    class="btn btn-primary"
                    style="padding: 0.75rem 2rem; border-radius: 50px; background: #2b9af3; color: white; border: none; font-weight: 600; cursor: pointer; transition: all 0.3s;"
                >
                    Create Event
                </button>
            </div>

            <!-- Loading Indicator (hidden by default) -->
            <div id="loadingIndicator" style="display: none; text-align: center; padding: 1rem;">
                <div class="spinner-border" style="color: #2b9af3;" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <p style="margin-top: 0.5rem; color: #666;">Creating your event...</p>
            </div>

            <!-- Success Message (hidden by default) -->
            <div id="successMessage" style="display: none; background-color: #d4edda; border: 1px solid #c3e6cb; color: #155724; border-radius: 6px; padding: 1rem; margin-top: 1rem;">
                ✓ Event created successfully! Your event is now live on the marketplace.
            </div>

            <!-- Error Message (hidden by default) -->
            <div id="errorMessage" style="display: none; background-color: #f8d7da; border: 1px solid #f5c6cb; color: #721c24; border-radius: 6px; padding: 1rem; margin-top: 1rem;"></div>
        </form>

        <!-- My Events Section -->
        <div class="my-events" style="margin-top: 4rem;">
            <h2 style="font-size: 1.8rem; font-weight: 700; margin-bottom: 2rem; color: #000;">Your Events</h2>
            <div id="userEventsList" style="display: grid; gap: 1.5rem;">
                <!-- User events will be displayed here -->
                <div style="text-align: center; padding: 2rem; color: #999;">
                    <p>You haven't created any events yet. Create one to get started!</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript for Sell Page Functionality -->
<script>
// Sell Page Initialization and Event Handling

/**
 * Initialize the Sell page
 * Fetches categories, loads user's events, and attaches form listeners
 */
function initSellPage() {
    console.log('Initializing Sell page');
    
    // API endpoints
    const apiCats = '/api/categorie.php';
    const apiEvents = '/api/evenement.php';
    
    // DOM elements
    const eventForm = document.getElementById('eventForm');
    const categorySelect = document.getElementById('eventCategory');
    const loadingIndicator = document.getElementById('loadingIndicator');
    const successMessage = document.getElementById('successMessage');
    const errorMessage = document.getElementById('errorMessage');
    const userEventsList = document.getElementById('userEventsList');
    const eventPhotoInput = document.getElementById('eventPhoto');

    // Fetch and populate categories
    function loadCategories() {
        fetch(apiCats)
            .then(r => r.json())
            .then(data => {
                if (Array.isArray(data)) {
                    data.forEach(cat => {
                        const option = document.createElement('option');
                        option.value = cat.id_categorie;
                        option.textContent = cat.nom;
                        categorySelect.appendChild(option);
                    });
                }
            })
            .catch(err => console.error('Failed to load categories:', err));
    }

    /**
     * Convert file to base64 encoding for photo storage
     * @param {File} file - The file to convert
     * @return {Promise<string>} - Base64 encoded string
     */
    function fileToBase64(file) {
        return new Promise((resolve, reject) => {
            const reader = new FileReader();
            reader.onload = () => {
                // Remove data URI prefix to store only base64 data
                const base64 = reader.result.split(',')[1];
                resolve(base64);
            };
            reader.onerror = reject;
        });
    }

    /**
     * Handle form submission - Create new event
     */
    eventForm.addEventListener('submit', async (e) => {
        e.preventDefault();

        // Reset messages
        successMessage.style.display = 'none';
        errorMessage.style.display = 'none';
        
        // Show loading indicator
        loadingIndicator.style.display = 'block';

        try {
            // Collect form data
            const eventData = {
                titre: document.getElementById('eventTitle').value.trim(),
                description: document.getElementById('eventDescription').value.trim(),
                date_event: document.getElementById('eventDate').value,
                lieu: document.getElementById('eventLocation').value.trim(),
                nb_max_part: parseInt(document.getElementById('eventCapacity').value),
                id_categorie: parseInt(document.getElementById('eventCategory').value),
                id_utilisateur: 1 // TODO: Get this from session/auth
            };

            // Handle photo upload if provided
            if (eventPhotoInput.files.length > 0) {
                const file = eventPhotoInput.files[0];
                // Validate file size (max 5MB)
                if (file.size > 5 * 1024 * 1024) {
                    throw new Error('Photo size must be less than 5MB');
                }
                eventData.photo = await fileToBase64(file);
            }

            // Send request to create event
            const response = await fetch(apiEvents, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(eventData)
            });

            if (!response.ok) {
                const error = await response.json();
                throw new Error(error.error || 'Failed to create event');
            }

            const result = await response.json();

            // Show success message
            loadingIndicator.style.display = 'none';
            successMessage.style.display = 'block';
            
            // Reset form
            eventForm.reset();
            
            // Reload user events
            loadUserEvents();

            // Hide success message after 3 seconds
            setTimeout(() => {
                successMessage.style.display = 'none';
            }, 3000);

        } catch (err) {
            console.error('Error creating event:', err);
            loadingIndicator.style.display = 'none';
            errorMessage.textContent = '✗ ' + err.message;
            errorMessage.style.display = 'block';
        }
    });

    /**
     * Load and display user's events
     * TODO: Implement once user authentication is set up
     */
    function loadUserEvents() {
        // This will fetch events created by the current user
        // For now, show placeholder
        console.log('Loading user events...');
    }

    // Initialize page
    loadCategories();
    loadUserEvents();
}

// Note: initSellPage() is called automatically when this page is loaded via loadPage()
</script>