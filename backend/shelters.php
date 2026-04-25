<?php
session_start();
require '../db.php';
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'admin') {
    header("Location: ../frontend/login.php");
    exit();
}

// Logique pour lister les centres
$stmt = $pdo->query("SELECT * FROM shelters ORDER BY id DESC");
$shelters = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HopeBridge Admin | Gestion des Centres</title>
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
            max-width: 550px !important;
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

        .form-group input {
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

        /* Custom file input */
        input[type="file"] {
            padding: 8px !important;
            font-size: 12px !important;
            background: rgba(255,255,255,0.05) !important;
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
            <li class="menu-item"><a href="shelters.php" class="menu-link active"><i class="fas fa-hotel"></i> Centres d'hébergement</a></li>
            <li class="menu-item"><a href="mailing.php" class="menu-link"><i class="fas fa-envelope-open-text"></i> Mailing & QR</a></li>
        </ul>
    </aside>

    <main class="main-content">
        <header>
            <div>
                <h1>Gestion des Centres</h1>
                <p style="color: var(--text-muted);">Administrer les lieux d'accueil.</p>
            </div>
            <button id="addBtn" class="btn-add"><i class="fas fa-plus"></i></button>
        </header>

        <div class="controls">
            <div class="search-wrapper">
                <i class="fas fa-search"></i>
                <input type="text" id="adminSearch" placeholder="Rechercher un centre (nom, adresse)...">
            </div>
            <div class="sort-wrapper">
                <select id="adminSort">
                    <option value="id">Trier par ID</option>
                    <option value="name">Nom (A-Z)</option>
                    <option value="total_capacity">Capacité</option>
                </select>
            </div>
        </div>

        <!-- Modal Ajout Centre -->
        <div id="addModal" class="modal">
            <div class="modal-content">
                <span class="close-modal"><i class="fas fa-times"></i></span>
                <h2 style="font-size: 1.8rem; margin-bottom: 0.5rem;">Ajouter un Nouveau Centre</h2>
                <p style="color: var(--text-muted); margin-bottom: 2rem;">Enregistrez une nouvelle structure d'accueil dans la base de données.</p>
                
                <form id="shelterForm" action="shelter_action.php" method="POST" enctype="multipart/form-data">
                    <div class="form-group">
                        <label><i class="fas fa-building" style="margin-right: 0.5rem; color: #38bdf8;"></i> Nom du Centre</label>
                        <input type="text" name="shelter_name" placeholder="Ex: Centre Saint-Michel" required>
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
                        <div class="form-group">
                            <label><i class="fas fa-bed" style="margin-right: 0.5rem; color: #38bdf8;"></i> Capacité (Lits)</label>
                            <input type="number" name="capacity" placeholder="50" required>
                        </div>
                        <div class="form-group">
                            <label><i class="fas fa-camera" style="margin-right: 0.5rem; color: #38bdf8;"></i> Photo de la structure</label>
                            <input type="file" name="shelter_photo" accept="image/*">
                        </div>
                    </div>

                    <div class="form-group">
                        <label><i class="fas fa-map-marker-alt" style="margin-right: 0.5rem; color: #38bdf8;"></i> Adresse Complète</label>
                        <input type="text" name="address" placeholder="Ex: 12 Rue de la Paix, Tunis" required>
                    </div>

                    <button type="submit" class="btn-admin" style="width: 100%; margin-top: 1rem;">
                        <i class="fas fa-check"></i> Enregistrer le centre
                    </button>
                </form>
            </div>
        </div>

        <section class="data-section" style="margin-top: 2rem;">
            <h2>Liste des Centres</h2>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nom</th>
                        <th>Capacité</th>
                        <th>Adresse</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($shelters as $s): ?>
                    <tr>
                        <td><?php echo $s['id']; ?></td>
                        <td><?php echo htmlspecialchars($s['name']); ?></td>
                        <td><?php echo $s['total_capacity']; ?></td>
                        <td><?php echo htmlspecialchars($s['address']); ?></td>
                        <td>
                            <a href="edit_shelter.php?id=<?php echo $s['id']; ?>" class="btn-action edit"><i class="fas fa-edit"></i></a>
                            <a href="delete_shelter.php?id=<?php echo $s['id']; ?>" class="btn-action delete" onclick="return confirm('Supprimer ce centre ?')"><i class="fas fa-trash"></i></a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </section>
    </main>

    <style>
        .btn-action { text-decoration: none; border: none; padding: 0.5rem; border-radius: 6px; cursor: pointer; margin-right: 0.5rem; transition: 0.2s; display: inline-block;}
        .btn-action.edit { background: rgba(56, 189, 248, 0.1); color: var(--accent-blue); }
        .btn-action.delete { background: rgba(239, 68, 68, 0.1); color: #f87171; }
    </style>
    <script src="js/admin-features.js"></script>
    <script>
        initSearch('shelters');
        initSort('shelters');
        initAddModal();
    </script>
</body>
</html>
