<?php


// Configuration stricte des cookies de session pour contrer XSS et CSRF
ini_set('session.cookie_httponly', 1);
ini_set('session.use_only_cookies', 1);
ini_set('session.cookie_samesite', 'Lax');

//Forcer les cookies sécurisés si le site est en HTTPS
if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') {
    ini_set('session.cookie_secure', 1);
}

// Chargement sécurisé des paramètres depuis la base de données
try {
    if (class_exists('Model')) {
        $options = Model::getoption();
    } else {
        $options = [];
    }
} catch (Exception $e) {
    $options = [];
}

// Définition de l'URL racine de l'application
define('URL', $options['root'] ?? 'http://localhost/maboutique/');

// Délai d'expiration pour le paiement d'une commande (en heures)
define('PAYMENT_DEADLINE', $options['payment_deadline'] ?? 24);

// Paramètres de personnalisation visuelle du thème
define('menu_color', $options['menu_color'] ?? '');
define('body_color', $options['body_color'] ?? '');

// Génère le chemin absolu pour un fichier CSS
function style(string $path): string 
{
    return URL . 'public/assets/css/' . $path;
}


// Génère le chemin absolu pour un fichier JavaScript
 
function script(string $path): string 
{
    return URL . 'public/assets/js/' . $path;
}


// Fonction de débogage
 
function dd(mixed $var): void 
{
    echo '<pre style="background: #f4f4f4; padding: 10px; border: 1px solid #ccc; border-radius: 5px; text-align: left; direction: ltr;">';
    var_dump($var);
    echo '</pre>';
}
?>