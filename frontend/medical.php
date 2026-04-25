<?php session_start(); ?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HopeBridge | Soutien Médical & Psychologique</title>
    <link rel="stylesheet" href="style.css?v=6">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        .gallery-container {
            padding: 8rem 5% 4rem;
            max-width: 1200px;
            margin: 0 auto;
        }
        .gallery-header {
            text-align: center;
            margin-bottom: 3rem;
        }
        .gallery-header h1 {
            color: var(--primary);
            font-size: 2.5rem;
            margin-bottom: 1rem;
        }
        .gallery-header p {
            color: var(--text-light);
            font-size: 1.1rem;
            max-width: 600px;
            margin: 0 auto;
        }
        .gallery-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 2rem;
        }
        .gallery-item {
            border-radius: 16px;
            overflow: hidden;
            box-shadow: var(--shadow);
            transition: var(--transition);
            aspect-ratio: 4/3;
        }
        .gallery-item:hover {
            transform: scale(1.03);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.2);
        }
        .gallery-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }
        .back-btn-container {
            text-align: center;
            margin-top: 4rem;
        }
    </style>
</head>
<body>

    <nav class="scrolled">
        <div class="logo">
            <i class="fas fa-hand-holding-heart"></i>
            <a href="index.php" style="text-decoration: none; color: inherit;"><span>HopeBridge</span></a>
        </div>
        <ul class="nav-links">

            <li><a href="index.php">Accueil</a></li>
            <li><a href="volunteer.php" class="btn-volunteer">Devenir Bénévole</a></li>
            <?php if(isset($_SESSION['user_id'])): ?>
                <li style="color: var(--primary); font-weight: 700;">Salut, <?php echo htmlspecialchars($_SESSION['user_name']); ?></li>
                <?php if($_SESSION['user_role'] == 'admin'): ?>
                    <li><a href="../backend/dashboard.php" style="color: var(--primary); font-weight: bold;"><i class="fas fa-user-shield"></i> Dashboard</a></li>
                <?php endif; ?>
                <li><a href="../logout.php" style="color: #f87171;">Déconnexion</a></li>
            <?php else: ?>
                <li><a href="login.php" style="color: var(--primary); font-weight: 700;">Connexion</a></li>
            <?php endif; ?>
        </ul>
    </nav>

    <main class="gallery-container">
        <div class="gallery-header">
            <h1>Soutien Médical & Psychologique</h1>
            <p>Nous offrons des soins de santé de base et un accompagnement psychologique pour aider les personnes à surmonter les traumatismes de la vie de rue.</p>
        </div>

        <div class="gallery-grid">
            <div class="gallery-item">
                <img src="https://images.unsplash.com/photo-1576091160550-2173dba999ef?auto=format&fit=crop&w=600&q=80" alt="Soutien Médical" class="gallery-img">
            </div>
            <div class="gallery-item">
                <img src="https://images.unsplash.com/photo-1527613426441-4da17471b66d?auto=format&fit=crop&w=600&q=80" alt="Consultation" class="gallery-img">
            </div>
            <div class="gallery-item">
                <img src="https://images.unsplash.com/photo-1505751172107-16053805373a?auto=format&fit=crop&w=600&q=80" alt="Pharmacie" class="gallery-img">
            </div>
        </div>

        <div class="back-btn-container">
            <a href="index.php" class="btn btn-secondary" style="border-color: var(--primary); color: var(--primary);"><i class="fas fa-arrow-left"></i> Retour à l'accueil</a>
        </div>
    </main>

    <script>

    </script>
</body>
</html>
