<?php
session_start();
require '../db.php';

// Vérification de l'accès admin
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'admin') {
    header("Location: ../frontend/login.php");
    exit();
}

// Traitement de l'ajout manuel d'un don
if (isset($_POST['add_donation'])) {
    $amount = $_POST['amount'];
    $donor_name = $_POST['donor_name'] ?: 'Anonyme';
    $currency = $_POST['currency'] ?? 'TND';
    
    try {
        $stmt = $pdo->prepare("INSERT INTO donations (amount, currency, user_id) VALUES (?, ?, NULL)"); 
        $stmt->execute([$amount, $currency]);
        header("Location: donations.php?success=added");
        exit();
    } catch (PDOException $e) {
        $error = "Erreur : " . $e->getMessage() . " (Vérifiez que la colonne 'currency' existe)";
    }
}

// Récupération des dons avec les informations de l'utilisateur
try {
    $stmt = $pdo->query("SELECT d.*, u.full_name, u.email FROM donations d LEFT JOIN users u ON d.user_id = u.id ORDER BY d.id DESC");
    $donations = $stmt->fetchAll();
} catch (PDOException $e) {
    $donations = []; 
}

// Calcul du total des dons
$total_stmt = $pdo->query("SELECT SUM(amount) as total FROM donations");
$total_amount = $total_stmt->fetch()['total'] ?? 0;
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HopeBridge Admin | Gestion des Dons</title>
    <link rel="stylesheet" href="dashboard-style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style id="admin-fixes">
        /* Force styles for this page */
        .main-content .controls {
            display: flex !important;
            flex-direction: row !important;
            justify-content: space-between !important;
            align-items: center !important;
            gap: 20px !important;
            margin-bottom: 30px !important;
            background: rgba(30, 41, 59, 0.4) !important;
            padding: 20px !important;
            border-radius: 20px !important;
            border: 1px solid rgba(255,255,255,0.1) !important;
        }

        .main-content .search-wrapper {
            position: relative !important;
            flex: 1 !important;
        }

        .main-content .search-wrapper i {
            position: absolute !important;
            left: 15px !important;
            top: 50% !important;
            transform: translateY(-50%) !important;
            color: #38bdf8 !important;
            z-index: 5 !important;
        }

        .main-content .search-wrapper input {
            width: 100% !important;
            display: block !important;
            background: #0f172a !important;
            border: 1px solid rgba(255,255,255,0.1) !important;
            padding: 12px 15px 12px 45px !important;
            border-radius: 12px !important;
            color: #fff !important;
            font-size: 16px !important;
        }

        .main-content .sort-wrapper select {
            background: #0f172a !important;
            border: 1px solid rgba(255,255,255,0.1) !important;
            padding: 12px 20px !important;
            border-radius: 12px !important;
            color: #fff !important;
            min-width: 150px !important;
        }

        /* Modal Styles */
        .modal {
            display: none;
            position: fixed !important;
            z-index: 9999 !important;
            left: 0; top: 0; width: 100%; height: 100%;
            background-color: rgba(0,0,0,0.85) !important;
            backdrop-filter: blur(10px) !important;
            align-items: center;
            justify-content: center;
        }

        .modal-content {
            background: #1e293b !important;
            margin: auto;
            padding: 40px !important;
            border: 1px solid #38bdf8 !important;
            width: 90% !important;
            max-width: 500px !important;
            border-radius: 24px !important;
            box-shadow: 0 20px 40px rgba(0,0,0,0.4) !important;
        }

        .form-group {
            margin-bottom: 20px !important;
            width: 100% !important;
            text-align: left !important;
        }

        .form-group label {
            display: block !important;
            margin-bottom: 8px !important;
            color: #94a3b8 !important;
            font-weight: 600 !important;
            font-size: 14px !important;
        }

        .form-group input, .form-group select {
            width: 100% !important;
            display: block !important;
            background: #0f172a !important;
            border: 1px solid rgba(255,255,255,0.1) !important;
            padding: 12px !important;
            border-radius: 10px !important;
            color: #fff !important;
            font-size: 15px !important;
        }

        .btn-admin {
            background: #38bdf8 !important;
            color: #000 !important;
            font-weight: 800 !important;
            padding: 14px !important;
            border-radius: 12px !important;
            cursor: pointer !important;
            border: none !important;
            width: 100% !important;
            transition: 0.3s !important;
        }

        .btn-admin:hover { transform: translateY(-2px); opacity: 0.9; }

        .close-modal {
            float: right;
            cursor: pointer;
            color: #94a3b8;
            font-size: 20px;
        }
        .close-modal:hover { color: #fff; }
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
            <li class="menu-item"><a href="donations.php" class="menu-link active"><i class="fas fa-donate"></i> Dons</a></li>
            <li class="menu-item"><a href="shelters.php" class="menu-link"><i class="fas fa-hotel"></i> Centres d'hébergement</a></li>
            <li class="menu-item"><a href="mailing.php" class="menu-link"><i class="fas fa-envelope-open-text"></i> Mailing & QR</a></li>
        </ul>
        <div class="menu-item" style="margin-top: auto;">
            <a href="../logout.php" class="menu-link" style="color: #f87171;"><i class="fas fa-sign-out-alt"></i> Déconnexion</a>
        </div>
    </aside>

    <main class="main-content">
        <header>
            <div>
                <h1>Gestion des Dons</h1>
                <p style="color: var(--text-muted);">Suivi des contributions financières.</p>
            </div>
            <button id="addBtn" class="btn-add"><i class="fas fa-plus"></i></button>
        </header>

        <!-- Modal Ajout Don -->
        <div id="addModal" class="modal">
            <div class="modal-content">
                <span class="close-modal"><i class="fas fa-times"></i></span>
                <h2 style="font-size: 1.8rem; margin-bottom: 0.5rem;">Enregistrer un Don</h2>
                <p style="color: var(--text-muted); margin-bottom: 2rem;">Saisie manuelle d'une contribution financière reçue hors ligne.</p>
                
                <form action="donations.php" method="POST">
                    <div class="form-group">
                        <label><i class="fas fa-user-circle" style="margin-right: 0.5rem; color: #38bdf8;"></i> Donateur (Nom ou Anonyme)</label>
                        <input type="text" name="donor_name" placeholder="Ex: Jean Dupont">
                    </div>
                    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 1.5rem;">
                        <div class="form-group">
                            <label><i class="fas fa-money-bill-wave" style="margin-right: 0.5rem; color: #38bdf8;"></i> Montant</label>
                            <input type="number" step="0.01" name="amount" placeholder="0.00" required>
                        </div>
                        <div class="form-group">
                            <label><i class="fas fa-coins" style="margin-right: 0.5rem; color: #38bdf8;"></i> Devise</label>
                            <select name="currency">
                                <option value="TND">DT (TND)</option>
                                <option value="EUR">€ (EUR)</option>
                                <option value="USD">$ (USD)</option>
                            </select>
                        </div>
                    </div>
                    <button type="submit" name="add_donation" class="btn-admin" style="width: 100%; margin-top: 1rem;">
                        <i class="fas fa-save"></i> Enregistrer le don
                    </button>
                </form>
            </div>
        </div>

        <div class="controls">
            <div class="search-wrapper">
                <i class="fas fa-search"></i>
                <input type="text" id="adminSearch" placeholder="Rechercher un don (donateur, email, montant)...">
            </div>
            <div class="sort-wrapper">
                <select id="adminSort">
                    <option value="id">Trier par ID</option>
                    <option value="amount">Montant</option>
                    <option value="donation_date">Date</option>
                </select>
            </div>
        </div>

        <section class="grid">
            <div class="card">
                <p class="card-title">Total des Dons</p>
                <p class="card-value"><?php echo number_format($total_amount, 2, ',', ' '); ?> €</p>
            </div>
            <div class="card">
                <p class="card-title">Nombre de Dons</p>
                <p class="card-value"><?php echo count($donations); ?></p>
            </div>
        </section>

        <section class="data-section">
            <h2>Historique des Transactions</h2>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Donateur</th>
                        <th>Email</th>
                        <th>Montant</th>
                        <th>Date</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($donations)): ?>
                        <tr>
                            <td colspan="6" style="text-align: center; color: var(--text-muted);">Aucun don enregistré pour le moment.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach($donations as $d): ?>
                        <tr>
                            <td>#<?php echo $d['id']; ?></td>
                            <td><?php echo htmlspecialchars($d['full_name'] ?? 'Anonyme'); ?></td>
                            <td><?php echo htmlspecialchars($d['email'] ?? 'N/A'); ?></td>
                            <td style="font-weight: 700; color: var(--accent-blue);">
                                <?php 
                                    $currency = $d['currency'] ?? 'TND'; 
                                    echo number_format($d['amount'], 2, ',', ' ') . ($currency == 'TND' ? ' DT' : ' €'); 
                                ?>
                            </td>
                            <td><?php echo isset($d['donation_date']) ? date('d/m/Y H:i', strtotime($d['donation_date'])) : '<span style="color:var(--text-muted);font-size:0.8rem;">Non renseignée</span>'; ?></td>
                            <td><span class="status status-completed">Complété</span></td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </section>
    </main>

    <script src="js/admin-features.js"></script>
    <script>
        initSearch('donations');
        initSort('donations');
        initAddModal();
    </script>
</body>
</html>
