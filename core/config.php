<?php


// Configuration stricte des cookies de session pour contrer les attaques XSS et CSRF
ini_set('session.cookie_httponly', 1);
ini_set('session.use_only_cookies', 1);
ini_set('session.cookie_samesite', 'Lax');

// Chargement sécurisé des paramètres depuis la base de données
try {
    $options = Model::getoption();
} catch (Exception $e) {
    $options = [];
}

