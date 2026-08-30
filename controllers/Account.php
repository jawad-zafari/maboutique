<?php
class Account extends Controller
{
    public function __construct()
    {
        parent::__construct();
        // Initialisation sécurisée de la session globale
        Model::sessionInit();
    }

  
    private function requireAuthentication(): int
    {
        $userId = (int) Model::sessionGet('userId');
        
        if ($userId === 0) {
            header('Location: ' . URL . 'Login/index');
            exit;
        }
        
        return $userId;
    }

   
}
?>