<?php

/**
 * Artiste Model Class
 * 
 * Handles all database operations related to artists/performers
 * Provides CRUD (Create, Read, Update, Delete) and search functionality
 * 
 * Database Table: artiste
 *   - id_artiste: INT (Primary Key, Auto-increment)
 *   - nom: VARCHAR(100) (Artist name - Required)
 *   - description_artiste: VARCHAR(255) (Artist bio/description)
 *   - photo: LONGBLOB (Artist photo stored as binary data)
 */

require_once "database.php";

class artiste {
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
     * Retrieve all artists from the database
     * 
     * @return array Array of associative arrays containing all artists
     */
    public function getAll() {
        $stmt = $this->pdo->query("SELECT * FROM artiste");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Retrieve a specific artist by ID
     * 
     * @param int $id Artist ID
     * @return array|false Associative array of artist data, or false if not found
     */
    public function getById($id) {
        $sql = "SELECT * FROM artiste WHERE id_artiste = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Search for artists by name using LIKE query
     * Performs case-insensitive partial matching on artist name
     * 
     * @param string $keyword Search keyword to match against artist names
     * @return array Array of matching artists
     */
    public function search($keyword) {
        $sql = "SELECT * FROM artiste WHERE nom LIKE ?";
        $like = '%' . $keyword . '%';
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$like]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Create a new artist in the database
     * 
     * @param array $data Associative array with keys:
     *                     - nom: Artist name (required)
     *                     - description: Artist description (optional)
     *                     - photo: Artist photo as BLOB (optional)
     * @return array Associative array of the newly created artist with ID
     */
    public function create($data) {
        $sql = "INSERT INTO artiste (nom, description_artiste, photo) VALUES (:nom, :description, :photo)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':nom' => $data['nom'] ?? null,
            ':description' => $data['description'] ?? null,
            ':photo' => $data['photo'] ?? null
        ]);
        return $this->getById($this->pdo->lastInsertId());
    }

    /**
     * Update an existing artist
     * 
     * @param int $id Artist ID to update
     * @param array $data Associative array with fields to update
     * @return array|false Updated artist data, or false if no changes
     */
    public function update($id, $data) {
        $fields = [];
        $params = [];
        
        // Build dynamic update query with only provided fields
        if (isset($data['nom'])) {
            $fields[] = 'nom = :nom';
            $params[':nom'] = $data['nom'];
        }
        if (isset($data['description'])) {
            $fields[] = 'description_artiste = :description';
            $params[':description'] = $data['description'];
        }
        if (isset($data['photo'])) {
            $fields[] = 'photo = :photo';
            $params[':photo'] = $data['photo'];
        }
        
        // Return existing data if no fields to update
        if (empty($fields)) {
            return $this->getById($id);
        }
        
        $params[':id'] = $id;
        $sql = "UPDATE artiste SET " . implode(', ', $fields) . " WHERE id_artiste = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $this->getById($id);
    }

    /**
     * Delete an artist from the database
     * 
     * @param int $id Artist ID to delete
     * @return bool True if successful, false otherwise
     */
    public function delete($id) {
        $sql = "DELETE FROM artiste WHERE id_artiste = ?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$id]);
    }
}
