<?php 
require '../db.php';
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'ngo') {
    header("Location: login.php");
    exit();
}

// Récupérer les repas disponibles qui n'ont pas encore expiré
$stmt = $pdo->prepare("
    SELECT m.*, u.full_name as donor_name 
    FROM meal_donations m 
    JOIN users u ON m.donor_id = u.id 
    WHERE m.status = 'available' AND m.expiry_date > NOW() 
    ORDER BY m.created_at DESC
");
$stmt->execute();
$meals = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HopeBridge | Repas Disponibles</title>
    <link rel="stylesheet" href="style.css?v=5">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: #0f172a;
            color: #f8fafc;
            min-height: 100vh;
            margin: 0;
            padding-top: 80px;
        }

        .container {
            max-width: 1000px;
            margin: 0 auto;
            padding: 2rem;
        }

        .header-section {
            text-align: center;
            margin-bottom: 4rem;
        }

        .header-section h1 {
            font-size: 2.5rem;
            font-weight: 800;
            color: #d4af37;
            margin-bottom: 1rem;
        }

        .header-section p {
            color: #94a3b8;
            font-size: 1.1rem;
        }

        .meals-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 2rem;
        }

        .meal-card {
            background: rgba(30, 41, 59, 0.7);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.05);
            border-radius: 20px;
            padding: 1.5rem;
            transition: all 0.3s;
            position: relative;
            overflow: hidden;
        }

        .meal-card:hover {
            transform: translateY(-10px);
            border-color: rgba(212, 175, 55, 0.4);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.4);
        }

        .meal-card::before {
            content: '';
            position: absolute;
            top: 0; left: 0; width: 4px; height: 100%;
            background: #d4af37;
        }

        .meal-card h3 {
            margin: 0 0 0.5rem 0;
            color: #f8fafc;
            font-size: 1.3rem;
        }

        .donor-info {
            font-size: 0.85rem;
            color: #d4af37;
            font-weight: 600;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .meal-details {
            margin-bottom: 1.5rem;
        }

        .detail-item {
            display: flex;
            align-items: center;
            gap: 0.7rem;
            font-size: 0.9rem;
            color: #94a3b8;
            margin-bottom: 0.5rem;
        }

        .detail-item i {
            color: #d4af37;
            width: 16px;
        }

        .btn-claim {
            width: 100%;
            padding: 0.8rem;
            background: #d4af37;
            color: #0f172a;
            border: none;
            border-radius: 10px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s;
            text-decoration: none;
            display: inline-block;
            text-align: center;
        }

        .btn-claim:hover {
            background: #f8fafc;
            transform: scale(1.02);
        }

        .no-meals {
            text-align: center;
            padding: 4rem;
            background: rgba(30, 41, 59, 0.4);
            border-radius: 20px;
            grid-column: 1 / -1;
        }

        .nav-back {
            margin-bottom: 2rem;
        }

        .nav-back a {
            color: #94a3b8;
            text-decoration: none;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
    </style>
</head>
<body>

    <div class="container">
        <div class="nav-back">
            <a href="index.php"><i class="fas fa-arrow-left"></i> Retour à l'accueil</a>
        </div>

        <div class="header-section">
            <h1>🍱 Repas Disponibles</h1>
            <p>Aidez-nous à éviter le gaspillage et distribuez ces repas aux plus démunis.</p>
        </div>

        <div class="meals-grid">
            <?php if (empty($meals)): ?>
                <div class="no-meals">
                    <i class="fas fa-box-open" style="font-size: 3rem; color: #64748b; margin-bottom: 1rem;"></i>
                    <p>Aucun repas n'est disponible pour le moment. Revenez plus tard !</p>
                </div>
            <?php else: ?>
                <?php foreach ($meals as $meal): ?>
                    <div class="meal-card">
                        <div class="donor-info">
                            <i class="fas fa-store"></i> <?php echo htmlspecialchars($meal['donor_name']); ?>
                        </div>
                        <h3><?php echo htmlspecialchars($meal['title']); ?></h3>
                        
                        <div class="meal-details">
                            <div class="detail-item">
                                <i class="fas fa-users"></i>
                                <?php echo $meal['quantity']; ?> portions disponibles
                            </div>
                            <div class="detail-item">
                                <i class="fas fa-hourglass-half"></i>
                                Expire le : <?php echo date('d/m H:i', strtotime($meal['expiry_date'])); ?>
                            </div>
                            <?php if (!empty($meal['description'])): ?>
                                <div class="detail-item">
                                    <i class="fas fa-info-circle"></i>
                                    <?php echo htmlspecialchars(substr($meal['description'], 0, 80)) . (strlen($meal['description']) > 80 ? '...' : ''); ?>
                                </div>
                            <?php endif; ?>
                        </div>

                        <a href="../claim_meal.php?id=<?php echo $meal['id']; ?>" class="btn-claim" onclick="return confirm('Voulez-vous vraiment réserver ce repas ?')">
                            <i class="fas fa-check-circle"></i> Réserver ce repas
                        </a>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

</body>
</html>
