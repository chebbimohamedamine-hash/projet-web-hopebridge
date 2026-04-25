<?php
session_start();
require '../db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'admin') {
    header("Location: ../frontend/login.php");
    exit();
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    try {
        // Optionnel : Supprimer le fichier image associé
        $stmtImg = $pdo->prepare("SELECT shelter_photo FROM shelters WHERE id = ?");
        $stmtImg->execute([$id]);
        $shelter = $stmtImg->fetch();
        if ($shelter && $shelter['shelter_photo'] && file_exists("../" . $shelter['shelter_photo'])) {
            unlink("../" . $shelter['shelter_photo']);
        }

        $stmt = $pdo->prepare("DELETE FROM shelters WHERE id = ?");
        $stmt->execute([$id]);
        header("Location: shelters.php?success=deleted");
    } catch (PDOException $e) {
        die("Erreur lors de la suppression : " . $e->getMessage());
    }
} else {
    header("Location: shelters.php");
}
exit();
