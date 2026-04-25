<?php
session_start();
require '../db.php';
require '../mail_helper.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'admin') {
    header("Location: ../frontend/login.php");
    exit();
}

$message_sent = false;
$error = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['send_mail'])) {
    $target = $_POST['target']; // 'volunteers' or 'donors'
    $subject = $_POST['subject'];
    $body = $_POST['body'];

    if (empty($subject) || empty($body)) {
        $error = "Veuillez remplir tous les champs.";
    } else {
        try {
            if ($target == 'volunteers') {
                $stmt = $pdo->query("SELECT u.email FROM users u JOIN volunteers v ON u.id = v.user_id");
            } else {
                $stmt = $pdo->query("SELECT DISTINCT u.email FROM users u JOIN donations d ON u.id = d.user_id");
            }
            
            $emails = $stmt->fetchAll(PDO::FETCH_COLUMN);
            
            foreach ($emails as $email) {
                sendHopeMail($email, $subject, "<h2>Message de l'administration HopeBridge</h2><p>$body</p>");
            }
            
            $message_sent = true;
        } catch (PDOException $e) {
            $error = "Erreur base de données : " . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HopeBridge Admin | Mailing System</title>
    <link rel="stylesheet" href="dashboard-style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style id="admin-fixes">
        /* Force styles for this page */
        .card {
            background: rgba(30, 41, 59, 0.4) !important;
            padding: 3rem !important;
            border-radius: 28px !important;
            border: 1px solid rgba(255,255,255,0.08) !important;
            box-shadow: var(--glass-shadow) !important;
        }

        .form-group {
            margin-bottom: 1.5rem !important;
            width: 100% !important;
        }

        .form-group label {
            display: block !important;
            margin-bottom: 0.8rem !important;
            color: var(--text-muted) !important;
            font-weight: 600 !important;
            font-size: 0.85rem !important;
            text-transform: uppercase !important;
            letter-spacing: 0.05em !important;
        }

        .form-group input, .form-group select, .form-group textarea {
            width: 100% !important;
            display: block !important;
            background: #0f172a !important;
            border: 1px solid var(--border) !important;
            padding: 1rem !important;
            border-radius: 14px !important;
            color: #fff !important;
            font-size: 1rem !important;
            transition: 0.3s !important;
        }

        .form-group input:focus, .form-group select:focus, .form-group textarea:focus {
            border-color: var(--accent-blue) !important;
            box-shadow: 0 0 0 4px rgba(56, 189, 248, 0.1) !important;
            outline: none !important;
        }

        #qrInput {
            background: #0f172a !important;
            border: 1px solid var(--border) !important;
            padding: 1rem !important;
            border-radius: 14px !important;
            color: #fff !important;
        }

        .btn-admin {
            background: var(--accent-blue) !important;
            color: #000 !important;
            font-weight: 800 !important;
            padding: 1rem 2rem !important;
            border-radius: 14px !important;
            cursor: pointer !important;
            border: none !important;
            transition: 0.3s !important;
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            gap: 0.8rem !important;
        }

        .btn-admin:hover {
            transform: translateY(-2px) !important;
            box-shadow: 0 10px 20px rgba(56, 189, 248, 0.2) !important;
        }
    </style>
</head>
<body>

    <aside class="sidebar">
        <div class="sidebar-logo">
            <i class="fas fa-hand-holding-heart"></i>
            <span>HopeBridge</span>
        </div>
        <ul class="menu">
            <li class="menu-item"><a href="dashboard.php" class="menu-link"><i class="fas fa-th-large"></i> Dashboard</a></li>
            <li class="menu-item"><a href="volunteers.php" class="menu-link"><i class="fas fa-users"></i> Bénévoles</a></li>
            <li class="menu-item"><a href="donations.php" class="menu-link"><i class="fas fa-donate"></i> Dons</a></li>
            <li class="menu-item"><a href="shelters.php" class="menu-link"><i class="fas fa-hotel"></i> Centres d'hébergement</a></li>
            <li class="menu-item"><a href="mailing.php" class="menu-link active"><i class="fas fa-envelope-open-text"></i> Mailing</a></li>
        </ul>
    </aside>

    <main class="main-content">
        <header>
            <div>
                <h1>Système de Mailing</h1>
                <p style="color: var(--text-muted);">Envoyez des messages massifs à votre communauté.</p>
            </div>
        </header>

        <section style="max-width: 800px; margin: 0 auto;">
            <?php if ($message_sent): ?>
                <div class="card" style="background: rgba(74, 222, 128, 0.1); color: #4ade80; border-color: rgba(74, 222, 128, 0.2); margin-bottom: 2rem;">
                    <i class="fas fa-check-circle"></i> Emails envoyés avec succès !
                </div>
            <?php endif; ?>

            <?php if ($error): ?>
                <div class="card" style="background: rgba(239, 68, 68, 0.1); color: #f87171; border-color: rgba(239, 68, 68, 0.2); margin-bottom: 2rem;">
                    <i class="fas fa-exclamation-circle"></i> <?php echo $error; ?>
                </div>
            <?php endif; ?>

            <div class="card">
                <form action="" method="POST">
                    <div class="form-group">
                        <label><i class="fas fa-users" style="margin-right: 0.5rem; color: var(--accent-blue);"></i> Destinataires cibles</label>
                        <select name="target">
                            <option value="volunteers">Tous les Bénévoles inscrits</option>
                            <option value="donors">Tous les Donateurs actifs</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label><i class="fas fa-heading" style="margin-right: 0.5rem; color: var(--accent-blue);"></i> Objet du message</label>
                        <input type="text" name="subject" placeholder="Ex: Invitation à notre prochain événement" required>
                    </div>

                    <div class="form-group">
                        <label><i class="fas fa-edit" style="margin-right: 0.5rem; color: var(--accent-blue);"></i> Contenu de l'email</label>
                        <textarea name="body" rows="10" placeholder="Rédigez votre message ici... Le contenu sera envoyé au format HTML." required></textarea>
                    </div>

                    <button type="submit" name="send_mail" class="btn-admin" style="width: 100%; margin-top: 1rem;">
                        <i class="fas fa-paper-plane"></i> Envoyer la campagne de mailing
                    </button>
                </form>
            </div>
        </section>

        <!-- Section QR Code Generation -->
        <header style="margin-top: 5rem;">
            <div class="header-title">
                <h1>Générateur de QR Code</h1>
                <p style="color: var(--text-muted);">Créez des accès rapides vers vos formulaires de dons ou d'inscription.</p>
            </div>
        </header>

        <section style="max-width: 800px; margin: 0 auto 5rem;">
            <div class="card">
                <div style="display: flex; gap: 1.5rem; margin-bottom: 2rem;">
                    <div class="form-group" style="flex: 1; margin-bottom: 0 !important;">
                        <input type="text" id="qrInput" placeholder="Entrez une URL (ex: https://hopebridge.tn/donations)" style="margin-bottom: 0 !important;">
                    </div>
                    <button onclick="generateQR()" class="btn-admin" style="background: var(--accent-orange) !important; color: white !important;">
                        <i class="fas fa-qrcode"></i> Générer
                    </button>
                </div>
                
                <div id="qrResult" style="text-align: center; display: none; padding: 3rem; background: rgba(15, 23, 42, 0.4); border-radius: 24px; border: 1px dashed rgba(255,255,255,0.1);">
                    <p style="margin-bottom: 2rem; color: var(--text-muted); font-weight: 600; text-transform: uppercase; letter-spacing: 0.1em;">Aperçu du QR Code</p>
                    <div style="background: white; display: inline-block; padding: 2rem; border-radius: 24px; box-shadow: 0 10px 30px rgba(0,0,0,0.5);">
                        <img id="qrImg" src="" alt="QR Code" style="display: block;">
                    </div>
                    <div style="margin-top: 2.5rem; display: flex; justify-content: center; gap: 1rem;">
                        <button onclick="downloadQR()" class="btn-admin" style="background: rgba(255,255,255,0.05) !important; color: #fff !important; font-size: 0.9rem;">
                            <i class="fas fa-download"></i> Télécharger (PNG)
                        </button>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <script>
        function generateQR() {
            const val = document.getElementById('qrInput').value;
            if (!val) return;
            const qrImg = document.getElementById('qrImg');
            const qrResult = document.getElementById('qrResult');
            
            qrImg.src = `https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=${encodeURIComponent(val)}`;
            qrResult.style.display = 'block';
        }

        function downloadQR() {
            const img = document.getElementById('qrImg');
            const link = document.createElement('a');
            link.href = img.src;
            link.download = 'hopebridge-qr.png';
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        }
    </script>

    <style>
        .form-group label { color: var(--text-dark); }
        .btn-admin:hover { opacity: 0.9; transform: translateY(-1px); }
    </style>
</body>
</html>
