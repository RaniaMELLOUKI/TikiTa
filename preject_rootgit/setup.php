<?php
try {
    // Direct PDO connection
    $pdo = new PDO(
        "mysql:host=localhost;dbname=projet_dev;charset=utf8",
        "root",
        ""
    );
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Create tables
    $pdo->exec("CREATE TABLE IF NOT EXISTS categorie (
        id_categorie INT AUTO_INCREMENT PRIMARY KEY,
        nom_categorie VARCHAR(100) NOT NULL,
        description VARCHAR(255)
    )");
    
    $pdo->exec("CREATE TABLE IF NOT EXISTS artiste (
        id_artiste INT AUTO_INCREMENT PRIMARY KEY,
        nom VARCHAR(100) NOT NULL,
        description_artiste VARCHAR(255),
        photo LONGBLOB
    )");
    
    $pdo->exec("CREATE TABLE IF NOT EXISTS utilisateur (
        id_utilisateur INT AUTO_INCREMENT PRIMARY KEY,
        email VARCHAR(255) UNIQUE NOT NULL,
        mot_de_passe VARCHAR(255) NOT NULL,
        prenom VARCHAR(100),
        nom_utilisateur VARCHAR(100),
        photo LONGBLOB
    )");
    
    $pdo->exec("CREATE TABLE IF NOT EXISTS evenement (
        id_event INT AUTO_INCREMENT PRIMARY KEY,
        titre VARCHAR(150) NOT NULL,
        description VARCHAR(255),
        date_event DATE NOT NULL,
        lieu VARCHAR(150) NOT NULL,
        nb_max_part INT NOT NULL,
        id_categorie INT,
        id_artiste INT,
        id_utilisateur INT,
        photo LONGBLOB,
        FOREIGN KEY (id_categorie) REFERENCES categorie(id_categorie),
        FOREIGN KEY (id_artiste) REFERENCES artiste(id_artiste),
        FOREIGN KEY (id_utilisateur) REFERENCES utilisateur(id_utilisateur)
    )");
    
    $pdo->exec("CREATE TABLE IF NOT EXISTS inscription (
        id_inscription INT AUTO_INCREMENT PRIMARY KEY,
        id_utilisateur INT,
        id_event INT,
        date_inscription DATETIME,
        FOREIGN KEY (id_utilisateur) REFERENCES utilisateur(id_utilisateur),
        FOREIGN KEY (id_event) REFERENCES evenement(id_event)
    )");
    
    // Insert sample categories
    $stmt = $pdo->prepare("INSERT IGNORE INTO categorie (nom_categorie) VALUES (?)");
    $categories = ['Sports', 'NBA', 'Concerts', 'Theater', 'NFL', 'Comedy'];
    foreach ($categories as $cat) {
        $stmt->execute([$cat]);
    }
    
    // Insert sample artists
    $stmt = $pdo->prepare("INSERT IGNORE INTO artiste (id_artiste, nom, description_artiste) VALUES (?, ?, ?)");
    $stmt->execute([1, 'Bad Bunny', 'Puerto Rican rapper and singer']);
    $stmt->execute([2, 'Taylor Swift', 'American singer-songwriter']);
    $stmt->execute([3, 'Olivia Dean', 'British R&B singer']);
    $stmt->execute([4, 'Playboi Carti', 'American rapper']);
    $stmt->execute([5, 'Gunna', 'American rapper']);
    
    // Insert sample events
    $stmt = $pdo->prepare("INSERT IGNORE INTO evenement (id_event, titre, description, date_event, lieu, nb_max_part, id_categorie, id_artiste) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([1, 'Bad Bunny Concert', 'Amazing concert experience', '2026-05-15', 'Madison Square Garden', 5000, 3, 1]);
    $stmt->execute([2, 'Taylor Swift Concert', 'Eras Tour', '2026-06-20', 'Soccerfield Stadium', 6000, 3, 2]);
    $stmt->execute([3, 'NBA Championship Finals', 'Basketball finals', '2026-07-10', 'Crypto.com Arena', 10000, 2, null]);
    
    echo json_encode(['success' => true, 'message' => 'Database setup completed successfully']);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?>
