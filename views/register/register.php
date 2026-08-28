<?php

// SÉCURITÉ : Récupération et nettoyage du jeton CSRF transmis par le contrôleur
$csrfToken = $data['csrf_token'] ?? '';
?>

