<?php

/**
 * Utilisateur Model Class
 * 
 * Handles all database operations related to users/accounts
 * Provides CRUD (Create, Read, Update, Delete) and authentication functionality
 * 
 * Database Table: utilisateur
 *   - id_utilisateur: INT (Primary Key, Auto-increment)
 *   - nom_utilisateur: VARCHAR(100) (Username - Required)
 *   - email_utilisateur: VARCHAR(150) (Email - Required, Unique)
 *   - mot_de_passe: VARCHAR(255) (Password - hashed with PASSWORD_DEFAULT - Required)
 *   - role_utilisateur: VARCHAR(50) (User role - Required)
 */

require_once "database.php";

class utilisateur {
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
     * Retrieve all users from the database
     * 
     * @return array Array of associative arrays containing all users
     */
    public function getAll() {
        $stmt = $this->pdo->query("SELECT * FROM utilisateur");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Retrieve a user by email address
     * 
     * @param string $email User email address
     * @return array|false Associative array of user data, or false if not found
     */
    public function getByEmail($email) {
        $sql = "SELECT * FROM utilisateur WHERE email_utilisateur = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Retrieve a specific user by ID
     * 
     * @param int $id User ID
     * @return array|false Associative array of user data, or false if not found
     */
    public function getById($id) {
        $sql = "SELECT * FROM utilisateur WHERE id_utilisateur = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Create a new user account in the database
     * Password is hashed using PHP's PASSWORD_DEFAULT algorithm (bcrypt)
     * 
     * @param array $data Associative array with keys:
     *                     - nom_utilisateur: Username (required)
     *                     - email_utilisateur: Email address (required)
     *                     - mot_de_passe: Plain text password (required, will be hashed)
     *                     - role_utilisateur: User role (required)
     * @return array Associative array of the newly created user with ID
     */
    public function create($data) {
        $sql = "INSERT INTO utilisateur (nom_utilisateur, email_utilisateur, mot_de_passe, role_utilisateur) 
                VALUES (:nom, :email, :pwd, :role)";
        $stmt = $this->pdo->prepare($sql);
        
        // Hash the password using PHP's secure PASSWORD_DEFAULT algorithm
        $pwdHash = isset($data['mot_de_passe']) ? password_hash($data['mot_de_passe'], PASSWORD_DEFAULT) : null;
        
        $params = [
            ':nom' => $data['nom_utilisateur'] ?? null,
            ':email' => $data['email_utilisateur'] ?? null,
            ':pwd' => $pwdHash,
            ':role' => $data['role_utilisateur'] ?? 'user'
        ];
        
        $stmt->execute($params);
        return $this->getById($this->pdo->lastInsertId());
    }

    /**
     * Authenticate a user by email and password
     * Verifies password using PHP's password_verify() for secure comparison
     * 
     * @param string $email User email address
     * @param string $password Plain text password to verify
     * @return array|false User data if credentials are valid, false otherwise
     */
    public function authenticate($email, $password) {
        // Get user by email
        $user = $this->getByEmail($email);
        
        // Return false if user doesn't exist
        if (!$user) {
            return false;
        }
        
        // Verify password using secure comparison
        if (!empty($user['mot_de_passe']) && password_verify($password, $user['mot_de_passe'])) {
            return $user;
        }
        
        return false;
    }
}