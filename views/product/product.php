<?php

// Récupération et sécurisation des données transmises par le contrôleur
$product = $data['productInfo'] ?? [];
$gallery = $data['gallery'] ?? [];
$csrfToken = $data['csrf_token'] ?? '';
$userId = Model::sessionGet('userId');

//  Récupération de l'état "Favori" calculé par le contrôleur 
$isFavorite = $data['isFavorite'] ?? false;

