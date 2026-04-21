<?php
/**
 * API Endpoint: evenement.php
 * Handles event CRUD operations and returns JSON
 */

error_reporting(E_ALL);
ini_set('display_errors', 0);

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

require_once __DIR__ . '/../classes/evenement.php';
require_once __DIR__ . '/../classes/validator.php';

try {
    $method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

    if ($method === 'OPTIONS') {
        http_response_code(200);
        exit;
    }

    $evenement = new evenement();

    if ($method === 'GET') {
        $eventId = isset($_GET['id']) ? intval($_GET['id']) : null;
        $searchKeyword = isset($_GET['search']) ? $_GET['search'] : null;
        $categoryId = isset($_GET['category']) ? intval($_GET['category']) : null;
        $userId = isset($_GET['user']) ? intval($_GET['user']) : null;

        if ($eventId) {
            $result = $evenement->getById($eventId);
            if ($result) {
                http_response_code(200);
                echo json_encode($result);
            } else {
                http_response_code(404);
                echo json_encode(['error' => 'Event not found']);
            }
            exit;
        }

        if ($searchKeyword) {
            $searchKeyword = Validator::sanitizeString($searchKeyword);
            if (strlen($searchKeyword) < 2) {
                http_response_code(400);
                echo json_encode(['error' => 'Search term must be at least 2 characters']);
                exit;
            }
            $results = $evenement->search($searchKeyword);
            http_response_code(200);
            echo json_encode($results);
            exit;
        }

        if ($categoryId) {
            if ($categoryId <= 0) {
                http_response_code(400);
                echo json_encode(['error' => 'Invalid category ID']);
                exit;
            }
            $results = $evenement->getByCategory($categoryId);
            http_response_code(200);
            echo json_encode($results);
            exit;
        }

        if ($userId) {
            if ($userId <= 0) {
                http_response_code(400);
                echo json_encode(['error' => 'Invalid user ID']);
                exit;
            }
            $results = $evenement->getByUser($userId);
            http_response_code(200);
            echo json_encode($results);
            exit;
        }

        $results = $evenement->getAll();
        http_response_code(200);
        echo json_encode($results);
        exit;
    }

    if ($method === 'POST') {
        $json = file_get_contents('php://input');
        $data = json_decode($json, true) ?: [];

        $rules = [
            'titre' => 'required|maxlength:150',
            'date_event' => 'required|date',
            'lieu' => 'required|maxlength:150',
            'nb_max_part' => 'required|numeric|min:1',
            'id_categorie' => 'required|numeric|min:1',
            'id_utilisateur' => 'required|numeric|min:1',
            'description' => 'maxlength:255'
        ];

        $errors = Validator::validate($data, $rules);
        if (!empty($errors)) {
            http_response_code(400);
            echo json_encode(['errors' => $errors]);
            exit;
        }

        $data['titre'] = Validator::sanitizeString($data['titre']);
        $data['description'] = Validator::sanitizeString($data['description'] ?? '');
        $data['lieu'] = Validator::sanitizeString($data['lieu']);

        $result = $evenement->create($data);
        if ($result) {
            http_response_code(201);
            echo json_encode($result);
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'Failed to create event']);
        }
        exit;
    }

    if ($method === 'PUT') {
        $json = file_get_contents('php://input');
        $data = json_decode($json, true) ?: [];
        $eventId = isset($_GET['id']) ? intval($_GET['id']) : null;

        if (!$eventId || $eventId <= 0) {
            http_response_code(400);
            echo json_encode(['error' => 'Valid event ID is required']);
            exit;
        }

        $existing = $evenement->getById($eventId);
        if (!$existing) {
            http_response_code(404);
            echo json_encode(['error' => 'Event not found']);
            exit;
        }

        if (isset($data['titre'])) {
            $data['titre'] = Validator::sanitizeString($data['titre']);
            $validation = Validator::validateLength($data['titre'], 150, 1, 'Title');
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

        if (isset($data['lieu'])) {
            $data['lieu'] = Validator::sanitizeString($data['lieu']);
            $validation = Validator::validateLength($data['lieu'], 150, 1, 'Location');
            if ($validation !== true) {
                http_response_code(400);
                echo json_encode(['error' => $validation]);
                exit;
            }
        }

        if (isset($data['date_event'])) {
            $validation = Validator::validateDate($data['date_event']);
            if ($validation !== true) {
                http_response_code(400);
                echo json_encode(['error' => $validation]);
                exit;
            }
        }

        if (isset($data['nb_max_part'])) {
            $validation = Validator::validateNumber($data['nb_max_part'], 1, 0, 'Capacity');
            if ($validation !== true) {
                http_response_code(400);
                echo json_encode(['error' => $validation]);
                exit;
            }
        }

        $result = $evenement->update($eventId, $data);
        if ($result) {
            http_response_code(200);
            echo json_encode($result);
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'Failed to update event']);
        }
        exit;
    }

    if ($method === 'DELETE') {
        $eventId = isset($_GET['id']) ? intval($_GET['id']) : null;
        if (!$eventId || $eventId <= 0) {
            http_response_code(400);
            echo json_encode(['error' => 'Valid event ID is required']);
            exit;
        }

        $existing = $evenement->getById($eventId);
        if (!$existing) {
            http_response_code(404);
            echo json_encode(['error' => 'Event not found']);
            exit;
        }

        $success = $evenement->delete($eventId);
        if ($success) {
            http_response_code(200);
            echo json_encode(['message' => 'Event deleted successfully']);
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'Failed to delete event']);
        }
        exit;
    }

    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit;

} catch (Exception $e) {
    http_response_code(500);
    error_log("API Error in evenement.php: " . $e->getMessage());
    echo json_encode(['error' => 'Internal server error']);
}
?>
