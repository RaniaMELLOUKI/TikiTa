<?php
/**
 * API Endpoint: categorie.php
 * Description: Handles all category-related HTTP requests (GET, POST, PUT, DELETE)
 * Purpose: Provides categories data for filtering events with CRUD operations
 */

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 0); // Don't display errors to client

// Set response headers
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// Include required classes
require_once '../classes/categorie.php';
require_once '../classes/validator.php';

try {
    // Get request method
    $method = $_SERVER['REQUEST_METHOD'];

    // Handle preflight requests (CORS)
    if ($method === 'OPTIONS') {
        http_response_code(200);
        exit;
    }

    // Initialize categorie object
    $categorie = new categorie();

    /**
     * GET: Retrieve all categories or a specific category
     * Query parameters:
     *   - id: Get specific category by ID
     */
    if ($method === 'GET') {
        // Check if a specific category ID is requested
        $categoryId = isset($_GET['id']) ? intval($_GET['id']) : null;

        if ($categoryId) {
            // Get specific category by ID
            $result = $categorie->getById($categoryId);
            
            if ($result) {
                http_response_code(200);
                echo json_encode($result);
            } else {
                // Category not found
                http_response_code(404);
                echo json_encode(['error' => 'Category not found']);
            }
        } else {
            // Get all categories
            $results = $categorie->getAll();
            http_response_code(200);
            echo json_encode($results);
        }
    }
    /**
     * POST: Create a new category
     * Required body parameters:
     *   - nom: Category name
     * Optional parameters:
     *   - description: Category description
     */
    elseif ($method === 'POST') {
        $json = file_get_contents('php://input');
        $data = json_decode($json, true);

        // Define validation rules
        $rules = [
            'nom' => 'required|maxlength:100',
            'description' => 'maxlength:255'
        ];

        // Validate input
        $errors = Validator::validate($data, $rules);
        if (!empty($errors)) {
            http_response_code(400);
            echo json_encode(['errors' => $errors]);
            exit;
        }

        // Sanitize inputs
        $data['nom'] = Validator::sanitizeString($data['nom']);
        if (isset($data['description'])) {
            $data['description'] = Validator::sanitizeString($data['description']);
        }

        // Create the category
        $result = $categorie->create($data);

        if ($result) {
            http_response_code(201); // Created
            echo json_encode($result);
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'Failed to create category']);
        }
    }
    /**
     * PUT: Update an existing category
     * Required URL parameter: id (category ID)
     * Body parameters: Fields to update (nom, description)
     */
    elseif ($method === 'PUT') {
        $json = file_get_contents('php://input');
        $data = json_decode($json, true);

        // Get category ID from query string
        $categoryId = isset($_GET['id']) ? intval($_GET['id']) : null;

        if (!$categoryId || $categoryId <= 0) {
            http_response_code(400);
            echo json_encode(['error' => 'Valid category ID is required']);
            exit;
        }

        // Verify category exists
        $existing = $categorie->getById($categoryId);
        if (!$existing) {
            http_response_code(404);
            echo json_encode(['error' => 'Category not found']);
            exit;
        }

        // Sanitize inputs
        if (isset($data['nom'])) {
            $data['nom'] = Validator::sanitizeString($data['nom']);
            $validation = Validator::validateLength($data['nom'], 100, 1, 'Category name');
            if ($validation !== true) {
                http_response_code(400);
                echo json_encode(['error' => $validation]);
                exit;
            }
        }

        if (isset($data['description'])) {
            $data['description'] = Validator::sanitizeString($data['description']);
            $validation = Validator::validateLength($data['description'], 255, 0, 'Description');
            if ($validation !== true) {
                http_response_code(400);
                echo json_encode(['error' => $validation]);
                exit;
            }
        }

        // Update the category
        $result = $categorie->update($categoryId, $data);

        if ($result) {
            http_response_code(200);
            echo json_encode($result);
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'Failed to update category']);
        }
    }
    /**
     * DELETE: Remove a category
     * Required URL parameter: id (category ID)
     */
    elseif ($method === 'DELETE') {
        // Get category ID from query string
        $categoryId = isset($_GET['id']) ? intval($_GET['id']) : null;

        if (!$categoryId || $categoryId <= 0) {
            http_response_code(400);
            echo json_encode(['error' => 'Valid category ID is required']);
            exit;
        }

        // Verify category exists
        $existing = $categorie->getById($categoryId);
        if (!$existing) {
            http_response_code(404);
            echo json_encode(['error' => 'Category not found']);
            exit;
        }

        // Delete the category
        $success = $categorie->delete($categoryId);

        if ($success) {
            http_response_code(200);
            echo json_encode(['message' => 'Category deleted successfully']);
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'Failed to delete category']);
        }
    }
    // Invalid method
    else {
        http_response_code(405); // Method Not Allowed
        echo json_encode(['error' => 'Method not allowed. Supported: GET, POST, PUT, DELETE']);
    }

} catch (Exception $e) {
    // Handle any unexpected errors
    http_response_code(500); // Internal Server Error
    error_log("API Error in categorie.php: " . $e->getMessage());
    echo json_encode(['error' => 'Internal server error']);
}

