<?php session_start(); ?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HopeBridge | Connexion</title>
    <link rel="stylesheet" href="style.css?v=5">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    <!-- Google APIs -->
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>
    <script type="text/javascript">
        function googleTranslateElementInit() {
            new google.translate.TranslateElement({pageLanguage: 'fr', layout: google.translate.TranslateElement.InlineLayout.SIMPLE}, 'google_translate_element');
        }
    </script>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
            display: flex;
            background: #0f172a;
            transition: background 0.3s;
        }

        /* LEFT PANEL */
        .login-left {
            flex: 1;
            background: linear-gradient(135deg, #0d9488 0%, #0f766e 50%, #134e4a 100%);
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 4rem;
            position: relative;
            overflow: hidden;
        }

        .login-left::before {
            content: '';
            position: absolute;
            width: 400px;
            height: 400px;
            background: rgba(255,255,255,0.05);
            border-radius: 50%;
            top: -100px;
            left: -100px;
        }
        .login-left::after {
            content: '';
            position: absolute;
            width: 300px;
            height: 300px;
            background: rgba(255,255,255,0.05);
            border-radius: 50%;
            bottom: -80px;
            right: -80px;
        }

        .brand-logo {
            font-size: 3rem;
            color: #ffffff;
            margin-bottom: 1.5rem;
            position: relative;
            z-index: 1;
        }

        .brand-name {
            font-size: 2.5rem;
            font-weight: 800;
            color: #ffffff;
            margin-bottom: 1rem;
            position: relative;
            z-index: 1;
        }

        .brand-tagline {
            font-size: 1.1rem;
            color: rgba(255,255,255,0.85);
            text-align: center;
            max-width: 350px;
            line-height: 1.7;
            position: relative;
            z-index: 1;
        }

        .stats-preview {
            display: flex;
            gap: 2rem;
            margin-top: 3rem;
            position: relative;
            z-index: 1;
        }

        .stat-item {
            text-align: center;
        }

        .stat-item .num {
            font-size: 1.8rem;
            font-weight: 800;
            color: #fbbf24;
        }

        .stat-item .lbl {
            font-size: 0.75rem;
            color: rgba(255,255,255,0.7);
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        /* RIGHT PANEL */
        .login-right {
            width: 480px;
            min-height: 100vh;
            background: #ffffff;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 3rem;
            position: relative;
            transition: background 0.3s;
        }

        .login-right h2 {
            font-size: 1.9rem;
            font-weight: 800;
            color: #1f2937;
            margin-bottom: 0.5rem;
            transition: color 0.3s;
        }

        .login-right .subtitle {
            color: #6b7280;
            margin-bottom: 2.5rem;
            font-size: 0.95rem;
            transition: color 0.3s;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group label {
            display: block;
            font-weight: 600;
            color: #374151;
            margin-bottom: 0.5rem;
            font-size: 0.9rem;
            transition: color 0.3s;
        }

        .input-wrapper {
            position: relative;
        }

        .input-wrapper i {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: #9ca3af;
            transition: color 0.3s;
        }

        .form-group input {
            width: 100%;
            padding: 0.85rem 1rem 0.85rem 2.8rem;
            border: 2px solid #e5e7eb;
            border-radius: 12px;
            font-size: 0.95rem;
            font-family: 'Inter', sans-serif;
            color: #1f2937;
            background: #f9fafb;
            transition: all 0.3s ease;
            outline: none;
        }

        .form-group input:focus {
            border-color: #0d9488;
            background: #ffffff;
            box-shadow: 0 0 0 4px rgba(13, 148, 136, 0.1);
        }

        .forgot-link {
            text-align: right;
            margin-top: -1rem;
            margin-bottom: 1.5rem;
        }

        .forgot-link a {
            font-size: 0.85rem;
            color: #0d9488;
            font-weight: 600;
            text-decoration: none;
            transition: color 0.3s;
        }

        .forgot-link a:hover { color: #0f766e; }

        .btn-login {
            width: 100%;
            padding: 1rem;
            background: linear-gradient(135deg, #0d9488, #0f766e);
            color: #ffffff;
            border: none;
            border-radius: 12px;
            font-size: 1rem;
            font-weight: 700;
            font-family: 'Inter', sans-serif;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(13, 148, 136, 0.4);
        }

        .divider {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin: 1.8rem 0;
            color: #d1d5db;
            font-size: 0.85rem;
        }
        .divider::before, .divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: #e5e7eb;
        }

        .register-link {
            text-align: center;
            color: #6b7280;
            font-size: 0.9rem;
            margin-top: 1rem;
            transition: color 0.3s;
        }

        .register-link a {
            color: #0d9488;
            font-weight: 700;
            text-decoration: none;
            transition: color 0.3s;
        }

        .error-msg {
            background: #fee2e2;
            color: #dc2626;
            padding: 0.75rem 1rem;
            border-radius: 10px;
            margin-bottom: 1.5rem;
            font-size: 0.9rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }





        @media (max-width: 768px) {
            .login-left { display: none; }
            .login-right { width: 100%; padding: 2rem; }
        }
    </style>
</head>
<body>


    <div id="google_translate_element" style="position: fixed; bottom: 20px; right: 20px; z-index: 9999;"></div>

    <!-- Left Branding Panel -->
    <div class="login-left">
        <div class="brand-logo"><i class="fas fa-hand-holding-heart"></i></div>
        <div class="brand-name">HopeBridge</div>
        <p class="brand-tagline">Rejoignez notre communauté et aidez-nous à redonner dignité et espoir à ceux qui en ont le plus besoin.</p>
        <div class="stats-preview">
            <div class="stat-item">
                <div class="num">15k+</div>
                <div class="lbl">Repas Servis</div>
            </div>
            <div class="stat-item">
                <div class="num">1,200</div>
                <div class="lbl">Personnes Logées</div>
            </div>
            <div class="stat-item">
                <div class="num">500+</div>
                <div class="lbl">Bénévoles</div>
            </div>
        </div>
    </div>

    <!-- Right Form Panel -->
    <div class="login-right">
        <h2>Bon retour ! 👋</h2>
        <p class="subtitle">Connectez-vous pour continuer votre action solidaire.</p>

        <?php if(isset($_GET['error'])): ?>
            <div class="error-msg">
                <i class="fas fa-exclamation-circle"></i> Email ou mot de passe incorrect.
            </div>
        <?php endif; ?>

        <form action="../login_post.php" method="POST">
            <div class="form-group">
                <label>Adresse Email</label>
                <div class="input-wrapper">
                    <i class="fas fa-envelope"></i>
                    <input type="email" name="email" placeholder="votre@email.com" required>
                </div>
            </div>

            <div class="form-group">
                <label>Mot de passe</label>
                <div class="input-wrapper">
                    <i class="fas fa-lock"></i>
                    <input type="password" name="password" id="passwordInput" placeholder="••••••••" required>
                </div>
            </div>

            <div class="forgot-link">
                <a href="forgot_password.php"><i class="fas fa-key"></i> Mot de passe oublié ?</a>
            </div>

            <!-- Google reCAPTCHA -->
            <div style="margin-bottom: 1.5rem; display: flex; justify-content: center;">
                <div class="g-recaptcha" data-sitekey="6LeMQr4sAAAAADJdtlK6lc9ZHrQsWnYEhJFWblYC"></div>
            </div>

            <button type="submit" class="btn-login">
                <i class="fas fa-sign-in-alt"></i> Se connecter
            </button>
        </form>

        <div class="divider">ou</div>

        <p class="register-link">
            Pas encore de compte ? <a href="register.php">Créer un compte</a>
        </p>


        <p style="text-align:center; margin-top: 2rem;">
            <a href="index.php" style="color: #9ca3af; font-size:0.85rem; text-decoration:none;">
                <i class="fas fa-arrow-left"></i> Retour à l'accueil
            </a>
        </p>
    </div>

    <script>

    </script>
</body>
</html>
