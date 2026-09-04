<?php

class Register extends Controller 
{
    public function __construct() 
    {
        parent::__construct();
        // SÉCURITÉ : Initialisation de la session pour la gestion du jeton CSRF et des messages
        Model::sessionInit(); 
    }
    
    // Affiche la page du formulaire d'inscription
    public function index(): void 
    {
        $data = [
            'csrf_token' => $this->generateCsrfToken(),
            'old_input'  => [], // Utilisé pour repeupler le formulaire en cas d'erreur
            'error_msg'  => null
        ];
        
        $this->view('register/register', $data);
    }

    // Traite les données soumises pour créer un compte utilisateur
    public function save(): void 
    {
        // SÉCURITÉ : N'accepter que les requêtes de type POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('HTTP/1.1 405 Method Not Allowed');
            exit;
        }

        // SÉCURITÉ : Vérification stricte du jeton CSRF
        $this->checkCsrfToken($_POST['csrf_token'] ?? '');
        
        // SÉCURITÉ (Anti Mass-Assignment) : Extraction et nettoyage manuel des champs autorisés
        $email           = filter_var(trim($_POST['email'] ?? ''), FILTER_SANITIZE_EMAIL);
        $password        = $_POST['password'] ?? '';
        $passwordConfirm = $_POST['password_confirm'] ?? '';
        $lastName        = trim($_POST['last_name'] ?? '');
        $mobile          = trim($_POST['mobile'] ?? '');
        $newsletter      = isset($_POST['newsletter']) ? 1 : 0;
        
        // Validation des données côté serveur
        $isValid = true;
        if (!filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($password) < 6 || $password !== $passwordConfirm || empty($lastName) || empty($mobile)) {
            $isValid = false;
        }

        // Si la validation échoue, on recharge la vue avec les anciennes données
        if (!$isValid) {
            $data = [
                'csrf_token' => $this->generateCsrfToken(),
                'old_input'  => $_POST,
                'error_msg'  => 'validation'
            ];
            $this->view('register/register', $data);
            return;
        }

        // Hachage du mot de passe avant l'insertion en base de données
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $roleId = 3; // Rôle client standard par défaut
        $createdAt = date('Y-m-d H:i:s');

        // Appel au modèle pour insérer l'utilisateur dans la base de données
        $isRegistered = $this->model->insertUser($email, $hashedPassword, $lastName, $mobile, $newsletter, $roleId, $createdAt);
        
        if ($isRegistered) {
            // Redirection vers la page de connexion après une inscription réussie
            header('Location: ' . URL . 'Login/index?success=registered');
            exit;
        } else {
            // L'utilisateur existe déjà, on recharge la vue avec un message d'erreur
            $data = [
                'csrf_token' => $this->generateCsrfToken(),
                'old_input'  => $_POST,
                'error_msg'  => 'exists'
            ];
            $this->view('register/register', $data);
        }
    }
}
?>