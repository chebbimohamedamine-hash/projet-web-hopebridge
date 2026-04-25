<?php
// db.php - Connexion à la base de données
$host = 'localhost';
$dbname = 'homeless';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

// Fonction utilitaire pour rediriger
function redirect($url) {
    header("Location: $url");
    exit();
}
?>
