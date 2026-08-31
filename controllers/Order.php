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

    // Vérifie si le panier n'est pas vide
    private function checkCartNotEmpty(): void
    {
        $cartData = $this->processCartData();
        $items = $cartData[0] ?? [];
        
        if (empty($items)) {
            header('Location: ' . URL . 'Cart/index?error=empty_cart');
            exit;
        }
    }

    
}
?>