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

        