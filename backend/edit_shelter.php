<?php
session_start();
require '../db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'admin') {
    header("Location: ../frontend/login.php");
    exit();
}

$shelter = null;
if (isset($_GET['id'])) {
    $stmt = $pdo->prepare("SELECT * FROM shelters WHERE id = ?");
    $stmt->execute([$_GET['id']]);
    $shelter = $stmt->fetch();
}

if (!$shelter) {
    header("Location: shelters.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $name = $_POST['shelter_name'];
    $capacity = $_POST['capacity'];
    $address = $_POST['address'];
    $photo_path = $shelter['image_path']; // Garder l'ancienne par défaut

    // Gestion de l'upload de photo
    if (isset($_FILES['shelter_photo']) && $_FILES['shelter_photo']['error'] == 0) {
        $upload_dir = '../uploads/';
        if (!is_dir($upload_dir)) mkdir($upload_dir);
        
        $filename = time() . '_' . basename($_FILES['shelter_photo']['name']);
        $target_file = $upload_dir . $filename;
        
        if (move_uploaded_file($_FILES['shelter_photo']['tmp_name'], $target_file)) {
            // Supprimer l'ancienne photo si elle existe
            if ($photo_path && file_exists("../" . $photo_path)) {
                unlink("../" . $photo_path);
            }
            $photo_path = 'uploads/' . $filename;
        }
    }

    try {
        $stmt = $pdo->prepare("UPDATE shelters SET name = ?, address = ?, total_capacity = ?, shelter_photo = ? WHERE id = ?");
        $stmt->execute([$name, $address, $capacity, $photo_path, $id]);
        header("Location: shelters.php?success=updated");
        exit();
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
    <title>HopeBridge Admin | Modifier Centre</title>
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
            <li class="menu-item"><a href="volunteers.php" class="menu-link"><i class="fas fa-users"></i> Bénévoles</a></li>
            <li class="menu-item"><a href="donations.php" class="menu-link"><i class="fas fa-donate"></i> Dons</a></li>
            <li class="menu-item"><a href="shelters.php" class="menu-link active"><i class="fas fa-hotel"></i> Centres d'hébergement</a></li>
        </ul>
    </aside>

    <main class="main-content">
        <header>
            <div>
                <h1>Modifier le Centre #<?php echo $shelter['id']; ?></h1>
                <p style="color: var(--text-muted);">Mise à jour des informations du centre.</p>
            </div>
        </header>

        <section class="data-section" style="max-width: 600px;">
            <?php if(isset($error)): ?>
                <p style="color: #ef4444; margin-bottom: 1rem;"><?php echo $error; ?></p>
            <?php endif; ?>

            <form action="edit_shelter.php?id=<?php echo $shelter['id']; ?>" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="id" value="<?php echo $shelter['id']; ?>">
                
                <div class="form-group">
                    <label>Nom du Centre</label>
                    <input type="text" name="shelter_name" value="<?php echo htmlspecialchars($shelter['name']); ?>" required>
                </div>

                <div class="form-group">
                    <label>Capacité Totale (Lits)</label>
                    <input type="number" name="capacity" value="<?php echo $shelter['total_capacity']; ?>" required>
                </div>

                <div class="form-group">
                    <label>Adresse Complète</label>
                    <input type="text" name="address" value="<?php echo htmlspecialchars($shelter['address']); ?>" required>
                </div>

                <div class="form-group">
                    <label>Photo actuelle</label>
                    <?php if($shelter['image_path']): ?>
                        <img src="../<?php echo $shelter['image_path']; ?>" style="width: 100px; border-radius: 8px; margin-bottom: 1rem;">
                    <?php endif; ?>
                    <label>Changer la photo (optionnel)</label>
                    <input type="file" name="shelter_photo" accept="image/*">
                </div>

                <div style="display: flex; gap: 1rem;">
                    <button type="submit" class="btn-admin">Mettre à jour</button>
                    <a href="shelters.php" class="btn-admin" style="background: var(--sidebar-dark); color: var(--text-muted); text-decoration: none; text-align: center;">Annuler</a>
                </div>
            </form>
        </section>
    </main>

</body>
</html>
