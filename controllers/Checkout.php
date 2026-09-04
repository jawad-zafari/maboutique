<?php

class Checkout extends Controller
{
    public function __construct()
    {
        parent::__construct();
        // Initialisation de la session globale pour vérifier l'utilisateur connecté
        Model::sessionInit(); 
    }

    public function index(string $orderId = null): void
    {
        if ($orderId === null) {
            header('Location: ' . URL . 'Account/orders');
            exit;
        }

        $orderIdInt = (int)$orderId;
        
        // Vérification stricte de l'ID de commande (Protection contre les attaques IDOR)
        $userId = (int) Model::sessionGet('userId');
        $orderInfo = $this->model->getOrderInfo($orderIdInt, $userId);
        
        // Si la commande n'appartient pas à l'utilisateur connecté, on bloque
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

    public function showError(): void
    {
        // Nettoyage de la variable GET par le contrôleur (Protection XSS supplémentaire)
        $rawError = $_GET['error'] ?? 'Une erreur est survenue lors de votre paiement.';
        $error = trim(strip_tags($rawError));
        $orderId = (int)($_GET['orderId'] ?? 0);
        
        $data = [
            'Error'      => $error,
            'orderId'    => $orderId,
            'csrf_token' => $this->generateCsrfToken()
        ];
        
        $this->view('checkout/error', $data);
    }

    public function processMockPaymentAjax(string $orderId): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode(['status' => 'error', 'message' => 'Méthode non autorisée.']);
            exit;
        }

        $this->checkCsrfToken($_POST['csrf_token'] ?? '');
        
        $orderIdInt = (int)$orderId;
        $userId = (int) Model::sessionGet('userId');
        $orderInfo = $this->model->getOrderInfo($orderIdInt, $userId);
        
        if (!$orderInfo) {
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode(['status' => 'error', 'message' => 'Commande introuvable ou accès refusé.']);
            exit;
        }

        // Idempotence : Si déjà payée
        if (!empty($orderInfo['is_paid'])) {
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode(['status' => 'success']);
            exit;
        }

        $successChance = rand(1, 100);
        
        if ($successChance <= 85) {
            $transactionId = 'TXN-' . date('YmdHis') . '-' . rand(1000, 9999);
            $this->model->markOrderAsPaid($orderIdInt, $transactionId);
            
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode(['status' => 'success']);
        } else {
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode(['status' => 'error', 'message' => 'Paiement refusé par votre établissement bancaire (Simulation).']);
        }
        exit;
    }

    public function bankTransfer(string $orderId): void
    {
        $orderIdInt = (int)$orderId;
        $userId = (int) Model::sessionGet('userId');
        $orderInfo = $this->model->getOrderInfo($orderIdInt, $userId);
        
        if (!$orderInfo) {
            header('Location: ' . URL . 'Checkout/showError?error=' . urlencode('Commande introuvable ou accès non autorisé.') . '&orderId=' . $orderIdInt);
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->checkCsrfToken($_POST['csrf_token'] ?? '');

            // Nettoyage strict et extraction manuelle (Anti Mass-Assignment)
            $creditCard = trim(strip_tags($_POST['creditcard'] ?? ''));
            $bank       = trim(strip_tags($_POST['bank'] ?? ''));
            $day        = (int)($_POST['day'] ?? 0);
            $month      = (int)($_POST['month'] ?? 0);
            $year       = (int)($_POST['year'] ?? 0);

            if (empty($creditCard)) {
                header('Location: ' . URL . 'Checkout/bankTransfer/' . $orderIdInt . '?error=missing_card');
                exit;
            }

            // Le modèle reçoit des variables scalaires strictement typées
            $this->model->updateCreditCard($creditCard, $bank, $day, $month, $year, $orderIdInt);
            header('Location: ' . URL . 'Checkout/index/' . $orderIdInt);
            exit;
        }

        $data = [
            'orderInfo'  => $orderInfo,
            'csrf_token' => $this->generateCsrfToken()
        ];
        $this->view('checkout/bank_transfer', $data);
    }
}
?>