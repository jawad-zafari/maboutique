<?php
class Account extends Controller
{
    public function __construct()
    {
        parent::__construct();
        // Initialisation sécurisée de la session globale
        Model::sessionInit();
    }

  
    private function requireAuthentication(): int
    {
        $userId = (int) Model::sessionGet('userId');
        
        if ($userId === 0) {
            header('Location: ' . URL . 'Login/index');
            exit;
        }
        
        return $userId;
    }

    // Affiche le tableau de bord du client
    public function index(): void
    {
        // SÉCURITÉ : Vérification de l'authentification
        $userId = $this->requireAuthentication();
        
        $userInfo = $this->model->getUserInfo($userId);
        $orders = $this->model->getOrders($userId);
        
        $totalOrdersCount = count($orders);
        $totalSpent = 0.0;
        
        foreach($orders as $order) {
            if(isset($order['is_paid']) && (int)$order['is_paid'] === 1) {
                $totalSpent += (float)($order['total_amount'] ?? 0);
            }
        }
        
        $latestOrder = $orders[0] ?? null;

        $data = [
            'userInfo'         => $userInfo,
            'orders'           => $orders,
            'totalOrdersCount' => $totalOrdersCount,
            'totalSpent'       => $totalSpent,
            'latestOrder'      => $latestOrder,
            'csrf_token'       => $this->generateCsrfToken()
        ];
        
        $this->view('account/account', $data);
    }

    // Met à jour les informations personnelles du client
    public function saveProfile(): void
    {
        $userId = $this->requireAuthentication();

        // SÉCURITÉ : Blocage des requêtes GET
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('HTTP/1.1 405 Method Not Allowed');
            exit;
        }

        // SÉCURITÉ : Vérification stricte du jeton CSRF
        $this->checkCsrfToken($_POST['csrf_token'] ?? '');

        // SÉCURITÉ (Anti Mass-Assignment) : Extraction et nettoyage des données
        $cleanData = [
            'username'    => trim($_POST['username'] ?? ''),
            'email'       => filter_var(trim($_POST['email'] ?? ''), FILTER_SANITIZE_EMAIL),
            'last_name'   => trim($_POST['last_name'] ?? ''),
            'mobile'      => trim($_POST['mobile'] ?? ''),
            'phone'       => trim($_POST['phone'] ?? ''),
            'address'     => trim($_POST['address'] ?? ''),
            'city'        => trim($_POST['city'] ?? ''),
            'postal_code' => trim($_POST['postal_code'] ?? ''),
            'gender'      => (int) ($_POST['gender'] ?? 1),
            'newsletter'  => isset($_POST['newsletter']) ? 1 : 0
        ];

        // Validation basique côté serveur
        if (!filter_var($cleanData['email'], FILTER_VALIDATE_EMAIL) || empty($cleanData['username'])) {
            header('Location: ' . URL . 'Account/index?error=validation');
            exit;
        }

        $this->model->updateProfile($cleanData, $userId);
        
        header('Location: ' . URL . 'Account/index?success=profile');
        exit;
    }

    
}
?>