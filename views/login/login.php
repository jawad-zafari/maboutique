<?php
// SÉCURITÉ : Récupération des données passées par le contrôleur
$csrfToken    = $csrf_token ?? '';
$oldInput     = $old_input ?? [];
$currentError = $error_msg ?? ($_GET['error'] ?? null);

// Récupération de l'URL de retour (Intended URL)
$backUrl = $oldInput['back_url'] ?? ($_GET['back'] ?? '');
?>

<div class="login-container">
    <div class="login-box">
        
        <div class="login-info">
            <div class="info-icon">
                <i class="fa-solid fa-users-viewfinder" aria-hidden="true"></i>
            </div>
            <h2>Bienvenue sur notre boutique</h2>
            <ul class="benefits-list">
                <li><i class="fa-solid fa-bolt" aria-hidden="true"></i> Achetez plus rapidement et plus simplement</li>
                <li><i class="fa-solid fa-list-check" aria-hidden="true"></i> Gérez facilement votre historique d'achats</li>
                <li><i class="fa-solid fa-heart" aria-hidden="true"></i> Créez vos listes d'envies et suivez leurs évolutions</li>
            </ul>
        </div>

        <div class="login-form-section">
            <h3><i class="fa-solid fa-arrow-right-to-bracket" aria-hidden="true"></i> Connexion à votre compte</h3>
            
            <div id="jsLoginErrorMessage" class="alert-message alert-danger-modern is-hidden" role="alert"></div>
            
            <!-- Gestion des erreurs côté serveur -->
            <?php if ($currentError === 'credentials' || $currentError == 1): ?>
                <div class="alert-message alert-danger-modern show-error" role="alert">
                    <i class="fa-solid fa-circle-exclamation" aria-hidden="true"></i>
                    <span> Identifiants incorrects. Veuillez réessayer.</span>
                </div>
            <?php endif; ?>

            <?php if ($currentError === 'validation'): ?>
                <div class="alert-message alert-danger-modern show-error" role="alert">
                    <i class="fa-solid fa-circle-exclamation" aria-hidden="true"></i>
                    <span> Veuillez remplir correctement tous les champs.</span>
                </div>
            <?php endif; ?>

            <!-- Action mise à jour vers la nouvelle méthode "authenticate" -->
            <form action="<?= URL ?>Login/authenticate" method="POST" id="formLogin" class="modern-form">
                
                <!-- SÉCURITÉ : Jeton CSRF -->
                <input type="hidden" name="csrf_token" value="<?= $this->e($csrfToken) ?>">
                
                <!-- Redirection post-connexion -->
                <?php if(!empty($backUrl)): ?>
                    <input type="hidden" name="back_url" value="<?= $this->e($backUrl) ?>">
                <?php endif; ?>

                <div class="form-group">
                    <label for="email"><i class="fa-solid fa-envelope" aria-hidden="true"></i> Adresse E-mail :</label>
                    <!-- SÉCURITÉ & UX : Repeuplement sécurisé de l'e-mail -->
                    <input type="email" id="email" name="email" class="form-control" dir="ltr" placeholder="exemple@email.com" autocomplete="email" required value="<?= $this->e($oldInput['email'] ?? '') ?>">
                </div>
                
                <div class="form-group">
                    <label for="password"><i class="fa-solid fa-lock" aria-hidden="true"></i> Mot de passe :</label>
                    <!-- Le mot de passe n'est jamais repeuplé -->
                    <input type="password" id="password" name="password" class="form-control" dir="ltr" placeholder="••••••••" autocomplete="current-password" required>
                </div>

                <div class="checkbox-group">
                    <input type="checkbox" id="rememberMe" name="remember_me" value="1">
                    <label for="rememberMe">Se souvenir de moi</label>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn-action" aria-label="Se connecter au site">
                        Se connecter <i class="fa-solid fa-check" aria-hidden="true"></i>
                    </button>
                </div>

                <div class="register-redirect">
                    Nouveau client ? <a href="<?= URL ?>Register/index" class="register-link">Créer un compte</a>
                </div>

            </form>
        </div>

    </div>
</div>
