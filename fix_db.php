<?php
require 'db.php';
try {
    $pdo->exec("ALTER TABLE donations ADD COLUMN IF NOT EXISTS currency VARCHAR(10) DEFAULT 'EUR' AFTER amount");
    $pdo->exec("ALTER TABLE donations ADD COLUMN IF NOT EXISTS donation_date DATETIME DEFAULT CURRENT_TIMESTAMP AFTER currency");
    echo "Colonnes ajoutées avec succès !";
} catch (Exception $e) {
    echo "Erreur : " . $e->getMessage();
}
?>
