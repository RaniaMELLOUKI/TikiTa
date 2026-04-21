<?php

/**
 * Categorie Model Class
 * 
 * Handles all database operations related to event categories
 * Provides CRUD (Create, Read, Update, Delete) functionality
 * 
 * Database Table: categorie
 *   - id_categorie: INT (Primary Key, Auto-increment)
 *   - nom: VARCHAR(100) (Category name)
 *   - description: VARCHAR(255) (Optional category description)
 */

require_once "database.php";

class categorie {
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
     * Retrieve all categories from the database
     * 
     * @return array Array of associative arrays containing all categories
     */
    public function getAll() {
        $stmt = $this->pdo->query("SELECT * FROM categorie");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Retrieve a specific category by ID
     * 
     * @param int $id Category ID
     * @return array|false Associative array of category data, or false if not found
     */
    public function getById($id) {
        $sql = "SELECT * FROM categorie WHERE id_categorie = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Create a new category in the database
     * 
     * @param array $data Associative array with keys: nom (required)
     * @return array Associative array of the newly created category with ID
     */
    public function create($data) {
        $sql = "INSERT INTO categorie (nom) VALUES (:nom)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':nom' => $data['nom'] ?? null]);
        return $this->getById($this->pdo->lastInsertId());
    }

    /**
     * Update an existing category
     * 
     * @param int $id Category ID to update
     * @param array $data Associative array with fields to update
     * @return array|false Updated category data, or false if no changes
     */
    public function update($id, $data) {
        $fields = [];
        $params = [];
        
        // Build dynamic update query with only provided fields
        if (isset($data['nom'])) {
            $fields[] = 'nom = :nom';
            $params[':nom'] = $data['nom'];
        }
        
        // Return existing data if no fields to update
        if (empty($fields)) {
            return $this->getById($id);
        }
        
        $params[':id'] = $id;
        $sql = "UPDATE categorie SET " . implode(', ', $fields) . " WHERE id_categorie = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $this->getById($id);
    }

    /**
     * Delete a category from the database
     * Note: Foreign key cascade will delete related events if configured
     * 
     * @param int $id Category ID to delete
     * @return bool True if successful, false otherwise
     */
    public function delete($id) {
        $sql = "DELETE FROM categorie WHERE id_categorie = ?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$id]);
    }
}
