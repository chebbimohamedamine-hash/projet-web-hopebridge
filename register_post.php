<?php
// register_post.php
require 'db.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $full_name = $_POST['full_name'];
    $email = $_POST['email'];
    $role = $_POST['role'];
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
            'ignore_errors' => true,
            'ssl' => array('verify_peer' => false, 'verify_peer_name' => false) // Pour XAMPP
        )
    );
    $context  = stream_context_create($options);
    $result = file_get_contents($url, false, $context);
    $responseKeys = json_decode($result, true);

    if (empty($recaptcha_response) || !$responseKeys["success"]) {
        die("Veuillez valider le reCAPTCHA.");
    }

    // Validation simple côté serveur
    if (empty($full_name) || empty($email) || empty($password)) {
        die("Veuillez remplir tous les champs obligatoires.");
    }

    $password_hash = password_hash($password, PASSWORD_BCRYPT);

    try {
        $stmt = $pdo->prepare("INSERT INTO users (full_name, email, password_hash, role) VALUES (?, ?, ?, ?)");
        $stmt->execute([$full_name, $email, $password_hash, $role]);
        
        $_SESSION['success'] = "Inscription réussie ! Vous pouvez maintenant vous connecter.";
        header("Location: frontend/login.php");
        exit();
    } catch (PDOException $e) {
        if ($e->getCode() == 23000) {
            die("Cet email est déjà utilisé.");
        }
        die("Erreur : " . $e->getMessage());
    }
}
?>
