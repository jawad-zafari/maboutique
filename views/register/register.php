<?php

// SÉCURITÉ : Récupération et nettoyage du jeton CSRF transmis par le contrôleur
$csrfToken = $data['csrf_token'] ?? '';
?>

<div class="register-container">
    
    <div class="register-box">
        
        <div class="register-info">
            <div class="info-icon">
                <i class="fa-solid fa-user-plus" aria-hidden="true"></i>
            </div>
            <h2>Créer un compte client</h2>
            <ul class="benefits-list">
                <li>
                    <i class="fa-solid fa-bolt" aria-hidden="true"></i> 
                    <span>Achetez plus rapidement et plus simplement</span>
                </li>
                <li>
                    <i class="fa-solid fa-list-check" aria-hidden="true"></i> 
                    <span>Gérez facilement votre historique d'achats</span>
                </li>
                <li>
                    <i class="fa-solid fa-heart" aria-hidden="true"></i> 
                    <span>Créez vos listes d'envies et favoris</span>
                </li>
            </ul>
        </div>

        <div class="register-form-section">
            <h3>
                <i class="fa-solid fa-pen-to-square" aria-hidden="true"></i> 
                <span>Inscription</span>
            </h3>

            <div id="jsRegisterErrorMessage" class="alert-message alert-danger-modern is-hidden" role="alert"></div>

            <?php if (isset($_GET['error']) && $_GET['error'] === 'exists'): ?>
                <div class="alert-message alert-danger-modern" role="alert">
                    <i class="fa-solid fa-circle-exclamation" aria-hidden="true"></i>
                    <span>Un compte existe déjà avec cette adresse e-mail.</span>
                </div>
            <?php endif; ?>

            <?php if (isset($_GET['error']) && $_GET['error'] === 'validation'): ?>
                <div class="alert-message alert-danger-modern" role="alert">
                    <i class="fa-solid fa-circle-exclamation" aria-hidden="true"></i>
                    <span>Veuillez vérifier les informations saisies dans le formulaire.</span>
                </div>
            <?php endif; ?>

        