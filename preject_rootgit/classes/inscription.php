<?php

/**
 * Inscription Model Class
 * 
 * Handles all database operations related to event registrations/bookings
 * Manages the many-to-many relationship between users and events
 * 
 * Database Table: inscription
 *   - id_inscription: INT (Primary Key, Auto-increment)
 *   - id_event: INT (Foreign Key to evenement table)
 *   - id_utilisateur: INT (Foreign Key to utilisateur table)
 *   - date_inscription: DATE (Registration date - Automatically set to current date)
 */

require_once "database.php";

class inscription {
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
     * Register a user for an event (create an inscription)
     * Automatically sets the registration date to today
     * 
     * @param int $id_event Event ID to register for
     * @param int $id_utilisateur User ID registering for the event
     * @return bool True if successful, false otherwise
     */
    public function inscrire($id_event, $id_utilisateur) {
        $sql = "INSERT INTO inscription (id_event, id_utilisateur, date_inscription) 
                VALUES (?, ?, CURDATE())";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$id_event, $id_utilisateur]);
    }

    /**
     * Retrieve all registrations for a specific event
     * 
     * @param int $id_event Event ID to get registrations for
     * @return array Array of user registrations for the event
     */
    public function getByEvent($id_event) {
        $sql = "SELECT * FROM inscription WHERE id_event = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id_event]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Retrieve all events a user has registered for
     * 
     * @param int $id_user User ID to get registrations for
     * @return array Array of events the user has registered for
     */
    public function getByUser($id_user) {
        $sql = "SELECT * FROM inscription WHERE id_utilisateur = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id_user]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Cancel a user's registration for an event (delete inscription)
     * 
     * @param int $id Inscription ID to delete
     * @return bool True if successful, false otherwise
     */
    public function delete($id) {
        $sql = "DELETE FROM inscription WHERE id_inscription = ?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$id]);
    }
}
