<?php session_start(); ?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HopeBridge | Devenir Bénévole</title>
    <link rel="stylesheet" href="style.css?v=4">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
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
            background-color: #f3f4f6 !important;
            border: 1px solid #e5e7eb !important;
            padding: 5px 12px !important;
            border-radius: 50px !important;
            font-family: 'Inter', sans-serif !important;
            font-size: 0.85rem !important;
        }
        .goog-te-gadget-simple span { color: #1f2937 !important; }
        .goog-te-gadget-icon { display: none !important; }
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
            <!-- Google Translate Widget -->
            <li id="google_translate_element" style="margin-left: 10px;"></li>
        </ul>
    </nav>

    <main class="form-container">
        <h2>Rejoindre l'Aventure</h2>
        <p style="margin-bottom: 2rem; color: var(--text-light);">En devenant bénévole, vous offrez plus que votre temps, vous offrez de l'espoir.</p>

        <form id="volunteerForm" class="pro-form">
            <div class="form-group">
                <label>Nom Complet</label>
                <input type="text" name="full_name" placeholder="Ex: Jean Dupont" required>
            </div>

            <div class="form-group">
                <label>Adresse Email</label>
                <input type="email" name="email" placeholder="jean.dupont@exemple.com" required>
            </div>

            <div class="form-group">
                <label>Domaine d'intervention</label>
                <select name="intervention_domain" required class="form-select-pro">
                    <option value="" disabled selected>Choisir un domaine...</option>
                    <option value="alimentaire">🍽️ Aide Alimentaire</option>
                    <option value="logistique">🏠 Logistique et Accueil</option>
                    <option value="medical">💊 Soutien Médical et Psychologique</option>
                    <option value="reinsertion">💼 Réinsertion Professionnelle</option>
                </select>
            </div>

            <div class="form-group">
                <label>Photo Professionnelle (Profil)</label>
                <div class="image-upload-wrapper" onclick="document.getElementById('profile_photo').click()" style="border: 2px dashed #d1d5db; padding: 2rem; border-radius: 12px; text-align: center; cursor: pointer; transition: all 0.3s ease; background: #f8fafc;">
                    <input type="file" id="profile_photo" name="profile_photo" accept="image/*" style="display: none;">
                    <div id="upload-preview" style="display: none; margin-bottom: 1rem;">
                        <img id="preview-img" src="" style="width: 80px; height: 80px; border-radius: 50%; object-fit: cover; border: 3px solid white; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1);">
                    </div>
                    <div id="upload-icon">
                        <i class="fas fa-cloud-upload-alt" style="font-size: 2.5rem; color: #38bdf8; margin-bottom: 0.5rem; display: block;"></i>
                    </div>
                    <span id="file-name" style="color: #64748b; font-size: 0.9rem;">Cliquez pour télécharger une photo</span>
                </div>
            </div>

            <div class="form-group">
                <label>Pourquoi voulez-vous nous rejoindre ?</label>
                <textarea name="motivation" rows="4" placeholder="Parlez-nous de vos motivations et compétences..." required></textarea>
            </div>

            <button type="submit" id="submitBtn" class="btn btn-primary" style="width: 100%; display: flex; align-items: center; justify-content: center; gap: 0.5rem;">
                <span>Envoyer ma Candidature</span>
                <i class="fas fa-paper-plane"></i>
            </button>
        </form>
    </main>

    <!-- Modal Feedback Pro -->
    <div id="feedbackModal" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.6); backdrop-filter: blur(4px); z-index: 1000; align-items: center; justify-content: center;">
        <div style="background: white; padding: 3rem; border-radius: 20px; text-align: center; max-width: 450px; width: 90%; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.25); animation: modalIn 0.4s cubic-bezier(0.16, 1, 0.3, 1);">
            <div id="modalIcon" style="width: 80px; height: 80px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.5rem; font-size: 2.5rem;">
                <i class="fas fa-check"></i>
            </div>
            <h3 id="modalTitle" style="font-size: 1.5rem; color: #1e293b; margin-bottom: 1rem;"></h3>
            <p id="modalMsg" style="color: #64748b; line-height: 1.6; margin-bottom: 2rem;"></p>
            <button id="modalBtn" class="btn btn-primary" style="width: 100%;">Continuer</button>
        </div>
    </div>

    <style>
        @keyframes modalIn {
            from { transform: scale(0.9); opacity: 0; }
            to { transform: scale(1); opacity: 1; }
        }
        .image-upload-wrapper:hover { border-color: #38bdf8; background: #f0f9ff; }
        .loading { opacity: 0.7; pointer-events: none; }
        .loading i { animation: spin 1s linear infinite; }
        @keyframes spin { from { transform: rotate(0deg); } to { transform: rotate(360deg); } }
    </style>

    <script>
        // Preview Image
        document.getElementById('profile_photo').onchange = function(e) {
            const file = e.target.files[0];
            if (file) {
                document.getElementById('file-name').innerText = file.name;
                const reader = new FileReader();
                reader.onload = function(event) {
                    document.getElementById('preview-img').src = event.target.result;
                    document.getElementById('upload-preview').style.display = 'block';
                    document.getElementById('upload-icon').style.display = 'none';
                };
                reader.readAsDataURL(file);
            }
        };

        // Form Submission
        document.getElementById('volunteerForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            const btn = document.getElementById('submitBtn');
            const btnText = btn.querySelector('span');
            const btnIcon = btn.querySelector('i');
            
            btn.classList.add('loading');
            btnText.textContent = 'Envoi en cours...';
            btnIcon.className = 'fas fa-circle-notch';

            const formData = new FormData(e.target);
            
            try {
                const response = await fetch('../volunteer_post.php', {
                    method: 'POST',
                    body: formData
                });
                
                const result = await response.json();
                
                const modal = document.getElementById('feedbackModal');
                const modalIcon = document.getElementById('modalIcon');
                const modalTitle = document.getElementById('modalTitle');
                const modalMsg = document.getElementById('modalMsg');
                const modalBtn = document.getElementById('modalBtn');

                if (result.success) {
                    modalIcon.style.background = '#dcfce7';
                    modalIcon.style.color = '#22c55e';
                    modalIcon.innerHTML = '<i class="fas fa-check"></i>';
                    modalTitle.textContent = 'Candidature Reçue !';
                    modalMsg.textContent = result.message;
                    modalBtn.onclick = () => window.location.href = 'index.php';
                } else {
                    modalIcon.style.background = '#fee2e2';
                    modalIcon.style.color = '#ef4444';
                    modalIcon.innerHTML = '<i class="fas fa-exclamation-triangle"></i>';
                    modalTitle.textContent = 'Oups !';
                    modalMsg.textContent = result.message;
                    modalBtn.onclick = () => modal.style.display = 'none';
                    
                    btn.classList.remove('loading');
                    btnText.textContent = 'Envoyer ma Candidature';
                    btnIcon.className = 'fas fa-paper-plane';
                }
                modal.style.display = 'flex';
            } catch (error) {
                console.error('Erreur:', error);
                alert('Une erreur est survenue.');
                btn.classList.remove('loading');
                btnText.textContent = 'Envoyer ma Candidature';
                btnIcon.className = 'fas fa-paper-plane';
            }
        });


    </script>
</body>
</html>
