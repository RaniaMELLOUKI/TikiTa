<?php

/**
 * Evenement Model Class
 * 
 * Handles all database operations related to events/tickets
 * Provides CRUD (Create, Read, Update, Delete) and search/filter functionality
 * 
 * Database Table: evenement
 *   - id_event: INT (Primary Key, Auto-increment)
 *   - titre: VARCHAR(150) (Event title - Required)
 *   - description: VARCHAR(255) (Event description)
 *   - date_event: DATE (Event date - Required)
 *   - lieu: VARCHAR(150) (Event location - Required)
 *   - nb_max_part: INT (Maximum participants - Required)
 *   - id_categorie: INT (Foreign Key to categorie table)
 *   - id_utilisateur: INT (Foreign Key to utilisateur table - Event creator)
 *   - photo: LONGBLOB (Event poster/photo stored as binary data)
 */

require_once "database.php";

class evenement {
    // Database connection object
    private $pdo;

    /**
     * Constructor: Initialize database connection
     */
    public function __construct() {
        $db = new database();
        $this->pdo = $db->connect();
    }

    /**
     * Retrieve all events from the database
     * 
     * @return array Array of associative arrays containing all events
     */
    public function getAll() {
        $sql = "SELECT * FROM evenement";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Retrieve a specific event by ID
     * 
     * @param int $id Event ID
     * @return array|false Associative array of event data, or false if not found
     */
    public function getById($id) {
        $sql = "SELECT * FROM evenement WHERE id_event = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Retrieve all events in a specific category
     * 
     * @param int $id_categorie Category ID to filter by
     * @return array Array of events in the specified category
     */
    public function getByCategory($id_categorie) {
        $sql = "SELECT * FROM evenement WHERE id_categorie = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id_categorie]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Retrieve all events created by a specific user
     * 
     * @param int $id_utilisateur User ID to filter by
     * @return array Array of events created by the specified user
     */
    public function getByUser($id_utilisateur) {
        $sql = "SELECT * FROM evenement WHERE id_utilisateur = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id_utilisateur]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Search for events by keyword
     * Searches across event title, description, and location
     * Performs case-insensitive partial matching
     * 
     * @param string $keyword Search keyword to match
     * @return array Array of matching events
     */
    public function search($keyword) {
        $sql = "SELECT * FROM evenement WHERE titre LIKE ? OR description LIKE ? OR lieu LIKE ?";
        $like = '%' . $keyword . '%';
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$like, $like, $like]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Create a new event in the database
     * 
     * @param array $data Associative array with keys:
     *                     - titre: Event title (required)
     *                     - description: Event description (optional)
     *                     - date_event: Event date in YYYY-MM-DD format (required)
     *                     - lieu: Event location (required)
     *                     - nb_max_part: Maximum participants (required)
     *                     - id_categorie: Category ID (required)
     *                     - id_utilisateur: Creator user ID (required)
     *                     - photo: Event photo as BLOB (optional)
     * @return array Associative array of the newly created event with ID
     */
    public function create($data) {
        $sql = "INSERT INTO evenement (titre, description, date_event, lieu, nb_max_part, id_categorie, id_utilisateur, photo)
                VALUES (:titre, :description, :date_event, :lieu, :nb_max_part, :id_categorie, :id_utilisateur, :photo)";
        $stmt = $this->pdo->prepare($sql);

        $params = [
            ':titre' => $data['titre'] ?? null,
            ':description' => $data['description'] ?? null,
            ':date_event' => $data['date_event'] ?? null,
            ':lieu' => $data['lieu'] ?? null,
            ':nb_max_part' => $data['nb_max_part'] ?? 0,
            ':id_categorie' => $data['id_categorie'] ?? null,
            ':id_utilisateur' => $data['id_utilisateur'] ?? null,
            ':photo' => $data['photo'] ?? null
        ];

        $stmt->execute($params);
        $id = $this->pdo->lastInsertId();
        return $this->getById($id);
    }

    /**
     * Update an existing event
     * Only provided fields will be updated (partial update supported)
     * 
     * @param int $id Event ID to update
     * @param array $data Associative array with fields to update
     * @return array|false Updated event data, or false if no changes
     */
    public function update($id, $data) {
        // Allowed fields that can be updated
        $allowed = ['titre','description','date_event','lieu','nb_max_part','id_categorie','id_utilisateur','photo'];
        $set = [];
        $params = [];
        
        // Build dynamic update query with only allowed and provided fields
        foreach ($allowed as $field) {
            if (isset($data[$field])) {
                $set[] = "$field = :$field";
                $params[":$field"] = $data[$field];
            }
        }
        
        // Return existing data if no fields to update
        if (empty($set)) {
            return $this->getById($id);
        }

        $sql = "UPDATE evenement SET " . implode(', ', $set) . " WHERE id_event = :id";
        $params[':id'] = $id;
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $this->getById($id);
    }

    /**
     * Delete an event from the database
     * Linked inscriptions will be deleted due to foreign key cascade
     * 
     * @param int $id Event ID to delete
     * @return bool True if successful, false otherwise
     */
    public function delete($id) {
        $sql = "DELETE FROM evenement WHERE id_event = ?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$id]);
    }
}
