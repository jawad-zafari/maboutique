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

    // Suppression d'un article (Validation POST + CSRF)
    public function deleteCart(string $cartRowId): void
    {
        // Bloquer les requêtes GET pour éviter les suppressions accidentelles ou malveillantes
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('HTTP/1.1 405 Method Not Allowed');
            echo json_encode(['status' => 'error', 'message' => 'Méthode non autorisée.']);
            exit;
        }

       
    }

    
}
?>