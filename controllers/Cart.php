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
        // Bloquer les requêtes GET pour éviter les suppressions accidentelles
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('HTTP/1.1 405 Method Not Allowed');
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode(['status' => 'error', 'message' => 'Méthode non autorisée.']);
            exit;
        }

        // Vérification stricte du jeton CSRF
        $this->checkCsrfToken($_POST['csrf_token'] ?? '');

        $this->model->deleteCartItem((int)$cartRowId);
        
        // Renvoie les nouvelles données au format JSON
        $cartData = $this->model->getCartData();
        
        // Renvoie les données du panier mises à jour au format JSON
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($cartData);
        exit;
    }

    // Mise à jour de la quantité
    public function updateCart(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('HTTP/1.1 405 Method Not Allowed');
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode(['status' => 'error', 'message' => 'Méthode non autorisée.']);
            exit;
        }

        $this->checkCsrfToken($_POST['csrf_token'] ?? '');

        // Extraction manuelle et typage strict
        $cartRowId = isset($_POST['cartRow']) ? (int)$_POST['cartRow'] : 0;
        $quantity  = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1;

        // Validation métier : La quantité doit être positive
        if ($quantity > 0 && $cartRowId > 0) {
            $this->model->updateCartItem($cartRowId, $quantity);
        }
        
        $cartData = $this->model->getCartData();
        
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($cartData);
        exit;
    }

    // Ajout d'un article au panier
    public function addToCart(string $productId): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('HTTP/1.1 405 Method Not Allowed');
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode(['status' => 'error', 'message' => 'Méthode non autorisée.']);
            exit;
        }

        $this->checkCsrfToken($_POST['csrf_token'] ?? '');

        // Nettoyage et typage strict
        $productIdInt = (int)$productId;
        $quantity     = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1;
        $colorId      = isset($_POST['colorId']) ? (int)$_POST['colorId'] : 0;
        $guaranteeId  = isset($_POST['guaranteeId']) ? (int)$_POST['guaranteeId'] : 0;

        // Validation métier
        if ($quantity > 0 && $productIdInt > 0) {
            $this->model->addToCart($productIdInt, $quantity, $colorId, $guaranteeId);
        }
        
        $cartData = $this->model->getCartData();
        
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($cartData);
        exit;
    }
}
?>