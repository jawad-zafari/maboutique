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

    // Gère le changement de mot de passe depuis le profil
    public function updatePassword(): void
    {
        $userId = $this->requireAuthentication();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('HTTP/1.1 405 Method Not Allowed');
            exit;
        }

        $this->checkCsrfToken($_POST['csrf_token'] ?? '');

        $passOld = $_POST['pass_old'] ?? '';
        $passNew = $_POST['pass_new'] ?? '';
        $passConfirm = $_POST['pass_confirm'] ?? '';

        // Validation du nouveau mot de passe
        if (empty($passNew) || strlen($passNew) < 6 || $passNew !== $passConfirm) {
            header('Location: ' . URL . 'Account/index?error=password_mismatch');
            exit;
        }

        // SÉCURITÉ : Vérification de l'ancien mot de passe
        $userHash = $this->model->getUserPasswordHash($userId);
        
        if (password_verify($passOld, $userHash)) {
            // Hachage du nouveau mot de passe dans le contrôleur (Respect du MVC)
            $hashedPassword = password_hash($passNew, PASSWORD_DEFAULT);
            $this->model->updatePassword($userId, $hashedPassword);
            
            header('Location: ' . URL . 'Account/index?success=password');
        } else {
            header('Location: ' . URL . 'Account/index?error=password');
        }
        exit;
    }

    // Supprime définitivement le compte client
    public function deleteAccount(): void
    {
        $userId = $this->requireAuthentication();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('HTTP/1.1 405 Method Not Allowed');
            exit;
        }

        $this->checkCsrfToken($_POST['csrf_token'] ?? '');

        $password = $_POST['password'] ?? '';

        // Double vérification par mot de passe avant la suppression
        $userHash = $this->model->getUserPasswordHash($userId);
        
        if (password_verify($password, $userHash)) {
            $this->model->deleteUser($userId);
            header('Location: ' . URL . 'Login/logout');
        } else {
            header('Location: ' . URL . 'Account/index?error=delete');
        }
        exit;
    }

    // Récupère les détails d'une commande via AJAX
    public function getOrderDetails(string $orderId): void
    {
        $userId = $this->requireAuthentication();

        header('Content-Type: application/json');
        ob_clean(); 

        $order = $this->model->getOrderById((int)$orderId, $userId);

        if ($order) {
            // Prévention de "PHP Object Injection"
            $cartData = !empty($order['cart_data']) ? unserialize($order['cart_data'], ['allowed_classes' => false]) : [];
            
            echo json_encode([
                'status'   => 'success',
                'order'    => $order,
                'products' => $cartData
            ]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Commande introuvable ou accès non autorisé.']);
        }
        exit; 
    }

    // Affiche la page des produits favoris
    public function favorites(): void
    {
        $userId = $this->requireAuthentication();
        
        $favorites = $this->model->getFavorites($userId);
        
        $data = [
            'favorites' => $favorites
        ];
        
        $this->view('account/favorites', $data);
    }

    // Ajoute ou retire un produit des favoris (Requête AJAX)
    public function toggleFavorite(string $productId): void
    {
        header('Content-Type: application/json');
        ob_clean();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['status' => 'error', 'message' => 'Méthode non autorisée.']);
            exit;
        }

        // SÉCURITÉ : Vérification AJAX CSRF
        $token = $_POST['csrf_token'] ?? '';
        if ($token !== Model::sessionGet('csrf_token')) {
            echo json_encode(['status' => 'error', 'message' => 'Jeton de sécurité invalide.']);
            exit;
        }

        // Vérification de l'authentification directement pour AJAX
        $userId = (int) Model::sessionGet('userId');
        
       
    }
}
?>