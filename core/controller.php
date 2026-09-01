<?php


class Controller
{
    protected mixed $model = null;

    public function __construct()
    {
        // Détecter automatiquement le nom du contrôleur enfant
        $controllerName = get_class($this);

        // Construire le nom du modèle correspondant
        $modelName = 'Model' . $controllerName;
        $modelPath = 'models/' . $modelName . '.php';

        // Charger et instancier le modèle s'il existe
        if (file_exists($modelPath)) {
            require_once $modelPath;
            if (class_exists($modelName)) {
                $this->model = new $modelName();
            }
        }
    }

     
    // Génère un jeton CSRF unique pour sécuriser les formulaires
    public function generateCsrfToken(): string
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }


    //   Vérifie la validité du jeton CSRF reçu
    public function checkCsrfToken(string $token): bool
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (empty($_SESSION['csrf_token']) || empty($token) || !hash_equals($_SESSION['csrf_token'], $token)) {
            die("Erreur de sécurité : Jeton CSRF invalide ou expiré.");
        }
        return true;
    }

    // Échappe les données dynamiques avant l'affichage dans les vues (Protection XSS)
    public function e(?string $value): string
    {
        return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
    }

    // Méthode responsable de l'affichage de l'ensemble des vues avec injection sécurisée des données
    public function view(string $viewName, array $data = []): void
    {
        $controllerName = get_class($this);

        Model::sessionInit();
        $baseModel = new Model();
        $userId = Model::sessionGet('userId');

        // Chargées sur toutes les pages
        
        if (!isset($data['userId'])) {
            $data['userId'] = $userId ?: false;
        }

        if (!isset($data['userLevel'])) {
            // Permet d'éviter l'erreur "Undefined variable" dans les vues Admin
            $data['userLevel'] = $userId ? Model::getUserLevel() : 0;
        }

        if (!isset($data['csrf_token'])) {
            $data['csrf_token'] = $_SESSION['csrf_token'] ?? $this->generateCsrfToken();
        }

        // Chargées uniquement pour le site public
        
        if (strpos($controllerName, 'Admin') !== 0 || $controllerName === 'AdminLogin') {
            
            // Injection des options globales du site
            if (!isset($data['option'])) {
                $data['option'] = Model::getoption();
            }

            // Injection du Menu
            if (!isset($data['menuList'])) {
                $data['menuList'] = $baseModel->getMenu(0);
            }

            // Injection du Panier
            if (!isset($data['cartItems']) || !isset($data['priceTotalAll'])) {
                $cartData = $baseModel->getCart();
                $data['cartItems'] = $cartData[0] ?? [];
                $data['priceTotalAll'] = $cartData[1] ?? 0;
            }

            // Compteur d'articles
            if (!isset($data['cartCount'])) {
                $cartCount = 0;
                foreach ($data['cartItems'] as $item) {
                    $cartCount += (int)($item['quantity'] ?? 1);
                }
                $data['cartCount'] = $cartCount;
            }

            // Compteur de favoris
            if (!isset($data['favCount'])) {
                $data['favCount'] = $userId ? $baseModel->getFavoriteCount((int)$userId) : 0;
            }
        }

        // EXTR_SKIP empêche l'écrasement des variables internes de sécurité
        extract($data, EXTR_SKIP);

        // CHARGEMENT DES VUES (ROUTING INTERNE)

        // Mode Administration
        if (strpos($controllerName, 'Admin') === 0 && $controllerName !== 'AdminLogin') {

            $activeMenu = strtolower(str_replace('Admin', '', $controllerName));

            if (file_exists('views/admin/layout.php')) {
                require 'views/admin/layout.php';
            }

            if (file_exists('views/' . $viewName . '.php')) {
                require 'views/' . $viewName . '.php';
            }

            if (file_exists('views/admin/footer.php')) {
                require 'views/admin/footer.php';
            }

        // Mode Site Public (Client)
        } else {
            if (file_exists('views/header.php') && $controllerName !== 'AdminLogin') {
                require 'views/header.php';
            }

            if (file_exists('views/' . $viewName . '.php')) {
                require 'views/' . $viewName . '.php';
            }

            if (file_exists('views/footer.php') && $controllerName !== 'AdminLogin') {
                require 'views/footer.php';
            }
        }
    }
}
?>