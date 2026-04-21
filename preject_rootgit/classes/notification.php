<?php

/**
 * Notification Model Class
 * 
 * Handles all database operations related to user notifications
 * Supports creating and managing different types of notifications
 * 
 * Database Table: notification
 *   - id_notification: INT (Primary Key, Auto-increment)
 *   - description_notification: VARCHAR(255) (Notification message - Required)
 *   - type: VARCHAR(100) (Notification type: info, warning, error, success, etc.)
 *   - id_utilisateur: INT (Foreign Key to utilisateur table - Recipient)
 */

require_once "database.php";

class notification {
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
     * Retrieve all notifications from the database
     * 
     * @return array Array of all notifications
     */
    public function getAll() {
        $sql = "SELECT * FROM notification";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Retrieve a specific notification by ID
     * 
     * @param int $id Notification ID
     * @return array|false Associative array of notification data, or false if not found
     */
    public function getById($id) {
        $sql = "SELECT * FROM notification WHERE id_notification = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Create a new notification for a user
     * 
     * @param string $description Notification message content
     * @param string $type Type of notification (info, warning, error, success, etc.)
     * @param int $id_utilisateur ID of the user to receive the notification
     * @return bool True if successful, false otherwise
     */
    public function create($description, $type, $id_utilisateur) {
        $sql = "INSERT INTO notification (description_notification, type, id_utilisateur) 
                VALUES (?, ?, ?)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$description, $type, $id_utilisateur]);
    }

    /**
     * Delete a notification from the database
     * 
     * @param int $id Notification ID to delete
     * @return bool True if successful, false otherwise
     */
    public function delete($id) {
        $sql = "DELETE FROM notification WHERE id_notification = ?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$id]);
    }
}
