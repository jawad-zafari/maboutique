<?php


class Controller
{
    protected $model;

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

    
    //   Échappe les données dynamiques avant l'affichage dans les vues (Protection XSS)
    
    public function e(?string $value): string
    {
        return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
    }

// Méthode responsable de l'affichage de l'ensemble des sections de la vue.

    public function view(string $viewName, array $data = []): void
    {
        $controllerName = get_class($this);

        // Injection des données globales pour le site public (Non-Admin)
        if (strpos($controllerName, 'Admin') !== 0 || $controllerName === 'AdminLogin') {
            Model::sessionInit();
            $baseModel = new Model();
            $userId = Model::sessionGet('userId');

            if (!isset($data['menuList'])) {
                $data['menuList'] = $baseModel->getMenu(0);
            }

            if (!isset($data['cartItems']) || !isset($data['priceTotalAll'])) {
                $cartData = $baseModel->getCart();
                $data['cartItems'] = $cartData[0] ?? [];
                $data['priceTotalAll'] = $cartData[1] ?? 0;
            }

            
        }

       
}
?>