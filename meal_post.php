<?php
require 'db.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'donor') {
        die("Accès non autorisé.");
    }

    $donor_id = $_SESSION['user_id'];
    $title = $_POST['title'];
    $quantity = intval($_POST['quantity']);
    $expiry_date = $_POST['expiry_date'];
    $description = $_POST['description'];

    if (empty($title) || $quantity <= 0 || empty($expiry_date)) {
        die("Veuillez remplir tous les champs obligatoires.");
    }

    try {
        $stmt = $pdo->prepare("INSERT INTO meal_donations (donor_id, title, description, quantity, expiry_date, status) VALUES (?, ?, ?, ?, ?, 'available')");
        $stmt->execute([$donor_id, $title, $description, $quantity, $expiry_date]);

        $_SESSION['success_msg'] = "Votre offre de repas a été publiée avec succès !";
        header("Location: frontend/index.php?success=meal_posted");
        exit();
    } catch (PDOException $e) {
        die("Erreur lors de la publication : " . $e->getMessage());
    }
} else {
    header("Location: frontend/post_meal.php");
    exit();
}
?>
