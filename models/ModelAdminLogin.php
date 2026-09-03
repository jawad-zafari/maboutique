<?php

class ModelAdminLogin extends Model
{
    public function __construct()
    {
        parent::__construct();
    }

    // Vérifie les informations d'identification de l'utilisateur et gère la sécurité de la connexion
    public function checkUser(string $email, string $password)
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

        // Récupération de l'utilisateur par e-mail
        $sql = "SELECT id, password, role_id FROM users WHERE email = ?";
        $user = $this->doSelect($sql, [$email], true);

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

    // Enregistre une tentative de connexion échouée dans la session
    // Rendue publique pour que le contrôleur puisse l'appeler en cas de validation échouée
    public function recordFailedAttempt()
    {
        Model::sessionInit();
        if (!isset($_SESSION['login_attempts'])) {
            $_SESSION['login_attempts'] = 0;
        }
        $_SESSION['login_attempts']++;
        $_SESSION['last_attempt_time'] = time();
    }
}
?>