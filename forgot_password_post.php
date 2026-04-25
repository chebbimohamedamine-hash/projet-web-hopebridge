<?php
require 'db.php';
require 'mail_helper.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);

    if (empty($email)) {
        header("Location: frontend/forgot_password.php?error=empty");
        exit();
    }

    // Vérifier si l'utilisateur existe
    $stmt = $pdo->prepare("SELECT id, full_name FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user) {
        // 1. Générer un code de 6 chiffres
        $code = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
        $expires_at = date("Y-m-d H:i:s", strtotime("+15 minutes"));

        // 2. Supprimer les anciens codes pour cet email
        $pdo->prepare("DELETE FROM password_resets WHERE email = ?")->execute([$email]);

        // 3. Insérer le nouveau code
        $stmt = $pdo->prepare("INSERT INTO password_resets (email, code, expires_at) VALUES (?, ?, ?)");
        $stmt->execute([$email, $code, $expires_at]);

        // 4. Envoyer l'email
        $subject = "Code de réinitialisation - HopeBridge";
        $body = "
            <h1>Bonjour {$user['full_name']}</h1>
            <p>Vous avez demandé la réinitialisation de votre mot de passe HopeBridge.</p>
            <p>Voici votre code de vérification (valable 15 minutes) :</p>
            <div style='background: #f3f4f6; padding: 20px; text-align: center; border-radius: 12px; font-size: 32px; font-weight: 800; letter-spacing: 10px; color: #0d9488;'>
                $code
            </div>
            <p>Si vous n'êtes pas à l'origine de cette demande, ignorez cet email.</p>
        ";

        if (sendHopeMail($email, $subject, $body)) {
            $_SESSION['reset_email'] = $email;
            header("Location: frontend/verify_code.php");
            exit();
        } else {
            die("Erreur : Impossible d'envoyer l'email de vérification. Vérifiez votre connexion.");
        }
    } else {
        header("Location: frontend/forgot_password.php?error=not_found");
        exit();
    }
}
?>
