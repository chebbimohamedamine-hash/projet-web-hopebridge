<?php
require 'db.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $email = $_SESSION['reset_email'] ?? '';

    if (!isset($_SESSION['code_verified']) || !$_SESSION['code_verified'] || empty($email)) {
        header("Location: frontend/login.php");
        exit();
    }

    if ($password !== $confirm_password) {
        header("Location: frontend/reset_password.php?error=match");
        exit();
    }

    // Mettre à jour le mot de passe
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("UPDATE users SET password_hash = ? WHERE email = ?");
    $stmt->execute([$hashed_password, $email]);

    // Nettoyer
    $pdo->prepare("DELETE FROM password_resets WHERE email = ?")->execute([$email]);
    unset($_SESSION['reset_email']);
    unset($_SESSION['code_verified']);

    header("Location: frontend/login.php?reset=success");
    exit();
}
?>
