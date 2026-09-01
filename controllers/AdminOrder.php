<?php

class AdminOrder extends Controller
{
    public function __construct()
    {
        parent::__construct();
        
        // Vérification des droits d'accès avec typage strict
        Model::sessionInit();
        $level = (int) Model::getUserLevel();
        
        // Seul l'administrateur (Niveau 1) peut y accéder
        if ($level !== 1) {
            header('Location: ' . URL . 'AdminLogin/index');
            exit;
        }
    }

    public function index(): void
    {
        $orders = $this->model->getOrders();
        $statuses = $this->model->orderStatus();
        
        $data = [
            'orders' => $orders,
            'statuses' => $statuses,
            'csrf_token' => $this->generateCsrfToken()
        ];
        
        $this->view('admin/admin_order/orders', $data);
    }

    public function bulkUpdateStatus(): void
    {
        // Bloquer tout accès direct via GET
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('HTTP/1.1 405 Method Not Allowed');
            exit('Méthode non autorisée');
        }

        $this->checkCsrfToken($_POST['csrf_token'] ?? '');

        if (isset($_POST['id']) && !empty($_POST['bulk_status_id'])) {
            $this->model->bulkUpdateStatus($_POST['id'], (int)$_POST['bulk_status_id']);
        }
        
        header('Location: ' . URL . 'AdminOrder/index');
        exit;
    }

    public function detail(int $orderId): void
    {
        $orderStatuses = $this->model->orderStatus();
        $orderInfo = $this->model->getOrderInfo($orderId);
        
        $data = [
            'orderInfo' => $orderInfo,
            'order_status' => $orderStatuses,
            'csrf_token' => $this->generateCsrfToken()
        ];
        
        $this->view('admin/admin_order/detail', $data);
    }

    public function editOrder(int $orderId): void
    {
        // Bloquer tout accès direct via GET
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('HTTP/1.1 405 Method Not Allowed');
            exit('Méthode non autorisée');
        }

        $this->checkCsrfToken($_POST['csrf_token'] ?? '');

        $this->model->editOrder($orderId, $_POST);
        
        header('Location: ' . URL . 'AdminOrder/detail/' . $orderId);
        exit;
    }

    public function showInvoice(int $orderId): void
    {
        $orderInfo = $this->model->getOrderInfo($orderId);
        $data = ['orderInfo' => $orderInfo];
        
        // Affichage de la facture
        $this->view('admin/admin_order/factor', $data, 1, 1);
    }

    public function delete(): void
    {
        // Sécurisation de l'action de suppression
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('HTTP/1.1 405 Method Not Allowed');
            exit('Méthode non autorisée');
        }

        $this->checkCsrfToken($_POST['csrf_token'] ?? '');

        if (!empty($_POST['id'])) {
            $this->model->delete($_POST);
        }
        
        header('Location: ' . URL . 'AdminOrder/index');
        exit;
    }
}
?>