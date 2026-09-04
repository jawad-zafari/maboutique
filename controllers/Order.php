<?php


class Order extends Controller 
{
    public function __construct() 
    {
        parent::__construct();
        // Initialisation de la session et vérification des droits
        Model::sessionInit(); 
    }

    // Vérifie si l'utilisateur est connecté de manière sécurisée
    private function checkLogin(): void
    {
        $userId = Model::sessionGet('userId');
        if ($userId === false) {
            header('Location: ' . URL . 'Login/index?back=Order/address');
            exit;
        }
    }

    // Vérifie si le panier n'est pas vide avant de continuer
    private function checkCartNotEmpty(): void
    {
        $cartData = $this->processCartData();
        $items = $cartData[0] ?? [];
        
        if (empty($items)) {
            header('Location: ' . URL . 'Cart/index?error=empty_cart');
            exit;
        }
    }

    // Traite et structure les données du panier pour les vues
    private function processCartData(): array 
    {
        $rawCartData = $this->model->getCartData() ?? [];
        
        $cart = [];
        $totalPrice = 0;
        $totalDiscount = 0;

        if (isset($rawCartData[0]) && is_array($rawCartData[0]) && isset($rawCartData[1]) && is_numeric($rawCartData[1])) {
            $cart = $rawCartData[0];
            $totalPrice = (float)$rawCartData[1];
            $totalDiscount = (float)($rawCartData[2] ?? 0);
        } else {
            $cart = is_array($rawCartData) ? $rawCartData : [];
        }

        if ($totalPrice <= 0 && !empty($cart)) {
            foreach ($cart as $item) {
                $qty = (int)($item['quantity'] ?? 1);
                $price = (float)($item['price'] ?? 0);
                $totalPrice += ($price * $qty);
            }
        }

        return [$cart, $totalPrice, $totalDiscount];
    }

    public function index(): void 
    {
        $userId = Model::sessionGet('userId');
        
        if ($userId !== false) {
            header('Location: ' . URL . 'Order/address');
            exit;
        } else {
            header('Location: ' . URL . 'Login/index?back=Order/address');
            exit;
        }
    }

    // Étape 2 : Choix de l'adresse et du mode de livraison
    public function address(): void 
    {
        $this->checkLogin(); 
        $this->checkCartNotEmpty(); 

        // Le contrôleur extrait l'ID utilisateur de la session
        $userId = (int)Model::sessionGet('userId');

        // Passage de l'ID utilisateur explicitement au modèle
        $addresses = $this->model->getAddresses($userId);
        $shippingTypes = $this->model->getShippingTypes();
        
        $data = [
            'cartData'   => $this->processCartData(),
            'addresses'  => $addresses, 
            'postType'   => $shippingTypes,
            'csrf_token' => $this->generateCsrfToken()
        ];
        
        $this->view('order/step2_address', $data);
    }

    // Ajout d'adresse via AJAX (avec en-tête JSON strict)
    public function addAddressAjax(): void
    {
        $this->checkLogin();

        // Définition de l'en-tête JSON pour la réponse
        header('Content-Type: application/json; charset=utf-8');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('HTTP/1.1 405 Method Not Allowed');
            echo json_encode(['status' => 'error', 'message' => 'Méthode non autorisée.']);
            exit;
        }

        $this->checkCsrfToken($_POST['csrf_token'] ?? '');

        // (Anti Mass-Assignment) : Nettoyage strict
        $cleanData = [
            'last_name'     => trim(strip_tags($_POST['last_name'] ?? '')),
            'mobile'        => trim(strip_tags($_POST['mobile'] ?? '')),
            'province_name' => trim(strip_tags($_POST['province_name'] ?? '')),
            'city_name'     => trim(strip_tags($_POST['city_name'] ?? '')),
            'postal_code'   => trim(strip_tags($_POST['postal_code'] ?? '')),
            'address'       => trim(strip_tags($_POST['address'] ?? ''))
        ];

        if (empty($cleanData['last_name']) || empty($cleanData['mobile']) || empty($cleanData['city_name']) || empty($cleanData['postal_code']) || empty($cleanData['address'])) {
            echo json_encode(['status' => 'error', 'message' => 'Veuillez remplir tous les champs obligatoires.']);
            exit;
        }

        $userId = (int)Model::sessionGet('userId');
        
        $addressId = $this->model->addAddress($cleanData, $userId);

        if ($addressId > 0) {
            $newAddress = $this->model->getAddressById($addressId, $userId);
            echo json_encode([
                'status'  => 'success',
                'message' => 'Adresse enregistrée avec succès !',
                'address' => $newAddress
            ]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Erreur lors de l\'enregistrement de l\'adresse.']);
        }
        exit;
    }

    
}
?>