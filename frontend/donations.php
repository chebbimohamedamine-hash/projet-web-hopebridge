<?php session_start(); ?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HopeBridge | Faire un Don</title>
    <link rel="stylesheet" href="style.css?v=5">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
            overflow-x: hidden;
        }

        /* ── VIDEO BACKGROUND ── */
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
                rgba(30, 27, 75, 0.82) 0%,
                rgba(67, 20, 112, 0.75) 50%,
                rgba(2, 6, 23, 0.88) 100%);
            transition: background 0.5s;
        }



        /* ── NAVBAR ── */
        .navbar {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 100;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 1.2rem 4%;
            background: rgba(0, 0, 0, 0.2);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }

        .nav-logo {
            display: flex;
            align-items: center;
            gap: 0.6rem;
            color: #ffffff;
            font-size: 1.3rem;
            font-weight: 800;
            text-decoration: none;
        }

        .nav-logo i { color: #fbbf24; }

        .nav-actions {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .nav-link {
            color: rgba(255,255,255,0.85);
            text-decoration: none;
            font-weight: 600;
            font-size: 0.9rem;
            transition: color 0.2s;
        }

        .nav-link:hover { color: #fbbf24; }



        /* ── PAGE CONTENT ── */
        .page-content {
            position: relative;
            z-index: 10;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 7rem 4% 4rem;
            gap: 4rem;
        }

        /* ── LEFT IMPACT PANEL ── */
        .impact-panel {
            flex: 1;
            max-width: 480px;
            color: #ffffff;
        }

        .impact-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: rgba(251, 191, 36, 0.2);
            border: 1px solid rgba(251, 191, 36, 0.5);
            color: #fbbf24;
            padding: 0.4rem 1rem;
            border-radius: 20px;
            font-size: 0.82rem;
            font-weight: 700;
            margin-bottom: 1.5rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .impact-panel h1 {
            font-size: 3rem;
            font-weight: 800;
            line-height: 1.15;
            margin-bottom: 1.5rem;
            text-shadow: 0 2px 20px rgba(0,0,0,0.3);
        }

        .impact-panel h1 span {
            color: #fbbf24;
        }

        .impact-panel p {
            font-size: 1.1rem;
            color: rgba(255,255,255,0.85);
            line-height: 1.7;
            margin-bottom: 2.5rem;
        }

        .impact-stats {
            display: flex;
            gap: 2rem;
            flex-wrap: wrap;
        }

        .impact-stat {
            display: flex;
            flex-direction: column;
            gap: 0.2rem;
        }

        .impact-stat .num {
            font-size: 2rem;
            font-weight: 800;
            color: #fbbf24;
        }

        .impact-stat .lbl {
            font-size: 0.8rem;
            color: rgba(255,255,255,0.7);
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        /* ── DONATION CARD ── */
        .donation-card {
            width: 420px;
            flex-shrink: 0;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 24px;
            padding: 2.5rem;
            box-shadow: 0 25px 50px rgba(0,0,0,0.3);
            transition: all 0.5s;
        }



        .donation-card h2 {
            color: #ffffff;
            font-size: 1.6rem;
            font-weight: 800;
            margin-bottom: 0.4rem;
        }

        .donation-card .card-subtitle {
            color: rgba(255,255,255,0.7);
            font-size: 0.9rem;
            margin-bottom: 2rem;
        }

        /* Amount Quick Select */
        .amount-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 0.8rem;
            margin-bottom: 1rem;
        }

        .amount-btn {
            padding: 0.8rem;
            border: 1.5px solid rgba(255,255,255,0.25);
            border-radius: 12px;
            background: transparent;
            color: #ffffff;
            font-weight: 700;
            font-family: 'Inter', sans-serif;
            font-size: 1rem;
            cursor: pointer;
            transition: all 0.25s;
        }

        .amount-btn:hover,
        .amount-btn.active {
            background: rgba(109, 40, 217, 0.5);
            border-color: #7c3aed;
        }



        /* Form Elements */
        .form-group-don {
            margin-bottom: 1.2rem;
        }

        .form-group-don label {
            display: block;
            color: rgba(255,255,255,0.8);
            font-size: 0.85rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        .input-don {
            width: 100%;
            padding: 0.85rem 1rem;
            background: rgba(255,255,255,0.1);
            border: 1.5px solid rgba(255,255,255,0.2);
            border-radius: 12px;
            color: #ffffff;
            font-family: 'Inter', sans-serif;
            font-size: 1rem;
            outline: none;
            transition: all 0.3s;
        }

        .input-don::placeholder { color: rgba(255,255,255,0.4); }

        .input-don:focus {
            border-color: rgba(255,255,255,0.6);
            background: rgba(255,255,255,0.15);
        }



        .select-don {
            width: 100%;
            padding: 0.85rem 1rem;
            background: rgba(255,255,255,0.1);
            border: 1.5px solid rgba(255,255,255,0.2);
            border-radius: 12px;
            color: #ffffff;
            font-family: 'Inter', sans-serif;
            font-size: 0.95rem;
            outline: none;
            cursor: pointer;
            transition: all 0.3s;
        }

        .select-don option { background: #1e293b; color: #f8fafc; }

        .btn-don {
            width: 100%;
            padding: 1rem;
            background: linear-gradient(135deg, #6d28d9, #4c1d95);
            color: #ffffff;
            border: none;
            border-radius: 14px;
            font-size: 1.05rem;
            font-weight: 800;
            font-family: 'Inter', sans-serif;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.6rem;
            transition: all 0.3s;
            margin-top: 0.5rem;
            letter-spacing: 0.02em;
        }

        .btn-don:hover {
            transform: translateY(-2px);
            box-shadow: 0 15px 30px rgba(109, 40, 217, 0.5);
        }



        .secure-note {
            text-align: center;
            color: rgba(255,255,255,0.5);
            font-size: 0.78rem;
            margin-top: 1rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.4rem;
        }

        /* ── MODAL ── */
        @keyframes modalIn {
            from { transform: scale(0.9); opacity: 0; }
            to { transform: scale(1); opacity: 1; }
        }
        @keyframes spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }
        .loading { opacity: 0.7; pointer-events: none; }
        .loading i { animation: spin 1s linear infinite; }

        /* ── RESPONSIVE ── */
        @media (max-width: 900px) {
            .page-content { flex-direction: column; gap: 2rem; padding-top: 6rem; }
            .impact-panel { max-width: 100%; text-align: center; }
            .impact-panel h1 { font-size: 2rem; }
            .impact-stats { justify-content: center; }
            .donation-card { width: 100%; max-width: 420px; }
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
            <a href="volunteer.php" class="nav-link">Bénévole</a>
            <?php if(isset($_SESSION['user_id'])): ?>
                <span style="color: #fbbf24; font-weight: 700; font-size:0.9rem;">
                    Salut, <?php echo htmlspecialchars($_SESSION['user_name']); ?>
                </span>
                <?php if($_SESSION['user_role'] == 'admin'): ?>
                    <a href="../backend/dashboard.php" class="nav-link">
                        <i class="fas fa-user-shield"></i> Dashboard
                    </a>
                <?php endif; ?>
                <a href="../logout.php" style="color: #f87171; font-weight:600; text-decoration:none; font-size:0.9rem;">Déconnexion</a>
            <?php else: ?>
                <a href="login.php" class="nav-link" style="color:#fbbf24;">Connexion</a>
            <?php endif; ?>
        </div>
    </nav>

    <!-- PAGE CONTENT -->
    <div class="page-content">

        <!-- LEFT: Impact message -->
        <div class="impact-panel">
            <div class="impact-badge">
                <i class="fas fa-heart"></i> Chaque Don Compte
            </div>
            <h1>Votre Générosité<br>Change des <span>Vies</span></h1>
            <p>
                Grâce à votre soutien, nous pouvons offrir un repas chaud, un toit sûr et un avenir à des milliers de personnes en détresse. Un simple don peut tout changer.
            </p>
            <div class="impact-stats">
                <div class="impact-stat">
                    <span class="num">15k+</span>
                    <span class="lbl">Repas Servis</span>
                </div>
                <div class="impact-stat">
                    <span class="num">1,200</span>
                    <span class="lbl">Personnes Logées</span>
                </div>
                <div class="impact-stat">
                    <span class="num">85%</span>
                    <span class="lbl">Réinsertion</span>
                </div>
            </div>
        </div>

        <!-- RIGHT: Donation Form Card -->
        <div class="donation-card">
            <h2>Faire un Don <i class="fas fa-heart" style="color:#fbbf24;"></i></h2>
            <p class="card-subtitle">Sécurisé · Anonyme possible · 100% reversé</p>

            <form id="donationForm">
                <!-- STEP 1: Amount & Payment -->
                <div id="step1">
                    <div class="amount-grid">
                        <button type="button" class="amount-btn" onclick="setAmount(10)">10 €</button>
                        <button type="button" class="amount-btn" onclick="setAmount(25)">25 €</button>
                        <button type="button" class="amount-btn" onclick="setAmount(50)">50 €</button>
                        <button type="button" class="amount-btn" onclick="setAmount(100)">100 €</button>
                        <button type="button" class="amount-btn" onclick="setAmount(200)">200 €</button>
                        <button type="button" class="amount-btn" onclick="setAmount(500)">500 €</button>
                    </div>

                    <div class="form-group-don">
                        <label><i class="fas fa-euro-sign"></i> Montant personnalisé (€)</label>
                        <input type="number" id="custom_amount" name="amount" class="input-don"
                               step="0.01" placeholder="Ou entrez votre montant..." required>
                    </div>

                    <div class="form-group-don">
                        <label><i class="fas fa-tag"></i> Affecter mon don à</label>
                        <select name="category_id" class="select-don">
                            <option value="1">🍽️ Repas d'urgence</option>
                            <option value="2">🏠 Hébergement</option>
                            <option value="3">💊 Soins Médicaux</option>
                        </select>
                    </div>

                    <div class="form-group-don">
                        <label><i class="fas fa-credit-card"></i> Mode de paiement</label>
                        <select name="payment_method" id="payment_method" class="select-don" required>
                            <option value="">Sélectionnez un mode de paiement</option>
                            <option value="card">💳 Carte Bancaire (Visa, Mastercard)</option>
                            <option value="paypal">🅿️ PayPal</option>
                            <option value="transfer">🏦 Virement Bancaire</option>
                            <option value="apple_pay">🍎 Apple Pay / Google Pay</option>
                        </select>
                    </div>

                    <button type="button" class="btn-don" onclick="nextStep()">
                        <span>Continuer</span>
                        <i class="fas fa-arrow-right"></i>
                    </button>
                </div>

                <!-- STEP 2: Donor Details -->
                <div id="step2" style="display: none; animation: slideIn 0.4s ease-out;">
                    <div class="form-group-don">
                        <label><i class="fas fa-user"></i> Prénom</label>
                        <input type="text" name="first_name" class="input-don" placeholder="Votre prénom" required
                               value="<?php echo isset($_SESSION['user_name']) ? explode(' ', $_SESSION['user_name'])[0] : ''; ?>">
                    </div>
                    <div class="form-group-don">
                        <label><i class="fas fa-user-tag"></i> Nom</label>
                        <input type="text" name="last_name" class="input-don" placeholder="Votre nom" required
                               value="<?php echo isset($_SESSION['user_name']) && strpos($_SESSION['user_name'], ' ') !== false ? substr($_SESSION['user_name'], strpos($_SESSION['user_name'], ' ') + 1) : ''; ?>">
                    </div>
                    <div class="form-group-don">
                        <label><i class="fas fa-envelope"></i> Adresse Email</label>
                        <input type="email" name="email" class="input-don" placeholder="votre@email.com" required
                               value="<?php echo isset($_SESSION['user_email']) ? $_SESSION['user_email'] : ''; ?>">
                    </div>
                    <div class="form-group-don">
                        <label><i class="fas fa-phone"></i> Téléphone (Optionnel)</label>
                        <input type="tel" name="phone" class="input-don" placeholder="+216 -- --- ---">
                    </div>

                    <div style="display: flex; gap: 1rem; margin-top: 1.5rem;">
                        <button type="button" class="btn-don" style="background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.2);" onclick="prevStep()">
                            <i class="fas fa-arrow-left"></i>
                        </button>
                        <button type="submit" id="submitBtn" class="btn-don" style="flex: 1;">
                            <span>Confirmer le Don</span>
                            <i class="fas fa-heart"></i>
                        </button>
                    </div>
                </div>

                <p class="secure-note">
                    <i class="fas fa-lock"></i> Paiement 100% sécurisé · Reçu fiscal disponible
                </p>
            </form>
        </div>
    </div>

    <!-- SUCCESS MODAL -->
    <div id="successModal" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.7); backdrop-filter: blur(8px); z-index: 1000; align-items: center; justify-content: center;">
        <div style="background: #1e293b; border: 1px solid rgba(212,175,55,0.3); padding: 3rem; border-radius: 24px; text-align: center; max-width: 450px; width: 90%; box-shadow: 0 25px 50px rgba(0,0,0,0.5); animation: modalIn 0.4s cubic-bezier(0.16, 1, 0.3, 1);">
            <div style="width: 80px; height: 80px; background: rgba(34,197,94,0.15); color: #22c55e; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.5rem; font-size: 2.5rem; border: 2px solid #22c55e;">
                <i class="fas fa-check"></i>
            </div>
            <h3 style="font-size: 1.5rem; color: #d4af37; margin-bottom: 1rem;">Merci infiniment ! 🙏</h3>
            <p id="modalMsg" style="color: #94a3b8; line-height: 1.6; margin-bottom: 2rem;"></p>
            <button onclick="window.location.href='index.php'" style="width:100%; padding:1rem; background: linear-gradient(135deg, #0d9488, #0f766e); color:white; border:none; border-radius:12px; font-weight:800; font-size:1rem; cursor:pointer;">
                Retour à l'accueil
            </button>
        </div>
    </div>

    <script>
        // Step navigation
        function nextStep() {
            const amount = document.getElementById('custom_amount').value;
            const payment = document.getElementById('payment_method').value;
            
            if (!amount || amount <= 0) {
                alert('Veuillez entrer un montant valide.');
                return;
            }
            if (!payment) {
                alert('Veuillez choisir un mode de paiement.');
                return;
            }

            document.getElementById('step1').style.display = 'none';
            document.getElementById('step2').style.display = 'block';
        }

        function prevStep() {
            document.getElementById('step2').style.display = 'none';
            document.getElementById('step1').style.display = 'block';
        }

        // Quick amount buttons
        function setAmount(val) {
            document.getElementById('custom_amount').value = val;
            document.querySelectorAll('.amount-btn').forEach(b => b.classList.remove('active'));
            event.target.classList.add('active');
        }

        // Form submission
        document.getElementById('donationForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            const btn = document.getElementById('submitBtn');
            const btnText = btn.querySelector('span');
            const btnIcon = btn.querySelector('i');

            btn.classList.add('loading');
            btnText.textContent = 'Traitement sécurisé...';
            btnIcon.className = 'fas fa-circle-notch fa-spin';

            const formData = new FormData(e.target);

            try {
                const response = await fetch('../donation_post.php', {
                    method: 'POST',
                    body: formData
                });
                const result = await response.json();

                if (result.success) {
                    document.getElementById('modalMsg').textContent = result.message;
                    document.getElementById('successModal').style.display = 'flex';
                } else {
                    alert('Erreur : ' + result.message);
                    btn.classList.remove('loading');
                    btnText.textContent = 'Confirmer le Don';
                    btnIcon.className = 'fas fa-heart';
                }
            } catch (error) {
                console.error('Erreur:', error);
                alert('Une erreur est survenue lors du traitement.');
                btn.classList.remove('loading');
                btnText.textContent = 'Confirmer le Don';
                btnIcon.className = 'fas fa-heart';
            }
        });


    </script>
</body>
</html>
