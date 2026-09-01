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

       
    }

   
}
?>