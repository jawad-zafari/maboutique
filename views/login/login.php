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

