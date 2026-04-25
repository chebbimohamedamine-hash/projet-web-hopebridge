<?php
session_start();
require '../db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../frontend/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$user_role = $_SESSION['user_role'];

// Récupération des infos actuelles
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

// Mise à jour du profil
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $full_name = $_POST['full_name'];
    $email = $_POST['email'];
    $photo_path = $user['profile_photo']; // Garder l'ancienne par défaut

    // Gestion de l'upload de photo
    if (isset($_FILES['profile_photo']) && $_FILES['profile_photo']['error'] == 0) {
        $upload_dir = '../uploads/profiles/';
        if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);
        
        $extension = pathinfo($_FILES['profile_photo']['name'], PATHINFO_EXTENSION);
        $filename = 'user_' . $user_id . '_' . time() . '.' . $extension;
        $target_file = $upload_dir . $filename;
        
        if (move_uploaded_file($_FILES['profile_photo']['tmp_name'], $target_file)) {
            $photo_path = 'uploads/profiles/' . $filename;
        }
    }
    
    try {
        $stmt = $pdo->prepare("UPDATE users SET full_name = ?, email = ?, profile_photo = ? WHERE id = ?");
        $stmt->execute([$full_name, $email, $photo_path, $user_id]);
        $_SESSION['user_name'] = $full_name;
        $success = "Profil et photo mis à jour avec succès !";
        
        // Refresh user data
        $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$user_id]);
        $user = $stmt->fetch();
    } catch (PDOException $e) {
        $error = "Erreur : " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HopeBridge | Mon Profil</title>
    <link rel="stylesheet" href="dashboard-style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>

    <aside class="sidebar">
        <div class="sidebar-logo">
            <i class="fas fa-hand-holding-heart"></i>
            <span>HopeBridge</span>
        </div>
        <ul class="menu">
            <li class="menu-item"><a href="dashboard.php" class="menu-link"><i class="fas fa-th-large"></i> Dashboard</a></li>
            <li class="menu-item"><a href="profile.php" class="menu-link active"><i class="fas fa-user-circle"></i> Mon Profil</a></li>
            <?php if ($user_role == 'admin'): ?>
                <li class="menu-item"><a href="volunteers.php" class="menu-link"><i class="fas fa-users"></i> Bénévoles</a></li>
                <li class="menu-item"><a href="donations.php" class="menu-link"><i class="fas fa-donate"></i> Dons</a></li>
                <li class="menu-item"><a href="shelters.php" class="menu-link"><i class="fas fa-hotel"></i> Centres d'hébergement</a></li>
            <?php endif; ?>
        </ul>
        <div class="menu-item" style="margin-top: auto;">
            <a href="../logout.php" class="menu-link" style="color: #f87171;"><i class="fas fa-sign-out-alt"></i> Déconnexion</a>
        </div>
    </aside>

    <main class="main-content">
        <header>
            <div class="header-title">
                <h1>Mon Profil</h1>
                <p style="color: var(--text-muted);">Gérez vos informations personnelles et vos paramètres de sécurité.</p>
            </div>
            <div id="google_translate_element"></div>
        </header>

        <section class="profile-container" style="max-width: 800px; margin: 0 auto;">
            <div class="data-section" style="padding: 0; overflow: hidden; border-radius: 32px;">
                <!-- Profile Header Cover -->
                <div style="height: 120px; background: linear-gradient(135deg, var(--accent-blue), #0ea5e9); position: relative;"></div>
                
                <div style="padding: 0 3rem 3rem; position: relative; margin-top: -60px;">
                    <form action="profile.php" method="POST" enctype="multipart/form-data" style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem;">
                        <!-- Avatar & Summary (Inside form to allow photo upload) -->
                        <div style="grid-column: span 2; display: flex; align-items: flex-end; gap: 2rem; margin-bottom: 3rem;">
                            <div style="position: relative;">
                                <div id="avatarPreview" class="avatar-large" style="width: 120px; height: 120px; background: var(--sidebar-dark); border: 4px solid var(--bg-dark); border-radius: 30px; display: flex; align-items: center; justify-content: center; font-size: 3rem; font-weight: 800; color: var(--accent-blue); box-shadow: var(--glass-shadow); overflow: hidden;">
                                    <?php if($user['profile_photo']): ?>
                                        <img src="../<?php echo $user['profile_photo']; ?>" style="width: 100%; height: 100%; object-fit: cover;">
                                    <?php else: ?>
                                        <?php echo substr($user['full_name'], 0, 1) . substr(strrchr($user['full_name'], " "), 1, 1); ?>
                                    <?php endif; ?>
                                </div>
                                <input type="file" name="profile_photo" id="profile_photo" style="display: none;" accept="image/*">
                                <button type="button" onclick="document.getElementById('profile_photo').click()" title="Changer de photo" style="position: absolute; bottom: 5px; right: 5px; background: var(--accent-blue); border: none; width: 32px; height: 32px; border-radius: 10px; color: #000; cursor: pointer; display: flex; align-items: center; justify-content: center; font-size: 0.9rem; box-shadow: 0 4px 10px rgba(0,0,0,0.3);">
                                    <i class="fas fa-camera"></i>
                                </button>
                            </div>
                            <div style="margin-bottom: 0.5rem;">
                                <h2 style="font-size: 1.8rem; margin-bottom: 0.3rem;"><?php echo htmlspecialchars($user['full_name']); ?></h2>
                                <span class="status status-completed" style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em;">
                                    <i class="fas fa-shield-alt"></i> <?php echo ucfirst($user['role']); ?>
                                </span>
                            </div>
                        </div>

                        <div class="form-group" style="grid-column: span 2;">
                            <label style="display: block; color: var(--text-muted); font-size: 0.85rem; font-weight: 600; margin-bottom: 0.8rem; text-transform: uppercase; letter-spacing: 0.05em;">Nom Complet</label>
                            <div style="position: relative;">
                                <i class="fas fa-user" style="position: absolute; left: 1.2rem; top: 50%; transform: translateY(-50%); color: var(--accent-blue); opacity: 0.7;"></i>
                                <input type="text" name="full_name" value="<?php echo htmlspecialchars($user['full_name']); ?>" required 
                                       style="width: 100%; background: rgba(15, 23, 42, 0.6); border: 1px solid var(--border); padding: 1.1rem 1.2rem 1.1rem 3.2rem; border-radius: 16px; color: #fff; font-size: 1rem; transition: all 0.3s;">
                            </div>
                        </div>

                        <div class="form-group">
                            <label style="display: block; color: var(--text-muted); font-size: 0.85rem; font-weight: 600; margin-bottom: 0.8rem; text-transform: uppercase; letter-spacing: 0.05em;">Adresse Email</label>
                            <div style="position: relative;">
                                <i class="fas fa-envelope" style="position: absolute; left: 1.2rem; top: 50%; transform: translateY(-50%); color: var(--accent-blue); opacity: 0.7;"></i>
                                <input type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required 
                                       style="width: 100%; background: rgba(15, 23, 42, 0.6); border: 1px solid var(--border); padding: 1.1rem 1.2rem 1.1rem 3.2rem; border-radius: 16px; color: #fff; font-size: 1rem; transition: all 0.3s;">
                            </div>
                        </div>

                        <div class="form-group">
                            <label style="display: block; color: var(--text-muted); font-size: 0.85rem; font-weight: 600; margin-bottom: 0.8rem; text-transform: uppercase; letter-spacing: 0.05em;">Identifiant Unique</label>
                            <div style="position: relative;">
                                <i class="fas fa-fingerprint" style="position: absolute; left: 1.2rem; top: 50%; transform: translateY(-50%); color: var(--accent-orange); opacity: 0.8;"></i>
                                <input type="text" value="HB-PRO-<?php echo str_pad($user['id'], 4, '0', STR_PAD_LEFT); ?>" disabled 
                                       style="width: 100%; background: rgba(251, 146, 60, 0.05); border: 1px solid rgba(251, 146, 60, 0.2); padding: 1.1rem 1.2rem 1.1rem 3.2rem; border-radius: 16px; color: var(--accent-orange); font-size: 1rem; cursor: not-allowed; font-family: 'JetBrains Mono', monospace; font-weight: 700;">
                            </div>
                        </div>

                        <div style="grid-column: span 2; margin-top: 1rem; border-top: 1px solid var(--border); padding-top: 2rem; display: flex; justify-content: flex-end;">
                            <button type="submit" class="btn-admin" style="border: none; border-radius: 16px; font-size: 1rem; display: flex; align-items: center; gap: 0.8rem; cursor: pointer; box-shadow: 0 10px 20px rgba(56, 189, 248, 0.2);">
                                <i class="fas fa-save"></i> Enregistrer les modifications
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </section>
    </main>

    <script type="text/javascript">
        function googleTranslateElementInit() {
            new google.translate.TranslateElement({pageLanguage: 'fr', includedLanguages: 'en,ar,fr,it,es,de'}, 'google_translate_element');
        }
    </script>
    <script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>
</body>
</html>
