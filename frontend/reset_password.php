<?php 
session_start(); 
if (!isset($_SESSION['code_verified']) || !$_SESSION['code_verified']) {
    header("Location: verify_code.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HopeBridge | Nouveau mot de passe</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body style="background: linear-gradient(135deg, #f9fafb 0%, #e5e7eb 100%); min-height: 100vh; display: flex; align-items: center; justify-content: center;">

    <div class="card" style="background: white; padding: 3rem; border-radius: 24px; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1), 0 10px 10px -5px rgba(0,0,0,0.04); width: 100%; max-width: 450px; border: 1px solid rgba(0,0,0,0.05);">
        <div style="text-align: center; margin-bottom: 2rem;">
            <div class="logo" style="justify-content: center; font-size: 2rem; margin-bottom: 1rem; display: flex; align-items: center; gap: 0.5rem; color: var(--primary); font-weight: 800;">
                <i class="fas fa-hand-holding-heart"></i>
                <span>HopeBridge</span>
            </div>
            <h2 style="font-family: 'Outfit', sans-serif; font-weight: 800; color: var(--text-dark); margin-bottom: 0.5rem;">Nouveau mot de passe</h2>
            <p style="color: var(--text-light); font-size: 0.95rem;">Vérification réussie. Choisissez un nouveau mot de passe sécurisé.</p>
        </div>

        <form action="../reset_password_post.php" method="POST">
            <div class="form-group" style="margin-bottom: 1.5rem;">
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: var(--text-dark);"><i class="fas fa-lock" style="margin-right: 0.5rem; color: var(--primary);"></i> Nouveau mot de passe</label>
                <input type="password" name="password" placeholder="••••••••" required 
                       style="width: 100%; padding: 0.85rem; border-radius: 12px; border: 2px solid #e2e8f0; font-size: 1rem; transition: border-color 0.3s;"
                       onfocus="this.style.borderColor='var(--primary)'" onblur="this.style.borderColor='#e2e8f0'">
            </div>
            <div class="form-group" style="margin-bottom: 1.5rem;">
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: var(--text-dark);"><i class="fas fa-check-circle" style="margin-right: 0.5rem; color: var(--primary);"></i> Confirmer le mot de passe</label>
                <input type="password" name="confirm_password" placeholder="••••••••" required 
                       style="width: 100%; padding: 0.85rem; border-radius: 12px; border: 2px solid #e2e8f0; font-size: 1rem; transition: border-color 0.3s;"
                       onfocus="this.style.borderColor='var(--primary)'" onblur="this.style.borderColor='#e2e8f0'">
            </div>
            <button type="submit" style="width: 100%; margin-top: 1rem; border: none; cursor: pointer; padding: 1rem; background: var(--primary); color: white; border-radius: 12px; font-weight: 800; font-size: 1rem; transition: all 0.3s; box-shadow: 0 4px 12px rgba(13, 148, 136, 0.3);"
                    onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 6px 15px rgba(13, 148, 136, 0.4)'"
                    onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 12px rgba(13, 148, 136, 0.3)'">
                Réinitialiser le mot de passe
            </button>
        </form>
    </div>
</body>
</html>
