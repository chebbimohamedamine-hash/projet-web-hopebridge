<?php
session_start();
require '../db.php';

// Autoriser tous les utilisateurs connectés
if (!isset($_SESSION['user_id'])) {
    header("Location: ../frontend/login.php");
    exit();
}
$user_role = $_SESSION['user_role'];
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HopeBridge | Dashboard</title>
    <link rel="stylesheet" href="dashboard-style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- Leaflet Maps -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <!-- Google Translate -->
    <script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>
</head>
<body>

    <aside class="sidebar">
        <div class="sidebar-logo">
            <i class="fas fa-hand-holding-heart"></i>
            <span>HopeBridge</span>
        </div>
        
        <ul class="menu">
            <li class="menu-item"><a href="dashboard.php" class="menu-link active"><i class="fas fa-th-large"></i> Dashboard</a></li>
            <li class="menu-item"><a href="profile.php" class="menu-link"><i class="fas fa-user-circle"></i> Mon Profil</a></li>
            
            <?php if ($user_role == 'admin'): ?>
                <li class="menu-item"><a href="volunteers.php" class="menu-link"><i class="fas fa-users"></i> Bénévoles</a></li>
                <li class="menu-item"><a href="donations.php" class="menu-link"><i class="fas fa-donate"></i> Dons</a></li>
                <li class="menu-item"><a href="shelters.php" class="menu-link"><i class="fas fa-hotel"></i> Centres d'hébergement</a></li>
                <li class="menu-item"><a href="mailing.php" class="menu-link"><i class="fas fa-envelope-open-text"></i> Mailing & QR</a></li>
            <?php endif; ?>
        </ul>

        <div class="menu-item" style="margin-top: auto;">
            <a href="../logout.php" class="menu-link" style="color: #f87171;"><i class="fas fa-sign-out-alt"></i> Déconnexion</a>
        </div>
    </aside>

    <main class="main-content">
        <header>
            <div>
                <h1>Tableau de Bord</h1>
                <p style="color: var(--text-muted);">Bienvenue, <?php echo htmlspecialchars($_SESSION['user_name']); ?>.</p>
            </div>
            <div style="display: flex; align-items: center; gap: 1.5rem;">
                <!-- Google Translate Element -->
                <div id="google_translate_element"></div>
                <!-- Weather Widget -->
                <div id="weather-widget" class="card" style="padding: 0.5rem 1rem; display: flex; align-items: center; gap: 1rem; margin-bottom: 0;">
                    <i class="fas fa-cloud-sun" style="color: var(--accent-orange); font-size: 1.5rem;"></i>
                    <div>
                        <p id="temp-value" style="font-weight: 700; font-size: 1.1rem; margin: 0;">--°C</p>
                        <p style="font-size: 0.7rem; color: var(--text-muted); margin: 0;">Météo Tunisie</p>
                    </div>
                </div>
            </div>
        </header>

        <section class="grid">
            <div class="card" style="grid-column: span 2; height: 300px; padding: 0;" id="map"></div>
            <?php
            // Stats réelles sécurisées
            try {
                $count_donations = $pdo->query("SELECT COUNT(*) FROM donations")->fetchColumn();
                $sum_donations = $pdo->query("SELECT SUM(amount) FROM donations")->fetchColumn() ?? 0;
                $count_volunteers = $pdo->query("SELECT COUNT(*) FROM volunteers")->fetchColumn();
                $count_shelters = $pdo->query("SELECT COUNT(*) FROM shelters")->fetchColumn();
            } catch (PDOException $e) {
                $count_donations = 0; $sum_donations = 0; $count_volunteers = 0; $count_shelters = 0;
            }
            ?>
            <div class="card">
                <p class="card-title">Total Dons</p>
                <p class="card-value"><?php echo number_format($sum_donations, 0, ',', ' '); ?> €</p>
                <p class="trend up"><i class="fas fa-arrow-up"></i> <?php echo $count_donations; ?> transactions</p>
            </div>
            <div class="card">
                <p class="card-title">Bénévoles</p>
                <p class="card-value"><?php echo $count_volunteers; ?></p>
                <p class="trend up"><i class="fas fa-users"></i> Membres actifs</p>
            </div>
            <div class="card" style="grid-column: span 2;">
                <p class="card-title">Évolution des Dons (€)</p>
                <canvas id="donationChart" height="100"></canvas>
            </div>
        </section>

        <?php if ($user_role == 'admin'): ?>
        <section class="data-section" style="margin-top: 2rem;">
            <h2>Vue d'ensemble de l'activité</h2>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem; margin-top: 1rem;">
                <div class="card">
                    <p class="card-title">Répartition des Bénévoles par Compétence</p>
                    <canvas id="volunteerChart"></canvas>
                </div>
                <div class="card">
                    <p class="card-title">Occupation des Centres</p>
                    <canvas id="shelterChart"></canvas>
                </div>
            </div>
        </section>
        <?php else: ?>
        <section class="data-section">
            <h2>Bienvenue sur votre espace</h2>
            <p>En tant que <strong><?php echo ucfirst($user_role); ?></strong>, vous pouvez gérer votre profil et suivre l'actualité de HopeBridge.</p>
        </section>
        <?php endif; ?>
    </main>

    <script>
        // Google Translate Init
        function googleTranslateElementInit() {
            new google.translate.TranslateElement({pageLanguage: 'fr'}, 'google_translate_element');
        }

        // Leaflet Map Init (Centré sur Tunis)
        const map = L.map('map').setView([36.8065, 10.1815], 12); 
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap'
        }).addTo(map);
        
        // Simuler des centres à Tunis
        L.marker([36.8065, 10.1815]).addTo(map).bindPopup('Centre HopeBridge Tunis').openPopup();

        // Weather Init (Tunis) - Utilisation du format court %t pour éviter le HTML
        fetch('https://wttr.in/Tunis?format=%t')
            .then(response => response.text())
            .then(temp => {
                // Sécurité : si le retour est trop long, c'est probablement du HTML, on met une valeur par défaut
                if (temp.length > 10) {
                    document.getElementById('temp-value').innerText = '24°C';
                } else {
                    document.getElementById('temp-value').innerText = temp.trim();
                }
            })
            .catch(() => { document.getElementById('temp-value').innerText = '22°C'; });
    </script>
    <script>
        // Graphique des Dons (Linéaire)
        const ctxDonation = document.getElementById('donationChart').getContext('2d');
        new Chart(ctxDonation, {
            type: 'line',
            data: {
                labels: ['Jan', 'Féb', 'Mar', 'Avr', 'Mai', 'Juin'],
                datasets: [{
                    label: 'Montant des dons',
                    data: [1200, 1900, 3000, 5000, 2400, <?php echo $sum_donations; ?>],
                    borderColor: '#38bdf8',
                    tension: 0.4,
                    fill: true,
                    backgroundColor: 'rgba(56, 189, 248, 0.1)'
                }]
            },
            options: {
                plugins: { legend: { display: false } },
                scales: { y: { display: false }, x: { grid: { display: false } } }
            }
        });

        <?php if ($user_role == 'admin'): ?>
        // Graphique Bénévoles (Doughnut)
        const ctxVolunteer = document.getElementById('volunteerChart').getContext('2d');
        new Chart(ctxVolunteer, {
            type: 'doughnut',
            data: {
                labels: ['Médical', 'Logistique', 'Social'],
                datasets: [{
                    data: [12, 19, 3],
                    backgroundColor: ['#38bdf8', '#fb923c', '#4ade80'],
                    borderWidth: 0
                }]
            }
        });

        // Graphique Centres (Bar)
        const ctxShelter = document.getElementById('shelterChart').getContext('2d');
        new Chart(ctxShelter, {
            type: 'bar',
            data: {
                labels: ['Centre A', 'Centre B', 'Centre C'],
                datasets: [{
                    label: 'Lits occupés',
                    data: [45, 32, 12],
                    backgroundColor: '#fb923c',
                    borderRadius: 8
                }]
            }
        });
        <?php endif; ?>
    </script>
</body>
</html>
