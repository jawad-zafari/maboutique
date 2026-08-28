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
