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

        // Extraction et sécurisation des identifiants dans le contrôleur
        $ids = $_POST['id'] ?? [];
        $statusId = (int)($_POST['bulk_status_id'] ?? 0);

        if (!empty($ids) && is_array($ids) && $statusId > 0) {
            $safeIds = array_map('intval', $ids);
            $this->model->bulkUpdateStatus($safeIds, $statusId);
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

        // Nettoyage et préparation des données avant de les transmettre au modèle
        $cleanData = [
            'address'       => trim(strip_tags($_POST['address'] ?? '')),
            'postal_code'   => trim(strip_tags($_POST['postal_code'] ?? '')),
            'phone'         => trim(strip_tags($_POST['phone'] ?? '')),
            'tracking_code' => trim(strip_tags($_POST['tracking_code'] ?? '')),
            'admin_note'    => trim(strip_tags($_POST['admin_note'] ?? '')),
            'pay_status'    => (int)($_POST['pay_status'] ?? 0),
            'order_status'  => (int)($_POST['order_status'] ?? 1)
        ];

        // Le modèle reçoit un tableau parfaitement propre
        $this->model->editOrder($orderId, $cleanData);
        
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

       
        
        header('Location: ' . URL . 'AdminOrder/index');
        exit;
    }
}
?>