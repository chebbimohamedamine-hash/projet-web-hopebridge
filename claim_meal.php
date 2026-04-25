<?php
require 'db.php';
require 'mail_helper.php';
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'ngo') {
    die("Accès non autorisé.");
}

$meal_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$ngo_id = $_SESSION['user_id'];

if ($meal_id <= 0) {
    die("ID de repas invalide.");
}

try {
    // Vérifier si le repas est toujours disponible
    $check_stmt = $pdo->prepare("SELECT m.*, u.email as donor_email, u.full_name as donor_name FROM meal_donations m JOIN users u ON m.donor_id = u.id WHERE m.id = ? AND m.status = 'available'");
    $check_stmt->execute([$meal_id]);
    $meal = $check_stmt->fetch();

    if (!$meal) {
        die("Ce repas n'est plus disponible.");
    }

    // Mettre à jour le statut
    $update_stmt = $pdo->prepare("UPDATE meal_donations SET status = 'claimed', ngo_id = ? WHERE id = ?");
    $update_stmt->execute([$ngo_id, $meal_id]);

    // Envoyer un email au donateur (Restaurant)
    $ngo_name = $_SESSION['user_name'];
    $subject = "Votre don de repas a été réservé ! - HopeBridge";
    $body = "<h1>Bonne nouvelle, {$meal['donor_name']} !</h1>
             <p>L'organisation <strong>$ngo_name</strong> vient de réserver votre offre de repas : <strong>{$meal['title']}</strong>.</p>
             <p>Merci de préparer le repas pour la récupération. L'ONG vous contactera sous peu.</p>
             <p>Merci pour votre générosité !</p>";
    
    sendHopeMail($meal['donor_email'], $subject, $body);

    $_SESSION['success_msg'] = "Repas réservé avec succès ! Le donateur a été prévenu.";
    header("Location: frontend/index.php?success=meal_claimed");
    exit();

} catch (PDOException $e) {
    die("Erreur lors de la réservation : " . $e->getMessage());
}
?>
