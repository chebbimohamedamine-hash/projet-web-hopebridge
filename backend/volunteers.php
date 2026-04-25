<?php
session_start();
require '../db.php';
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'admin') {
    header("Location: ../frontend/login.php");
    exit();
}

// Fetch volunteers with user info
$stmt = $pdo->query("SELECT v.*, u.full_name, u.email, u.profile_photo FROM volunteers v JOIN users u ON v.user_id = u.id ORDER BY v.id DESC");
$volunteers = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HopeBridge Admin | Gestion des Bénévoles</title>
    <link rel="stylesheet" href="dashboard-style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style id="admin-fixes">
        /* Ultra-specific overrides to fix the layout problems */
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

        .form-group input, .form-group textarea {
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
            <li class="menu-item"><a href="volunteers.php" class="menu-link active"><i class="fas fa-users"></i> Bénévoles</a></li>
            <li class="menu-item"><a href="donations.php" class="menu-link"><i class="fas fa-donate"></i> Dons</a></li>
            <li class="menu-item"><a href="shelters.php" class="menu-link"><i class="fas fa-hotel"></i> Centres d'hébergement</a></li>
            <li class="menu-item"><a href="mailing.php" class="menu-link"><i class="fas fa-envelope-open-text"></i> Mailing & QR</a></li>
        </ul>
    </aside>

    <main class="main-content">
        <header>
            <div>
                <h1>Gestion des Bénévoles</h1>
                <p style="color: var(--text-muted);">Liste des inscrits.</p>
            </div>
            <button id="addBtn" class="btn-add"><i class="fas fa-plus"></i></button>
        </header>

        <div class="controls">
            <div class="search-wrapper">
                <i class="fas fa-search"></i>
                <input type="text" id="adminSearch" placeholder="Rechercher un bénévole (nom, email, compétences)...">
            </div>
            <div class="sort-wrapper">
                <select id="adminSort">
                    <option value="id">Trier par ID</option>
                    <option value="full_name">Nom (A-Z)</option>
                    <option value="email">Email</option>
                </select>
            </div>
        </div>

        <!-- Modal Ajout -->
        <div id="addModal" class="modal">
            <div class="modal-content">
                <span class="close-modal"><i class="fas fa-times"></i></span>
                <h2 style="font-size: 1.8rem; margin-bottom: 0.5rem;">Ajouter un Bénévole</h2>
                <p style="color: var(--text-muted); margin-bottom: 2rem;">Remplissez les informations pour inscrire manuellement un nouveau membre.</p>
                
                <form action="../volunteer_post.php" method="POST" enctype="multipart/form-data">
                    <div class="form-group">
                        <label><i class="fas fa-user" style="margin-right: 0.5rem; color: var(--accent-blue);"></i> Nom Complet</label>
                        <input type="text" name="full_name" placeholder="Ex: Ahmed Ben Ali" required>
                    </div>
                    <div class="form-group">
                        <label><i class="fas fa-envelope" style="margin-right: 0.5rem; color: var(--accent-blue);"></i> Email Professionnel</label>
                        <input type="email" name="email" placeholder="ahmed@exemple.com" required>
                    </div>
                    <div class="form-group">
                        <label><i class="fas fa-lightbulb" style="margin-right: 0.5rem; color: var(--accent-blue);"></i> Compétences / Motivation</label>
                        <textarea name="motivation" rows="4" placeholder="Décrivez brièvement les points forts..."></textarea>
                    </div>
                    <button type="submit" class="btn-admin" style="width: 100%; margin-top: 1rem;">
                        <i class="fas fa-check"></i> Confirmer l'inscription
                    </button>
                </form>
            </div>
        </div>

        <section class="data-section">
            <table>
                <thead>
                    <tr>
                        <th>Photo</th>
                        <th>Nom</th>
                        <th>Email</th>
                        <th>Compétences</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($volunteers as $v): ?>
                    <tr>
                        <td>
                            <?php if($v['profile_photo']): ?>
                                <img src="../<?php echo $v['profile_photo']; ?>" style="width: 40px; height: 40px; border-radius: 50%; object-fit: cover;">
                            <?php else: ?>
                                <div class="avatar"><?php echo substr($v['full_name'], 0, 2); ?></div>
                            <?php endif; ?>
                        </td>
                        <td><?php echo htmlspecialchars($v['full_name']); ?></td>
                        <td><?php echo htmlspecialchars($v['email']); ?></td>
                        <td><?php echo htmlspecialchars($v['skills']); ?></td>
                        <td>
                            <a href="delete_volunteer.php?id=<?php echo $v['id']; ?>" class="btn-action delete" onclick="return confirm('Supprimer ce bénévole ?')"><i class="fas fa-trash"></i></a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </section>
    </main>

    <style>
        /* Force styles for this page to avoid cache/link issues */
        .controls {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 2rem;
            margin-bottom: 2.5rem;
            background: rgba(30, 41, 59, 0.3);
            padding: 1.5rem;
            border-radius: 20px;
            border: 1px solid var(--border);
        }

        .search-wrapper {
            position: relative;
            flex-grow: 1;
        }

        .search-wrapper i {
            position: absolute;
            left: 1.2rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--accent-blue);
        }

        .search-wrapper input {
            width: 100%;
            background: #0f172a !important;
            border: 1px solid var(--border) !important;
            padding: 1rem 1.2rem 1rem 3.5rem !important;
            border-radius: 14px !important;
            color: #fff !important;
            font-size: 1rem !important;
        }

        .sort-wrapper select {
            background: #0f172a !important;
            border: 1px solid var(--border) !important;
            padding: 1rem 1.5rem !important;
            border-radius: 14px !important;
            color: #fff !important;
            cursor: pointer;
        }

        /* Modal fixes */
        .modal {
            position: fixed;
            top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(2, 6, 23, 0.9);
            backdrop-filter: blur(10px);
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 10000;
        }

        .modal-content {
            background: #1e293b !important;
            padding: 3rem !important;
            border-radius: 28px !important;
            border: 1px solid rgba(56, 189, 248, 0.4) !important;
            width: 95%;
            max-width: 550px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5) !important;
        }

        .form-group { margin-bottom: 1.5rem; }
        .form-group label { 
            display: block; 
            margin-bottom: 0.8rem; 
            font-weight: 600; 
            color: var(--text-muted);
            font-size: 0.85rem;
            text-transform: uppercase;
        }

        .form-group input, .form-group textarea {
            width: 100% !important;
            background: #0f172a !important;
            border: 1px solid var(--border) !important;
            padding: 1rem !important;
            border-radius: 12px !important;
            color: #fff !important;
            font-size: 1rem !important;
        }

        .btn-action { text-decoration: none; border: none; padding: 0.6rem; border-radius: 8px; cursor: pointer; transition: 0.2s; display: inline-flex; align-items: center; justify-content: center; width: 38px; height: 38px; }
        .btn-action.delete { background: rgba(239, 68, 68, 0.15); color: #f87171; }
        .btn-action.delete:hover { background: #ef4444; color: #fff; }
    </style>
    <script src="js/admin-features.js"></script>
    <script>
        initSearch('volunteers');
        initSort('volunteers');
        initAddModal();
    </script>
</body>
</html>
