<?php 
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'donor') {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HopeBridge | Offrir un Repas</title>
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
            max-width: 800px;
            margin: 0 auto;
            padding: 2rem;
        }

        .post-card {
            background: rgba(30, 41, 59, 0.7);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(212, 175, 55, 0.2);
            border-radius: 24px;
            padding: 3rem;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
        }

        .post-card h1 {
            font-size: 2.2rem;
            font-weight: 800;
            color: #d4af37;
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .post-card p.subtitle {
            color: #94a3b8;
            margin-bottom: 2.5rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group label {
            display: block;
            font-weight: 600;
            color: #cbd5e1;
            margin-bottom: 0.6rem;
            font-size: 0.9rem;
        }

        .input-wrapper {
            position: relative;
        }

        .input-wrapper i {
            position: absolute;
            left: 1.2rem;
            top: 50%;
            transform: translateY(-50%);
            color: #64748b;
        }

        .form-group input, .form-group textarea {
            width: 100%;
            padding: 1rem 1rem 1rem 3rem;
            background: #0f172a;
            border: 1.5px solid rgba(212, 175, 55, 0.1);
            border-radius: 12px;
            color: #f8fafc;
            font-family: 'Inter', sans-serif;
            font-size: 1rem;
            outline: none;
            transition: all 0.3s;
        }

        .form-group textarea {
            padding-left: 1.2rem;
            min-height: 120px;
            resize: vertical;
        }

        .form-group input:focus, .form-group textarea:focus {
            border-color: #d4af37;
            box-shadow: 0 0 0 4px rgba(212, 175, 55, 0.1);
        }

        .btn-submit {
            width: 100%;
            padding: 1.2rem;
            background: linear-gradient(135deg, #d4af37, #b8860b);
            color: #0f172a;
            border: none;
            border-radius: 12px;
            font-size: 1.1rem;
            font-weight: 800;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.8rem;
            transition: all 0.3s;
            margin-top: 2rem;
        }

        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 15px 30px rgba(212, 175, 55, 0.3);
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
            transition: color 0.3s;
        }

        .nav-back a:hover {
            color: #d4af37;
        }

    </style>
</head>
<body>

    <div class="container">
        <div class="nav-back">
            <a href="index.php"><i class="fas fa-arrow-left"></i> Retour à l'accueil</a>
        </div>

        <div class="post-card">
            <h1><i class="fas fa-utensils"></i> Offrir un Repas</h1>
            <p class="subtitle">Votre générosité permet de nourrir ceux qui en ont besoin. Remplissez les détails du repas disponible.</p>

            <form action="../meal_post.php" method="POST">
                <div class="form-group">
                    <label>Titre du repas / Plat</label>
                    <div class="input-wrapper">
                        <i class="fas fa-tag"></i>
                        <input type="text" name="title" placeholder="Ex: 20 portions de Couscous au poulet" required>
                    </div>
                </div>

                <div class="form-row" style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
                    <div class="form-group">
                        <label>Nombre de portions</label>
                        <div class="input-wrapper">
                            <i class="fas fa-users"></i>
                            <input type="number" name="quantity" value="1" min="1" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Date & Heure limite de récupération</label>
                        <div class="input-wrapper">
                            <i class="fas fa-clock"></i>
                            <input type="datetime-local" name="expiry_date" required>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label>Description / Allergènes / Conseils</label>
                    <textarea name="description" placeholder="Décrivez le repas, les conditions de transport, ou toute information importante..."></textarea>
                </div>

                <button type="submit" class="btn-submit">
                    <i class="fas fa-paper-plane"></i> Publier l'offre de repas
                </button>
            </form>
        </div>
    </div>

</body>
</html>
