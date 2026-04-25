<?php
$host = '127.0.0.1';
$dbname = 'homeless';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    echo "Connexion réussie à 127.0.0.1 !";
} catch (PDOException $e) {
    echo "Erreur 127.0.0.1 : " . $e->getMessage();
}
?>
