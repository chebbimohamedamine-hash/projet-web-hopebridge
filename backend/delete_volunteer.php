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
        $stmt = $pdo->prepare("DELETE FROM volunteers WHERE id = ?");
        $stmt->execute([$id]);
        header("Location: volunteers.php?success=deleted");
    } catch (PDOException $e) {
        die("Erreur lors de la suppression : " . $e->getMessage());
    }
} else {
    header("Location: volunteers.php");
}
exit();
