<?php


class Order extends Controller 
{
    public function __construct() 
    {
        parent::__construct();
        Model::sessionInit(); 
    }

    // Vérifie si l'utilisateur est connecté
    private function checkLogin(): void
    {
        $userId = Model::sessionGet('userId');
        if ($userId === false) {
            header('Location: ' . URL . 'Login/index?back=Order/address');
            exit;
        }
    }

   
}
?>