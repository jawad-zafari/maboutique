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

    // Simulation du traitement de paiement (AJAX)
    public function processMockPaymentAjax(string $orderId): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['status' => 'error', 'message' => 'Méthode non autorisée.']);
            exit;
        }

        $this->checkCsrfToken($_POST['csrf_token'] ?? '');
        
        $orderIdInt = (int)$orderId;
        $orderInfo = $this->model->getOrderInfo($orderIdInt);
        
        if (!$orderInfo) {
            echo json_encode(['status' => 'error', 'message' => 'Commande introuvable ou accès refusé.']);
            exit;
        }

        // Idempotence : Si déjà payée
        if (!empty($orderInfo['is_paid'])) {
            echo json_encode(['status' => 'success']);
            exit;
        }

        $successChance = rand(1, 100);
        
        if ($successChance <= 85) {
            $transactionId = 'TXN-' . date('YmdHis') . '-' . rand(1000, 9999);
            $this->model->markOrderAsPaid($orderIdInt, $transactionId);
            
            echo json_encode(['status' => 'success']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Paiement refusé par votre établissement bancaire (Simulation).']);
        }
        exit;
    }

   
}
?>