<?php
// donation_post.php - Version Professionnelle (AJAX & JSON)
require 'db.php';
require 'mail_helper.php';
session_start();

// Définir le header pour JSON
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Récupération des données (supporte FormData et JSON)
    $input = json_decode(file_get_contents('php://input'), true) ?? $_POST;
    
    $amount = isset($input['amount']) ? floatval($input['amount']) : 0;
    $category_id = isset($input['category_id']) ? intval($input['category_id']) : null;
    $user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
    $currency = 'EUR';

    // Validation
    if ($amount <= 0) {
        echo json_encode(['success' => false, 'message' => 'Le montant doit être supérieur à 0.']);
        exit();
    }

    // Simulation d'un délai de traitement (pour le côté "Pro")
    usleep(1500000); // 1.5 secondes

    try {
        // Génération d'un ID de transaction unique
        $transaction_id = 'HB-' . strtoupper(uniqid());
        $status = 'completed';

        // Insertion dans la base de données
        $stmt = $pdo->prepare("INSERT INTO donations (amount, currency, user_id, category_id, donation_date, status, transaction_id) VALUES (?, ?, ?, ?, NOW(), ?, ?)");
        $stmt->execute([$amount, $currency, $user_id, $category_id, $status, $transaction_id]);

        $donation_id = $pdo->lastInsertId();

        // Envoi d'un email de confirmation si l'utilisateur est connecté
        if ($user_id) {
            $user_stmt = $pdo->prepare("SELECT email, full_name FROM users WHERE id = ?");
            $user_stmt->execute([$user_id]);
            $user = $user_stmt->fetch();
            
            if ($user) {
                $email_body = "<h1>Merci, {$user['full_name']} !</h1>
                               <p>Votre don généreux de <strong>" . number_format($amount, 2, ',', ' ') . " €</strong> a bien été reçu.</p>
                               <p><strong>ID de Transaction :</strong> $transaction_id</p>
                               <p>Grâce à vous, nous pouvons continuer notre mission d'aide aux sans-abris.</p>";
                sendHopeMail($user['email'], "Confirmation de votre don - HopeBridge", $email_body);
            }
        }

        echo json_encode([
            'success' => true, 
            'message' => 'Merci pour votre don ! Votre générosité nous aide énormément.',
            'transaction_id' => $transaction_id,
            'amount' => $amount
        ]);

    } catch (PDOException $e) {
        // Fallback pour les anciennes versions de la table si nécessaire
        try {
            $stmt = $pdo->prepare("INSERT INTO donations (amount, currency, user_id) VALUES (?, ?, ?)");
            $stmt->execute([$amount, $currency, $user_id]);
            echo json_encode(['success' => true, 'message' => 'Don enregistré avec succès (mode réduit).']);
        } catch (PDOException $ex) {
            echo json_encode(['success' => false, 'message' => 'Erreur serveur : ' . $ex->getMessage()]);
        }
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Méthode non autorisée.']);
}
?>
