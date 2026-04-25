<?php session_start(); ?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HopeBridge | Ensemble pour un toit</title>
    <meta name="description" content="Soutenez les personnes sans-abri. Votre don peut changer une vie.">
    <link rel="stylesheet" href="style.css?v=6">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <!-- Google Translate Script Pro -->
    <script type="text/javascript">
        function googleTranslateElementInit() {
            new google.translate.TranslateElement({
                pageLanguage: 'fr',
                includedLanguages: 'ar,en,es,fr,it,de,pt,zh-CN,ja,ru,tr', 
                layout: google.translate.TranslateElement.InlineLayout.SIMPLE,
                autoDisplay: false
            }, 'google_translate_element');
        }
    </script>
    <script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>
    <style>
        #google_translate_element { display: inline-block; vertical-align: middle; }
        .goog-te-gadget-simple {
            background-color: rgba(255, 255, 255, 0.1) !important;
            border: 1px solid rgba(255, 255, 255, 0.2) !important;
            padding: 5px 12px !important;
            border-radius: 50px !important;
            font-family: 'Inter', sans-serif !important;
            font-size: 0.85rem !important;
        }
        .goog-te-gadget-simple span { color: white !important; }
        nav.scrolled .goog-te-gadget-simple span { color: #1f2937 !important; }
        nav.scrolled .goog-te-gadget-simple { background-color: #f3f4f6 !important; border: 1px solid #e5e7eb !important; }
        .goog-te-gadget-icon { display: none !important; }
    </style>
    <!-- Leaflet CSS for Map -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

</head>
<body>

    <nav id="navbar">
        <div class="logo">
            <i class="fas fa-hand-holding-heart"></i>
            <span>HopeBridge</span>
        </div>
        <ul class="nav-links">

            <li><a href="#home">Accueil</a></li>
            <li><a href="volunteer.php">Devenir Bénévole</a></li>
            <li><a href="#mission">Notre Mission</a></li>
            <li><a href="#impact">Impact</a></li>
            
            <?php if(isset($_SESSION['user_id'])): ?>
                <li style="color: var(--primary); font-weight: 700;">Salut, <?php echo htmlspecialchars($_SESSION['user_name']); ?></li>
                
                <?php if($_SESSION['user_role'] == 'donor'): ?>
                    <li><a href="post_meal.php" style="color: #fbbf24;"><i class="fas fa-plus-circle"></i> Offrir un Repas</a></li>
                <?php endif; ?>

                <?php if($_SESSION['user_role'] == 'admin'): ?>
                    <li><a href="../backend/dashboard.php" style="color: var(--primary); font-weight: bold;"><i class="fas fa-user-shield"></i> Dashboard</a></li>
                <?php endif; ?>
                <li><a href="../logout.php" style="color: #f87171;">Déconnexion</a></li>
            <?php else: ?>
                <li><a href="login.php" style="color: var(--primary); font-weight: bold; text-decoration: none;">Connexion</a></li>
                <li><a href="register.php" style="padding: 0.6rem 1.4rem; border: 2px solid var(--primary); border-radius: 50px; color: var(--primary); font-weight: bold; text-decoration: none; transition: all 0.3s ease;" onmouseover="this.style.background='var(--primary)'; this.style.color='white'" onmouseout="this.style.background='transparent'; this.style.color='var(--primary)'">Inscription</a></li>
            <?php endif; ?>
            <!-- Google Translate Widget -->
            <li id="google_translate_element" style="margin-left: 10px;"></li>
        </ul>
    </nav>

    <header class="hero" id="home">
        <h1>Redonner de l'Espoir, <br>Un Toit à la fois.</h1>
        <p>En Tunisie, des milliers de personnes manquent d'un abri sûr. Ensemble, brisons le cycle de la pauvreté en offrant nourriture, logement et opportunités d'avenir.</p>
        <div class="hero-btns">
            <a href="donations.php" class="btn-donate"><i class="fas fa-heart"></i> Faire un Don</a>
            <a href="volunteer.php" class="btn-volunteer-hero"><i class="fas fa-hands-helping"></i> Devenir Bénévole</a>
        </div>
    </header>

    <section class="stats" id="impact">
        <div class="stat-card">
            <span class="stat-number">15k+</span>
            <span class="stat-label">Repas Servis</span>
        </div>
        <div class="stat-card">
            <span class="stat-number">1,200</span>
            <span class="stat-label">Personnes Logées</span>
        </div>
        <div class="stat-card">
            <span class="stat-number">500+</span>
            <span class="stat-label">Bénévoles Actifs</span>
        </div>
        <div class="stat-card">
            <span class="stat-number">85%</span>
            <span class="stat-label">Réinsertion Réussie</span>
        </div>
    </section>

    <section class="mission" id="mission">
        <h2>Comment nous aidons</h2>
        <p>Une approche holistique pour aider nos frères et sœurs à retrouver leur dignité.</p>
        
        <div class="mission-grid">
            <a href="logement_urgence.php" class="mission-card" style="text-decoration: none; color: inherit; display: block;">
                <img src="https://cdn.heinze.de/m1/99/13077199/images/00/13077200px960x640.jpg" alt="Logement d'Urgence" class="mission-img">
                <div class="mission-content">
                    <i class="fas fa-home"></i>
                    <h3>Logement d'Urgence</h3>
                    <p>Nous fournissons des abris sûrs et chaleureux pour les nuits les plus froides. Cliquez pour voir notre galerie.</p>
                </div>
            </a>
            <a href="aide_alimentaire.php" class="mission-card" style="text-decoration: none; color: inherit; display: block;">
                <img src="assets/aide1.png?v=1" alt="Aide Alimentaire" class="mission-img">
                <div class="mission-content">
                    <i class="fas fa-utensils"></i>
                    <h3>Aide Alimentaire</h3>
                    <p>Des repas nutritifs servis quotidiennement dans nos centres communautaires. Cliquez pour voir notre galerie.</p>
                </div>
            </a>
            <a href="reinsertion.php" class="mission-card" style="text-decoration: none; color: inherit; display: block;">
                <img src="https://images.unsplash.com/photo-1521737604893-d14cc237f11d?auto=format&fit=crop&w=600&q=80" alt="Réinsertion" class="mission-img">
                <div class="mission-content">
                    <i class="fas fa-graduation-cap"></i>
                    <h3>Réinsertion</h3>
                    <p>Formations professionnelles et accompagnement pour retrouver un emploi stable. Cliquez pour voir notre galerie.</p>
                </div>
            </a>
        </div>
    </section>
    
    <!-- ===== SECTION LOCALISATION CORPORATE HUB ===== -->
    <section class="location-section" id="location">
        <div class="location-header-v4">
            <div class="underline"></div>
            <h2>Où nous trouver ?</h2>
            <p>Retrouvez toutes les informations pour nous rendre visite ou nous contacter directement.</p>
        </div>

        <div class="location-grid-v4">
            <!-- Carte 1: Adresse -->
            <div class="contact-card-v4">
                <div class="card-icon-v4">
                    <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>
                </div>
                <h3>Notre Siège</h3>
                <p>Avenue Habib Bourguiba,<br>1001 Tunis, Tunisie</p>
            </div>

            <!-- Carte 2: Contact -->
            <div class="contact-card-v4">
                <div class="card-icon-v4">
                    <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l2.28-2.28a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg>
                </div>
                <h3>Ligne Directe</h3>
                <p>Support : (+216) 71 234 567<br>Urgences : 24h/24 & 7j/7</p>
            </div>

            <!-- Carte 3: Email -->
            <div class="contact-card-v4">
                <div class="card-icon-v4">
                    <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
                </div>
                <h3>E-mail Officiel</h3>
                <p>contact@hopebridge.org<br>partenariat@hopebridge.org</p>
            </div>
        </div>

        <div class="map-outer-v4">
            <div id="map"></div>
        </div>
    </section>


    <!-- ===== FOOTER PROFESSIONNEL ===== -->
    <footer class="site-footer">

        <!-- Barre supérieure : réseaux sociaux -->
        <div class="footer-social-bar">
            <a href="#" aria-label="Facebook"    class="footer-social"><i class="fab fa-facebook-f"></i></a>
            <a href="#" aria-label="Twitter"     class="footer-social"><i class="fab fa-twitter"></i></a>
            <a href="#" aria-label="Instagram"   class="footer-social"><i class="fab fa-instagram"></i></a>
            <a href="#" aria-label="LinkedIn"    class="footer-social"><i class="fab fa-linkedin-in"></i></a>
            <a href="#" aria-label="YouTube"     class="footer-social"><i class="fab fa-youtube"></i></a>
        </div>

        <!-- Partenaires / Certifications -->
        <div class="footer-badges">
            <div class="footer-badge badge-excellence">
                <i class="fas fa-award"></i>
                <span>Certifié ISO</span>
            </div>
            <div class="footer-badge badge-excellence">
                <i class="fas fa-landmark"></i>
                <span>Accrédité ONG</span>
            </div>
            <div class="footer-badge badge-excellence">
                <i class="fas fa-medal"></i>
                <span>Care Alliance</span>
            </div>
            <div class="footer-badge badge-excellence">
                <i class="fas fa-certificate"></i>
                <span>Don Éthique</span>
            </div>
            <div class="footer-badge badge-excellence">
                <i class="fas fa-star"></i>
                <span>Label d'Excellence</span>
            </div>
        </div>

        <!-- Copyright -->
        <div class="footer-legal">
            <p class="footer-copy">&copy; 2026 <strong>HopeBridge</strong>. Tous droits réservés.</p>
        </div>

        <!-- Divider -->
        <div class="footer-divider"></div>

        <!-- Coordonnées -->
        <div class="footer-contact">
            <p>
                Contact
                <a href="#">Bureau Central</a>
                par téléphone&nbsp;<strong>(+216) 71 234 567</strong>
                &nbsp;ou&nbsp;
                <a href="mailto:contact@hopebridge.org">contact@hopebridge.org</a>
            </p>
            <p>
                Conformité&nbsp;:&nbsp;<strong>(+216) 71 234 890</strong>
                &nbsp;ou&nbsp;
                <a href="mailto:conformite@hopebridge.org">conformite@hopebridge.org</a>
            </p>
            <p>
                <strong>Options Fax :</strong> Dossiers médicaux : (+216) 71 111 222 &nbsp;·&nbsp; Nouvelles admissions : (+216) 71 333 444
            </p>
        </div>

        <!-- Divider -->
        <div class="footer-divider"></div>

        <!-- Mentions légales -->
        <div class="footer-disclaimer">
            <p>*Certains services ne sont pas disponibles dans toutes les régions. Nous travaillons activement à l'extension de notre réseau de centres communautaires à travers le territoire.</p>
            <p>Le Programme HopeBridge®, la certification BénévoleActif® et les labels associés sont des marques déposées protégées par la loi.</p>
        </div>

    </footer>

    <script src="script.js"></script>
    <!-- Voice Welcome & Scripts -->
    <script>
        function welcomeVoice() {
            const msg = new SpeechSynthesisUtterance();
            msg.text = "Bienvenue dans notre site HopeBridge. Ensemble pour un toit.";
            msg.lang = 'fr-FR';
            msg.rate = 0.9; // Vitesse un peu plus lente pour la clarté
            window.speechSynthesis.speak(msg);
        }

        // Déclencher au premier clic sur la page (car les navigateurs bloquent l'audio auto-play)
        document.addEventListener('click', function() {
            if (!sessionStorage.getItem('voicePlayed')) {
                welcomeVoice();
                sessionStorage.setItem('voicePlayed', 'true');
            }
        }, { once: true });



        // Initialisation de la carte Leaflet
    </script>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const tunis = [36.8065, 10.1815];
            // Choix du style de carte (Expert tiles)
            const tileStyle = 'https://{s}.basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}{r}.png'; // Voyager pour normal
            
            const map = L.map('map', {
                scrollWheelZoom: false,
                zoomControl: false // On cache pour un look plus épuré
            }).setView(tunis, 14);
            
            L.tileLayer(tileStyle, {
                attribution: '&copy; CartoDB'
            }).addTo(map);

            // Ajout du zoom en bas à droite
            L.control.zoom({ position: 'bottomright' }).addTo(map);

            // Marqueur Premium personnalisé
            const markerIcon = L.divIcon({
                className: 'custom-div-icon',
                html: `<div style="background-color: #0d9488; width: 20px; height: 20px; border-radius: 50%; border: 4px solid white; box-shadow: 0 0 15px rgba(0,0,0,0.3);"></div>`,
                iconSize: [20, 20],
                iconAnchor: [10, 10]
            });

            L.marker(tunis, {icon: markerIcon}).addTo(map)
                .bindPopup('<div style="font-family: \'Inter\', sans-serif; padding: 10px;"><strong>Siège HopeBridge</strong><br>Tunis, Tunisie</div>');
            


            setTimeout(() => { map.invalidateSize(); }, 500);
        });
    </script>
</body>


</html>
