<?php 
session_start(); 
if (!isset($_SESSION['reset_email'])) {
    header("Location: forgot_password.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HopeBridge | Vérifier le code</title>
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
            <h2 style="font-family: 'Outfit', sans-serif; font-weight: 800; color: var(--text-dark); margin-bottom: 0.5rem;">Vérification</h2>
            <p style="color: var(--text-light); font-size: 0.95rem;">Entrez le code à 6 chiffres envoyé à <br><strong style="color: var(--text-dark);"><?php echo htmlspecialchars($_SESSION['reset_email']); ?></strong></p>
        </div>

        <?php if(isset($_GET['error'])): ?>
            <div style="background: #fee2e2; color: #ef4444; padding: 0.85rem; border-radius: 12px; text-align: center; margin-bottom: 1.5rem; font-weight: 600; font-size: 0.9rem; border: 1px solid rgba(239, 68, 68, 0.2);">
                <i class="fas fa-exclamation-circle" style="margin-right: 0.5rem;"></i> Code incorrect ou expiré.
            </div>
        <?php endif; ?>

        <form action="../verify_code_post.php" method="POST">
            <div class="form-group" style="text-align: center;">
                <input type="text" name="code" maxlength="6" placeholder="000000" required 
                       style="font-size: 2.5rem; text-align: center; letter-spacing: 0.6rem; font-weight: 800; border: 2.5px solid #e2e8f0; border-radius: 16px; width: 100%; padding: 1.2rem; color: var(--text-dark); transition: all 0.3s; background: #f8fafc;"
                       onfocus="this.style.borderColor='var(--primary)'; this.style.background='white'; this.style.boxShadow='0 0 0 4px rgba(13, 148, 136, 0.1)'" 
                       onblur="this.style.borderColor='#e2e8f0'; this.style.background='#f8fafc'; this.style.boxShadow='none'">
            </div>
            <button type="submit" style="width: 100%; margin-top: 2rem; border: none; cursor: pointer; padding: 1.1rem; background: var(--primary); color: white; border-radius: 12px; font-weight: 800; font-size: 1.1rem; transition: all 0.3s; box-shadow: 0 4px 12px rgba(13, 148, 136, 0.3);"
                    onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 6px 15px rgba(13, 148, 136, 0.4)'"
                    onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 12px rgba(13, 148, 136, 0.3)'">
                Vérifier le code
            </button>
        </form>

        <p style="text-align: center; margin-top: 2rem; color: var(--text-light); font-size: 0.9rem;">
            Pas reçu ? <a href="../forgot_password_post.php?resend=1" style="color: var(--primary); font-weight: 700; text-decoration: none; border-bottom: 2px solid transparent; transition: all 0.3s;"
                           onmouseover="this.style.borderBottomColor='var(--primary)'"
                           onmouseout="this.style.borderBottomColor='transparent'">Renvoyer le code</a>
        </p>
    </div>
</body>
</html>
