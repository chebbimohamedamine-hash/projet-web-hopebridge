<?php session_start(); ?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HopeBridge | Mot de passe oublié</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body style="background: linear-gradient(135deg, #f9fafb 0%, #e5e7eb 100%); min-height: 100vh; display: flex; align-items: center; justify-content: center;">

    <div class="card" style="background: white; padding: 3rem; border-radius: 24px; box-shadow: var(--shadow); width: 100%; max-width: 450px;">
        <div style="text-align: center; margin-bottom: 2rem;">
            <div class="logo" style="justify-content: center; font-size: 2rem; margin-bottom: 1rem;">
                <i class="fas fa-hand-holding-heart"></i>
                <a href="index.php" style="text-decoration: none; color: inherit;"><span>HopeBridge</span></a>
            </div>
            <h2>Récupération</h2>
            <p style="color: var(--text-light);">Entrez votre email pour recevoir un nouveau mot de passe.</p>
        </div>

        <?php if(isset($_GET['success'])): ?>
            <p style="color: #4ade80; text-align: center; margin-bottom: 1rem; font-weight: 600;">Succès ! Le mot de passe a été réinitialisé.</p>
            <p style="font-size: 0.8rem; text-align: center; color: var(--text-muted);">En local, vérifiez vos logs (sendmail) ou connectez-vous avec le nouveau code.</p>
        <?php elseif(isset($_GET['error'])): ?>
            <?php if($_GET['error'] == 'not_found'): ?>
                <p style="color: #ef4444; text-align: center; margin-bottom: 1rem; font-weight: 600;">Email non trouvé en base.</p>
            <?php else: ?>
                <p style="color: #4ade80; text-align: center; margin-bottom: 1rem; font-weight: 600;">Succès ! Le mot de passe a été réinitialisé.</p>
                <p style="font-size: 0.8rem; text-align: center; color: var(--text-muted);">Note: L'email est simulé en local. Le mot de passe a été mis à jour en base.</p>
            <?php endif; ?>
        <?php endif; ?>

        <form action="../forgot_password_post.php" method="POST">
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" placeholder="votre@email.com" required>
            </div>
            <button type="submit" class="btn btn-primary" style="width: 100%; margin-top: 1rem;">Envoyer le code</button>
        </form>

        <p style="text-align: center; margin-top: 2rem; color: var(--text-light);">
            <a href="login.php" style="color: var(--primary); font-weight: 600; text-decoration: none;"><i class="fas fa-arrow-left"></i> Retour à la connexion</a>
        </p>
    </div>
</body>
</html>
