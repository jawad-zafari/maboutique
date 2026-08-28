<?php
class Login extends Controller
{
    public function __construct()
    {
        parent::__construct();
        // SÉCURITÉ : Initialisation de la session pour la gestion du jeton CSRF
        Model::sessionInit(); 
    }

    // Affiche la page de connexion
    public function index(): void
    {
        $data = [
            'csrf_token' => $this->generateCsrfToken(),
            'old_input'  => [], // Utilisé pour repeupler le formulaire
            'error_msg'  => null
        ];

        $this->view('login/login', $data);
    }

    // Traite la soumission du formulaire et authentifie l'utilisateur
    public function authenticate(): void
    {
        // SÉCURITÉ : Bloquer les requêtes qui ne sont pas de type POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('HTTP/1.1 405 Method Not Allowed');
            exit;
        }

        // SÉCURITÉ : Vérification obligatoire du jeton CSRF
        $this->checkCsrfToken($_POST['csrf_token'] ?? '');

        // SÉCURITÉ (Anti Mass-Assignment) : Extraction manuelle et nettoyage
        $email    = filter_var(trim($_POST['email'] ?? ''), FILTER_SANITIZE_EMAIL);
        $password = $_POST['password'] ?? '';
        $backUrl  = trim($_POST['back_url'] ?? '');

        // Validation basique des entrées
        if (!filter_var($email, FILTER_VALIDATE_EMAIL) || empty($password)) {
            $this->reloadViewWithError($email, $backUrl, 'validation');
            return;
        }

        // Appel au modèle pour récupérer les données de l'utilisateur
        $user = $this->model->getUserByEmail($email);
        
        // Vérification du mot de passe haché
        if ($user && password_verify($password, $user['password'])) {
            
            // SÉCURITÉ : Régénération de l'ID de session contre la fixation de session
            session_regenerate_id(true);
            
            // Configuration des variables de session
            Model::sessionSet('userId', (int)$user['id']);
            Model::sessionSet('loggedIn', true);
            
            // SÉCURITÉ : Protection stricte contre les redirections ouvertes (Open Redirect)
            // L'URL doit commencer par "/" mais pas par "//" (ex: //hacker.com)
            if (!empty($backUrl) && str_starts_with($backUrl, '/') && !str_starts_with($backUrl, '//')) {
                header('Location: ' . URL . ltrim($backUrl, '/'));
            } else {
                header('Location: ' . URL . 'Index/index');
            }
            exit;
        } 

        // Si l'authentification échoue (E-mail ou mot de passe incorrect)
        $this->reloadViewWithError($email, $backUrl, 'credentials');
    }

    // Déconnecte l'utilisateur et détruit la session de manière sécurisée
    public function logout(): void
    {
        Model::sessionInit();
        
        // Vider toutes les variables de la session actuelle
        $_SESSION = array();
        
        // SÉCURITÉ : Suppression sécurisée du cookie de session
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }
        
        // Destruction de la session sur le serveur
        session_destroy();
        header('Location: ' . URL . 'Index/index');
        exit;
    }

    
}
?>