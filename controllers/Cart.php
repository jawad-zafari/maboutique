<?php

class Cart extends Controller
{
    public function __construct()
    {
        parent::__construct();
        // Initialisation de la session pour la gestion sécurisée du panier
        Model::sessionInit();
    }

    // Afficher la page complète du panier
    public function index(): void
    {
        $cartData = $this->model->getCartData();
        
        // Génération du jeton pour sécuriser les actions du panier
        $data = [
            'cartItems'     => $cartData[0] ?? [],
            'priceTotalAll' => $cartData[1] ?? 0,
            'csrf_token'    => $this->generateCsrfToken()
        ];

        $this->view('cart/cart', $data);
    }

   
}
?>