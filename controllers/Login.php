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

        
    }
}
?>