<?php
require 'db.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $code = trim($_POST['code']);
    $email = $_SESSION['reset_email'] ?? '';

    if (empty($code) || empty($email)) {
        header("Location: frontend/verify_code.php?error=1");
        exit();
    }

    // Vérifier le code en base
    $stmt = $pdo->prepare("SELECT * FROM password_resets WHERE email = ? AND code = ? AND expires_at > NOW()");
    $stmt->execute([$email, $code]);
    $reset = $stmt->fetch();

    if ($reset) {
        // Code correct ! Marquer comme vérifié dans la session
        $_SESSION['code_verified'] = true;
        header("Location: frontend/reset_password.php");
        exit();
    } else {
        header("Location: frontend/verify_code.php?error=invalid");
        exit();
    }
}
?>
