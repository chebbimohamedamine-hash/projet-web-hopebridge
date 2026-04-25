<?php
require 'db.php';

try {
    $sql = "
    CREATE TABLE IF NOT EXISTS meal_donations (
        id INT AUTO_INCREMENT PRIMARY KEY,
        donor_id INT NOT NULL,
        ngo_id INT DEFAULT NULL,
        title VARCHAR(255) NOT NULL,
        description TEXT,
        quantity INT NOT NULL DEFAULT 1,
        expiry_date DATETIME NOT NULL,
        status ENUM('available', 'claimed', 'collected') DEFAULT 'available',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (donor_id) REFERENCES users(id) ON DELETE CASCADE,
        FOREIGN KEY (ngo_id) REFERENCES users(id) ON DELETE SET NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
    ";
    
    $pdo->exec($sql);
    echo "Table 'meal_donations' créée avec succès !";
} catch (PDOException $e) {
    echo "Erreur lors de la création de la table : " . $e->getMessage();
}
?>
