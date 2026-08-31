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

    // Traite et sécurise les données du panier
    private function processCartData(): array 
    {
        $rawCartData = $this->model->getCartData() ?? [];
        
        $cart = [];
        $totalPrice = 0;
        $totalDiscount = 0;

        if (isset($rawCartData[0]) && is_array($rawCartData[0]) && isset($rawCartData[1]) && is_numeric($rawCartData[1])) {
            $cart = $rawCartData[0];
            $totalPrice = (float)$rawCartData[1];
            $totalDiscount = (float)($rawCartData[2] ?? 0);
        } else {
            $cart = is_array($rawCartData) ? $rawCartData : [];
        }

        if ($totalPrice <= 0 && !empty($cart)) {
            foreach ($cart as $item) {
                $qty = (int)($item['quantity'] ?? 1);
                $price = (float)($item['price'] ?? 0);
                $totalPrice += ($price * $qty);
            }
        }

        return [$cart, $totalPrice, $totalDiscount];
    }

    public function index(): void 
    {
        $userId = Model::sessionGet('userId');
        
        if ($userId != false) {
            header('Location: ' . URL . 'Order/address');
            exit;
        } else {
            header('Location: ' . URL . 'Login/index?back=Order/address');
            exit;
        }
    }

    
}
?>