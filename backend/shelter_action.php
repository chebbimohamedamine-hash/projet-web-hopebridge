<?php
// backend/shelter_action.php
require '../db.php';
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'admin') {
    die("Accès non autorisé.");
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['shelter_name'];
    $capacity = $_POST['capacity'];
    $address = $_POST['address'];
    $photo_path = null;

    // Gestion de l'upload de photo
    if (isset($_FILES['shelter_photo']) && $_FILES['shelter_photo']['error'] == 0) {
        $upload_dir = '../uploads/';
        if (!is_dir($upload_dir)) mkdir($upload_dir);
        
        $filename = time() . '_' . basename($_FILES['shelter_photo']['name']);
        $target_file = $upload_dir . $filename;
        
        if (move_uploaded_file($_FILES['shelter_photo']['tmp_name'], $target_file)) {
            $photo_path = 'uploads/' . $filename;
        }
    }

    try {
        $stmt = $pdo->prepare("INSERT INTO shelters (name, address, total_capacity, shelter_photo) VALUES (?, ?, ?, ?)");
        $stmt->execute([$name, $address, $capacity, $photo_path]);
        
        header("Location: shelters.php");
        exit();
    } catch (PDOException $e) {
        die("Erreur : " . $e->getMessage());
    }
}
?>
