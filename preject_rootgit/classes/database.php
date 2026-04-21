<?php

/**
 * Database Connection Class
 * 
 * Manages the database connection using PDO (PHP Data Objects)
 * Provides a centralized point for database connectivity
 * 
 * Features:
 *   - Secure database connection with prepared statements support
 *   - Error handling with logging
 *   - Exception-based error mode for better error management
 */
class database {
    // Database credentials
    private $host = "localhost";      // MySQL server hostname
    private $db = "projet_dev";       // Database name
    private $user = "root";           // Database user
    private $pass = "";               // Database password (empty for local dev)

    /**
     * Connect to database and return PDO object
     * 
     * Creates a new PDO connection to MySQL database with UTF-8 charset
     * Sets error mode to exceptions for better error handling
     * 
     * @return PDO Database connection object
     * @throws PDOException If connection fails
     */
    public function connect() {
        try {
            // Create PDO connection with error handling
            $pdo = new PDO(
                "mysql:host=$this->host;dbname=$this->db;charset=utf8", 
                $this->user, 
                $this->pass
            );
            
            // Set error mode to throw exceptions for better error handling
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            return $pdo;
        } catch (PDOException $e) {
            // Log the error without exposing sensitive details to client
            error_log("Database connection failed: " . $e->getMessage());
            
            // Return HTTP 503 Service Unavailable
            http_response_code(503);
            echo json_encode(['error' => 'Database connection failed. Please try again later.']);
            exit;
        }
    }
}
