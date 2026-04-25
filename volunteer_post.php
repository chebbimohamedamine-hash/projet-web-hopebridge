<?php
// volunteer_post.php - Version Professionnelle (AJAX & JSON)
require 'db.php';
require 'mail_helper.php';
session_start();

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $full_name = $_POST['full_name'] ?? '';
    $email = $_POST['email'] ?? '';
    $motivation = $_POST['motivation'] ?? '';
    $photo_path = null;

    if (empty($full_name) || empty($email)) {
        echo json_encode(['success' => false, 'message' => 'Veuillez remplir tous les champs obligatoires.']);
        exit();
    }

    try {
        // 1. Vérifier si l'email existe déjà
        $stmtCheck = $pdo->prepare("SELECT id, role FROM users WHERE email = ?");
        $stmtCheck->execute([$email]);
        $existingUser = $stmtCheck->fetch();

        if ($existingUser) {
            // Vérifier si c'est déjà un bénévole
            $stmtVolCheck = $pdo->prepare("SELECT id FROM volunteers WHERE user_id = ?");
            $stmtVolCheck->execute([$existingUser['id']]);
            if ($stmtVolCheck->fetch()) {
                echo json_encode(['success' => false, 'message' => 'Vous avez déjà postulé avec cet email.']);
                exit();
            }
            $userId = $existingUser['id'];
        } else {
            // 2. Upload de la photo si c'est un nouvel utilisateur
            if (isset($_FILES['profile_photo']) && $_FILES['profile_photo']['error'] == 0) {
                $upload_dir = 'uploads/';
                if (!is_dir($upload_dir)) mkdir($upload_dir);
                $filename = time() . '_' . basename($_FILES['profile_photo']['name']);
                if (move_uploaded_file($_FILES['profile_photo']['tmp_name'], $upload_dir . $filename)) {
                    $photo_path = $upload_dir . $filename;
                }
            }

            // Créer le nouvel utilisateur
            $stmtUser = $pdo->prepare("INSERT INTO users (full_name, email, password_hash, role, profile_photo) VALUES (?, ?, ?, 'volunteer', ?)");
            $stmtUser->execute([$full_name, $email, password_hash('pass123', PASSWORD_BCRYPT), $photo_path]);
            $userId = $pdo->lastInsertId();
        }

        // 3. Créer l'entrée bénévole
        $stmtVol = $pdo->prepare("INSERT INTO volunteers (user_id, skills) VALUES (?, ?)");
        $stmtVol->execute([$userId, $motivation]);

        // 4. Envoi email de confirmation
        $email_body = "<h1>Bienvenue chez HopeBridge, $full_name !</h1>
                       <p>Votre candidature en tant que bénévole a bien été reçue.</p>
                       <p>Notre équipe va l'étudier et reviendra vers vous très prochainement.</p>
                       <p>Merci pour votre engagement !</p>";
        sendHopeMail($email, "Candidature Bénévole Reçue - HopeBridge", $email_body);

        echo json_encode([
            'success' => true, 
            'message' => 'Votre candidature a été envoyée avec succès ! Nous vous contacterons bientôt.'
        ]);

    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Erreur lors du traitement : ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Méthode non autorisée.']);
}
?>
