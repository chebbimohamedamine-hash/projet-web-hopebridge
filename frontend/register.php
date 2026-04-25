<?php session_start(); ?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HopeBridge | Créer un Compte</title>
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
            overflow-x: hidden;
            background: #020617;
        }

        /* VIDEO BACKGROUND */
        .video-bg {
            position: fixed;
            inset: 0;
            z-index: 0;
            overflow: hidden;
        }

        .video-bg video {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .video-overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg,
                rgba(6, 78, 59, 0.80) 0%,
                rgba(5, 46, 22, 0.75) 50%,
                rgba(2, 6, 23, 0.90) 100%);
            transition: background 0.5s;
        }



        /* NAVBAR */
        .navbar {
            position: fixed;
            top: 0; left: 0; right: 0;
            z-index: 100;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 1.1rem 4%;
            background: rgba(0,0,0,0.25);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid rgba(255,255,255,0.08);
        }

        .nav-logo {
            display: flex;
            align-items: center;
            gap: 0.6rem;
            color: #fff;
            font-size: 1.3rem;
            font-weight: 800;
            text-decoration: none;
        }

        .nav-logo i { color: #fbbf24; }

        .nav-actions { display: flex; align-items: center; gap: 1rem; }

        .nav-link {
            color: rgba(255,255,255,0.8);
            text-decoration: none;
            font-weight: 600;
            font-size: 0.9rem;
            transition: color 0.2s;
        }
        .nav-link:hover { color: #fbbf24; }



        /* PAGE LAYOUT */
        .page-content {
            position: relative;
            z-index: 10;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 4rem;
            padding: 7rem 4% 4rem;
        }

        /* LEFT PANEL */
        .left-panel {
            flex: 1;
            max-width: 460px;
            color: #ffffff;
        }

        .welcome-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: rgba(251,191,36,0.15);
            border: 1px solid rgba(251,191,36,0.4);
            color: #fbbf24;
            padding: 0.4rem 1rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 700;
            margin-bottom: 1.5rem;
            text-transform: uppercase;
            letter-spacing: 0.06em;
        }

        .left-panel h1 {
            font-size: 2.8rem;
            font-weight: 800;
            line-height: 1.2;
            margin-bottom: 1.2rem;
            text-shadow: 0 2px 20px rgba(0,0,0,0.3);
        }

        .left-panel h1 span { color: #fbbf24; }

        .left-panel p {
            color: rgba(255,255,255,0.82);
            font-size: 1rem;
            line-height: 1.75;
            margin-bottom: 2rem;
        }

        .benefits-list {
            list-style: none;
            display: flex;
            flex-direction: column;
            gap: 0.8rem;
        }

        .benefits-list li {
            display: flex;
            align-items: center;
            gap: 0.7rem;
            color: rgba(255,255,255,0.85);
            font-size: 0.95rem;
        }

        .benefits-list li .icon-check {
            width: 28px;
            height: 28px;
            border-radius: 50%;
            background: rgba(251,191,36,0.2);
            border: 1.5px solid rgba(251,191,36,0.5);
            color: #fbbf24;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.75rem;
            flex-shrink: 0;
        }

        /* REGISTER CARD */
        .register-card {
            width: 440px;
            flex-shrink: 0;
            background: rgba(255,255,255,0.08);
            backdrop-filter: blur(22px);
            border: 1px solid rgba(255,255,255,0.15);
            border-radius: 24px;
            padding: 2.5rem;
            box-shadow: 0 30px 60px rgba(0,0,0,0.4);
            transition: all 0.4s;
        }



        .register-card h2 {
            color: #fff;
            font-size: 1.6rem;
            font-weight: 800;
            margin-bottom: 0.3rem;
        }

        .card-sub {
            color: rgba(255,255,255,0.6);
            font-size: 0.88rem;
            margin-bottom: 2rem;
        }

        .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; }

        .fg {
            margin-bottom: 1.2rem;
        }

        .fg label {
            display: block;
            color: rgba(255,255,255,0.75);
            font-size: 0.83rem;
            font-weight: 600;
            margin-bottom: 0.4rem;
        }

        .inp-wrap { position: relative; }

        .inp-wrap i {
            position: absolute;
            left: 0.9rem;
            top: 50%;
            transform: translateY(-50%);
            color: rgba(255,255,255,0.35);
            font-size: 0.9rem;
            pointer-events: none;
        }

        .inp {
            width: 100%;
            padding: 0.78rem 0.9rem 0.78rem 2.5rem;
            background: rgba(255,255,255,0.08);
            border: 1.5px solid rgba(255,255,255,0.15);
            border-radius: 11px;
            color: #fff;
            font-family: 'Inter', sans-serif;
            font-size: 0.92rem;
            outline: none;
            transition: all 0.3s;
        }

        .inp::placeholder { color: rgba(255,255,255,0.3); }

        .inp:focus {
            border-color: rgba(255,255,255,0.5);
            background: rgba(255,255,255,0.12);
        }



        .sel {
            width: 100%;
            padding: 0.78rem 0.9rem;
            background: rgba(255,255,255,0.08);
            border: 1.5px solid rgba(255,255,255,0.15);
            border-radius: 11px;
            color: #fff;
            font-family: 'Inter', sans-serif;
            font-size: 0.92rem;
            outline: none;
            cursor: pointer;
            transition: all 0.3s;
        }

        .sel option { background: #1e293b; color: #f8fafc; }

        .error-field {
            color: #f87171;
            font-size: 0.78rem;
            margin-top: 0.3rem;
            display: none;
        }

        .btn-reg {
            width: 100%;
            padding: 0.95rem;
            background: linear-gradient(135deg, #10b981, #059669);
            color: #fff;
            border: none;
            border-radius: 12px;
            font-size: 1rem;
            font-weight: 800;
            font-family: 'Inter', sans-serif;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            transition: all 0.3s;
            margin-top: 0.5rem;
        }

        .btn-reg:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 28px rgba(16,185,129,0.45);
        }



        .login-link {
            text-align: center;
            color: rgba(255,255,255,0.55);
            font-size: 0.88rem;
            margin-top: 1.2rem;
        }

        .login-link a {
            color: #fbbf24;
            font-weight: 700;
            text-decoration: none;
        }



        .back-link {
            text-align: center;
            margin-top: 1.2rem;
        }

        .back-link a {
            color: rgba(255,255,255,0.4);
            font-size: 0.82rem;
            text-decoration: none;
        }

        /* STRENGTH BAR */
        .strength-bar {
            display: flex;
            gap: 4px;
            margin-top: 6px;
        }

        .strength-segment {
            height: 4px;
            flex: 1;
            border-radius: 2px;
            background: rgba(255,255,255,0.1);
            transition: background 0.3s;
        }

        /* RESPONSIVE */
        @media (max-width: 900px) {
            .page-content { flex-direction: column; gap: 2rem; padding-top: 6rem; }
            .left-panel { max-width: 100%; text-align: center; }
            .left-panel h1 { font-size: 2rem; }
            .benefits-list { align-items: center; }
            .register-card { width: 100%; max-width: 440px; }
            .form-row { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>

    <!-- VIDEO BACKGROUND -->
    <div class="video-bg">
        <video autoplay muted loop playsinline>
            <source src="https://www.w3schools.com/howto/rain.mp4" type="video/mp4">
        </video>
        <div class="video-overlay"></div>
    </div>

    <!-- NAVBAR -->
    <nav class="navbar">
        <a href="index.php" class="nav-logo">
            <i class="fas fa-hand-holding-heart"></i>
            HopeBridge
        </a>
        <div class="nav-actions">

            <a href="index.php" class="nav-link">Accueil</a>
            <a href="login.php" class="nav-link">Connexion</a>
            <!-- Google Translate Widget -->
            <div id="google_translate_element" style="margin-left: 10px;"></div>
        </div>
    </nav>

    <!-- CONTENT -->
    <div class="page-content">

        <!-- LEFT: Why join -->
        <div class="left-panel">
            <div class="welcome-badge">
                <i class="fas fa-star"></i> Rejoignez-nous
            </div>
            <h1>Ensemble, nous<br>faisons la <span>différence</span></h1>
            <p>
                En créant votre compte, vous rejoignez une communauté de milliers de personnes engagées dans la lutte contre la pauvreté et l'exclusion.
            </p>
            <ul class="benefits-list">
                <li>
                    <div class="icon-check"><i class="fas fa-check"></i></div>
                    Suivre l'impact de vos dons en temps réel
                </li>
                <li>
                    <div class="icon-check"><i class="fas fa-check"></i></div>
                    Recevoir vos reçus fiscaux automatiquement
                </li>
                <li>
                    <div class="icon-check"><i class="fas fa-check"></i></div>
                    Accéder à des rapports exclusifs sur nos actions
                </li>
                <li>
                    <div class="icon-check"><i class="fas fa-check"></i></div>
                    Rejoindre notre réseau de bénévoles engagés
                </li>
            </ul>
        </div>

        <!-- RIGHT: Register Form -->
        <div class="register-card">
            <h2>Créer un compte 🌟</h2>
            <p class="card-sub">Gratuit · Sécurisé · En 30 secondes</p>

            <form id="registerForm" action="../register_post.php" method="POST">

                <div class="fg">
                    <label><i class="fas fa-user" style="margin-right:0.3rem;"></i> Nom complet</label>
                    <div class="inp-wrap">
                        <i class="fas fa-user"></i>
                        <input type="text" name="full_name" class="inp" placeholder="Jean Dupont" required>
                    </div>
                    <div class="error-field" id="err-full_name"></div>
                </div>

                <div class="fg">
                    <label><i class="fas fa-envelope" style="margin-right:0.3rem;"></i> Adresse Email</label>
                    <div class="inp-wrap">
                        <i class="fas fa-envelope"></i>
                        <input type="email" name="email" class="inp" placeholder="votre@email.com" required>
                    </div>
                    <div class="error-field" id="err-email"></div>
                </div>

                <div class="fg">
                    <label><i class="fas fa-id-badge" style="margin-right:0.3rem;"></i> Je m'inscris en tant que</label>
                    <select name="role" class="sel">
                        <option value="donor">💚 Donateur</option>
                        <option value="volunteer">🙋 Bénévole</option>
                        <option value="admin">🛡️ Administrateur (Test)</option>
                    </select>
                </div>

                <div class="fg">
                    <label><i class="fas fa-lock" style="margin-right:0.3rem;"></i> Mot de passe</label>
                    <div class="inp-wrap">
                        <i class="fas fa-lock"></i>
                        <input type="password" name="password" id="pwdInput" class="inp" placeholder="Minimum 6 caractères" required oninput="checkStrength(this.value)">
                    </div>
                    <div class="strength-bar">
                        <div class="strength-segment" id="s1"></div>
                        <div class="strength-segment" id="s2"></div>
                        <div class="strength-segment" id="s3"></div>
                        <div class="strength-segment" id="s4"></div>
                    </div>
                    <div class="error-field" id="err-password"></div>
                </div>

                <!-- Google reCAPTCHA -->
                <div class="fg" style="display: flex; justify-content: center; margin-top: 1rem;">
                    <div class="g-recaptcha" data-sitekey="6LeMQr4sAAAAADJdtlK6lc9ZHrQsWnYEhJFWblYC"></div>
                </div>

                <button type="submit" class="btn-reg">
                    <i class="fas fa-user-plus"></i>
                    <span>Créer mon compte</span>
                </button>
            </form>

            <p class="login-link">
                Déjà un compte ? <a href="login.php">Se connecter</a>
            </p>
            <div class="back-link">
                <a href="index.php"><i class="fas fa-arrow-left"></i> Retour à l'accueil</a>
            </div>
        </div>
    </div>

    <script src="js/validator.js"></script>
    <script>
        // Password strength indicator
        function checkStrength(val) {
            const segs = [document.getElementById('s1'), document.getElementById('s2'),
                          document.getElementById('s3'), document.getElementById('s4')];
            let strength = 0;
            if (val.length >= 6) strength++;
            if (val.length >= 10) strength++;
            if (/[A-Z]/.test(val) && /[0-9]/.test(val)) strength++;
            if (/[^A-Za-z0-9]/.test(val)) strength++;

            const colors = ['#ef4444', '#f97316', '#fbbf24', '#22c55e'];
            segs.forEach((s, i) => {
                s.style.background = i < strength ? colors[strength - 1] : 'rgba(255,255,255,0.1)';
            });
        }

        // Form validation
        const v = typeof Validator !== 'undefined' ? new Validator('registerForm') : null;
        document.getElementById('registerForm').onsubmit = function(e) {
            const fullName = document.querySelector('[name="full_name"]').value;
            const email = document.querySelector('[name="email"]').value;
            const password = document.querySelector('[name="password"]').value;
            let hasError = false;

            if (fullName.length < 3) {
                const el = document.getElementById('err-full_name');
                el.textContent = 'Nom trop court (min. 3 caractères).';
                el.style.display = 'block';
                hasError = true;
            } else { document.getElementById('err-full_name').style.display = 'none'; }

            if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
                const el = document.getElementById('err-email');
                el.textContent = 'Adresse email invalide.';
                el.style.display = 'block';
                hasError = true;
            } else { document.getElementById('err-email').style.display = 'none'; }

            if (password.length < 6) {
                const el = document.getElementById('err-password');
                el.textContent = 'Le mot de passe doit contenir au moins 6 caractères.';
                el.style.display = 'block';
                hasError = true;
            } else { document.getElementById('err-password').style.display = 'none'; }

            if (hasError) e.preventDefault();
        };


    </script>
</body>
</html>
