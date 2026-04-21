<?php
/**
 * API Endpoint: artiste.php
 * Description: Handles all artist-related HTTP requests (GET, POST, PUT, DELETE)
 * Purpose: Provides CRUD operations for artists and artist search
 */

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 0); // Don't display errors to client

// Set response headers
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// Include the artiste model class
require_once '../classes/artist.php';

try {
    // Get request method
    $method = $_SERVER['REQUEST_METHOD'];

    // Handle preflight requests (CORS)
    if ($method === 'OPTIONS') {
        http_response_code(200);
        exit;
    }

    // Initialize artiste object
    $artiste = new artiste();

    /**
     * GET: Retrieve all artists or search by name
     * Query parameters:
     *   - search: Search by artist name
     *   - id: Get specific artist by ID
     */
    if ($method === 'GET') {
        $artistId = isset($_GET['id']) ? intval($_GET['id']) : null;
        $searchName = isset($_GET['search']) ? $_GET['search'] : null;

        // Get specific artist by ID
        if ($artistId) {
            $result = $artiste->getById($artistId);
            
            if ($result) {
                http_response_code(200);
                echo json_encode($result);
            } else {
                http_response_code(404);
                echo json_encode(['error' => 'Artist not found']);
            }
        }
        // Search artists by name
        elseif ($searchName) {
            $searchName = htmlspecialchars($searchName, ENT_QUOTES, 'UTF-8');
            $results = $artiste->search($searchName);
            http_response_code(200);
            echo json_encode($results);
        }
        // Get all artists
        else {
            $results = $artiste->getAll();
            http_response_code(200);
            echo json_encode($results);
        }
    }
    /**
     * POST: Create a new artist
     * Required body parameters:
     *   - nom: Artist name
     * Optional body parameters:
     *   - description: Artist description
     *   - photo: Artist photo (BLOB/base64)
     */
    elseif ($method === 'POST') {
        $json = file_get_contents('php://input');
        $data = json_decode($json, true);

        // Validate required fields
        if (!isset($data['nom']) || empty($data['nom'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Artist name is required']);
            exit;
        }

        // Sanitize inputs
        $data['nom'] = htmlspecialchars($data['nom'], ENT_QUOTES, 'UTF-8');
        $data['description'] = htmlspecialchars($data['description'] ?? '', ENT_QUOTES, 'UTF-8');

        // Create the artist
        $result = $artiste->create($data);

        if ($result) {
            http_response_code(201);
            echo json_encode($result);
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'Failed to create artist']);
        }
    }
    /**
     * PUT: Update an existing artist
     * Required URL parameter: id (artist ID)
     */
    elseif ($method === 'PUT') {
        $json = file_get_contents('php://input');
        $data = json_decode($json, true);

        // Get artist ID from query string
        $artistId = isset($_GET['id']) ? intval($_GET['id']) : null;

        if (!$artistId) {
            http_response_code(400);
            echo json_encode(['error' => 'Artist ID is required']);
            exit;
        }

        // Verify artist exists
        $existing = $artiste->getById($artistId);
        if (!$existing) {
            http_response_code(404);
            echo json_encode(['error' => 'Artist not found']);
            exit;
        }

        // Sanitize inputs
        if (isset($data['nom'])) {
            $data['nom'] = htmlspecialchars($data['nom'], ENT_QUOTES, 'UTF-8');
        }
        if (isset($data['description'])) {
            $data['description'] = htmlspecialchars($data['description'], ENT_QUOTES, 'UTF-8');
        }

        // Update the artist
        $result = $artiste->update($artistId, $data);

        if ($result) {
            http_response_code(200);
            echo json_encode($result);
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'Failed to update artist']);
        }
    }
    /**
     * DELETE: Remove an artist
     * Required URL parameter: id (artist ID)
     */
    elseif ($method === 'DELETE') {
        $artistId = isset($_GET['id']) ? intval($_GET['id']) : null;

        if (!$artistId) {
            http_response_code(400);
            echo json_encode(['error' => 'Artist ID is required']);
            exit;
        }

        // Verify artist exists
        $existing = $artiste->getById($artistId);
        if (!$existing) {
            http_response_code(404);
            echo json_encode(['error' => 'Artist not found']);
            exit;
        }

        // Delete the artist
        $success = $artiste->delete($artistId);

        if ($success) {
            http_response_code(200);
            echo json_encode(['message' => 'Artist deleted successfully']);
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'Failed to delete artist']);
        }
    }
    // Invalid method
    else {
        http_response_code(405);
        echo json_encode(['error' => 'Method not allowed']);
    }

} catch (Exception $e) {
    // Handle any unexpected errors
    http_response_code(500);
    error_log("API Error in artiste.php: " . $e->getMessage());
    echo json_encode(['error' => 'Internal server error']);
}
