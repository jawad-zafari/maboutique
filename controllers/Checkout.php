<?php

class Checkout extends Controller
{
    public function __construct()
    {
        parent::__construct();
        Model::sessionInit(); 
    }

    // Page principale de confirmation de commande
    public function index(string $orderId = null): void
    {
        if ($orderId === null) {
            header('Location: ' . URL . 'Account/orders');
            exit;
        }

        $orderIdInt = (int)$orderId;
        $orderInfo = $this->model->getOrderInfo($orderIdInt);
        
        // SÉCURITÉ (IDOR) : Si la commande n'appartient pas à l'utilisateur connecté, on bloque
        if (!$orderInfo) {
            header('Location: ' . URL . 'Checkout/showError?error=' . urlencode('Commande introuvable ou accès non autorisé.') . '&orderId=' . $orderIdInt);
            exit;
        }

        $data = [
            'orderInfo'  => $orderInfo,
            'csrf_token' => $this->generateCsrfToken()
        ];
        
        $this->view('checkout/checkout', $data);
    }

    // Affichage des erreurs de paiement
    public function showError(): void
    {
        $error = $_GET['error'] ?? 'Une erreur est survenue lors de votre paiement.';
        $orderId = (int)($_GET['orderId'] ?? 0);
        
        $data = [
            'Error'      => $error,
            'orderId'    => $orderId,
            'csrf_token' => $this->generateCsrfToken()
        ];
        
        $this->view('checkout/error', $data);
    }

    
}
?>