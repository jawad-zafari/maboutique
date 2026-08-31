<?php

class App
{
    protected string $controller = 'Index';
    protected string $method = 'index';
    protected array $params = [];

    public function __construct()
    {
        $url = $this->parseUrl($_GET['url'] ?? '');

        if (!empty($url[0])) {
            $this->controller = ucfirst($url[0]);
            unset($url[0]);
        }

        // SÉCURITÉ : Validation stricte du nom du contrôleur
        if (!preg_match('/^[a-zA-Z0-9_]+$/', $this->controller)) {
            $this->controller = 'Error404';
        }

        $controllerPath = 'controllers/' . $this->controller . '.php';

        // Gestion de l'erreur 404 (Contrôleur introuvable)
        if (!file_exists($controllerPath)) {
            $this->controller = 'Error404';
            $controllerPath = 'controllers/Error404.php';
        }

        require_once $controllerPath;
        
        if (class_exists($this->controller)) {
            $controllerObject = new $this->controller();
        } else {
            $this->loadError404();
            return;
        }

        if (isset($url[1])) {
            $this->method = $url[1];
            unset($url[1]);
        }

        // SÉCURITÉ : Validation de la méthode
        if (!preg_match('/^[a-zA-Z0-9_]+$/', $this->method)) {
            $this->method = 'index';
        }

        $this->params = $url ? array_values($url) : [];

        // Gestion de l'erreur 404 (Méthode introuvable ou privée)
        if (method_exists($controllerObject, $this->method)) {
            $reflection = new ReflectionMethod($controllerObject, $this->method);
            
            if ($reflection->isPublic() && $reflection->getDeclaringClass()->getName() === $this->controller) {
                call_user_func_array([$controllerObject, $this->method], $this->params);
            } else {
                // Accès refusé à une méthode protégée/privée
                $this->loadError404();
            }
        } else {
            // Méthode inexistante
            $this->loadError404();
        }
    }

    // Charge le contrôleur d'erreur de manière isolée
    private function loadError404(): void
    {
        require_once 'controllers/Error404.php';
        $errorController = new Error404();
        $errorController->index();
        exit;
    }

   
    //   Nettoie et découpe l'URL passée en paramètre
     
    private function parseUrl(string $url): array
    {
        if (empty($url)) {
            return [];
        }
        $url = filter_var($url, FILTER_SANITIZE_URL);
        $url = rtrim($url, '/');
        return explode('/', $url);
    }
}
?>