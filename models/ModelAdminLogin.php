<?php

class ModelAdminLogin extends Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function checkUser($form)
    {
        Model::sessionInit();

        // Protection contre la force brute (Max 3 tentatives, blocage 5 minutes)
        $maxAttempts = 3;
        $lockoutTime = 300; 

        if (isset($_SESSION['login_attempts']) && $_SESSION['login_attempts'] >= $maxAttempts) {
            if (time() - $_SESSION['last_attempt_time'] < $lockoutTime) {
                return 'locked';
            } else {
                // Réinitialisation après expiration du temps de blocage
                $_SESSION['login_attempts'] = 0;
            }
        }

        // Nettoyage et validation stricte de l'e-mail
        $emailRaw = $form['email'] ?? '';
        $emailSanitized = filter_var($emailRaw, FILTER_SANITIZE_EMAIL);
        
        if (!filter_var($emailSanitized, FILTER_VALIDATE_EMAIL)) {
            $this->recordFailedAttempt();
            return false;
        }

        $password = $form['password'] ?? '';
        
        if (empty($emailSanitized) || empty($password)) {
            $this->recordFailedAttempt();
            return false;
        }

        // Récupération de l'utilisateur par e-mail
        $sql = "SELECT id, password, role_id FROM users WHERE email = ?";
        $user = $this->doSelect($sql, [$emailSanitized], true);

        if (!empty($user)) {
            // Vérification du mot de passe haché et du contrôle d'accès (RBAC)
            if (password_verify($password, $user['password']) && ($user['role_id'] == 1 || $user['role_id'] == 2)) {
                
                // Connexion réussie : Réinitialiser les tentatives échouées
                $_SESSION['login_attempts'] = 0;
                
                // PRÉVENTION : Régénération de l'ID de session (Session Fixation)
                session_regenerate_id(true);
                
                // Stockage sécurisé
                Model::sessionSet('userId', (int)$user['id']);
                Model::sessionSet('userLevel', (int)$user['role_id']);
                
                return true;
            }
        }

        $this->recordFailedAttempt();
        return false;
    }

    //  Enregistre une tentative de connexion échouée dans la session
    private function recordFailedAttempt()
    {
        if (!isset($_SESSION['login_attempts'])) {
            $_SESSION['login_attempts'] = 0;
        }
        $_SESSION['login_attempts']++;
        $_SESSION['last_attempt_time'] = time();
    }
}
?>