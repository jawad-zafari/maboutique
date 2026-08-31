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

    
}
?>