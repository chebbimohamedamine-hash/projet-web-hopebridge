<?php
// login_post.php
require 'db.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $recaptcha_secret = "6LeMQr4sAAAAAMaR033R2bs0j-3fr5P3QoUDsChi";
    $recaptcha_response = $_POST['g-recaptcha-response'] ?? '';

    // Vérification reCAPTCHA
    $url = 'https://www.google.com/recaptcha/api/siteverify';
    $data = array('secret' => $recaptcha_secret, 'response' => $recaptcha_response);
    $options = array(
        'http' => array(
            'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
            'method'  => 'POST',
            'content' => http_build_query($data),
            'ssl' => array('verify_peer' => false, 'verify_peer_name' => false)
        )
    );
    $context  = stream_context_create($options);
    $result = file_get_contents($url, false, $context);
    $responseKeys = json_decode($result, true);

    if (empty($recaptcha_response) || !$responseKeys["success"]) {
        header("Location: frontend/login.php?error=captcha");
        exit();
    }

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password_hash'])) {
        // Connexion réussie
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['full_name'];
        $_SESSION['user_role'] = $user['role'];

        if ($user['role'] == 'admin') {
            header("Location: backend/dashboard.php");
        } else {
            header("Location: frontend/index.php");
        }
        exit();
    } else {
        // Échec de connexion
        header("Location: frontend/login.php?error=1");
        exit();
    }
}
?>
