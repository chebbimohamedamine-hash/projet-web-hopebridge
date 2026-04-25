<?php
require 'db.php';
try {
    $stmt = $pdo->query("DESCRIBE donations");
    echo "<pre>";
    print_r($stmt->fetchAll());
    echo "</pre>";
} catch (Exception $e) {
    echo "Erreur : " . $e->getMessage();
}
?>
