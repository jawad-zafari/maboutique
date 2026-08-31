<?php


class Order extends Controller 
{
    public function __construct() 
    {
        parent::__construct();
        Model::sessionInit(); 
    }

    // Vérifie si l'utilisateur est connecté
    private function checkLogin(): void
    {
        $userId = Model::sessionGet('userId');
        if ($userId === false) {
            header('Location: ' . URL . 'Login/index?back=Order/address');
            exit;
        }
    }

    // Vérifie si le panier n'est pas vide
    private function checkCartNotEmpty(): void
    {
        $cartData = $this->processCartData();
        $items = $cartData[0] ?? [];
        
        if (empty($items)) {
            header('Location: ' . URL . 'Cart/index?error=empty_cart');
            exit;
        }
    }

    // Traite et sécurise les données du panier
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
        
        if ($userId != false) {
            header('Location: ' . URL . 'Order/address');
            exit;
        } else {
            header('Location: ' . URL . 'Login/index?back=Order/address');
            exit;
        }
    }

    public function address(): void 
    {
        $this->checkLogin(); 
        $this->checkCartNotEmpty(); 

        $addresses = $this->model->getAddresses();
        $shippingTypes = $this->model->getShippingTypes();
        
        $data = [
            'cartData'   => $this->processCartData(),
            'addresses'  => $addresses, 
            'postType'   => $shippingTypes,
            'csrf_token' => $this->generateCsrfToken()
        ];
        
        $this->view('order/step2_address', $data);
    }

    // Ajout d'adresse via AJAX avec nettoyage des entrées (Input Sanitization)
    public function addAddressAjax(): void
    {
        $this->checkLogin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('HTTP/1.1 405 Method Not Allowed');
            echo json_encode(['status' => 'error', 'message' => 'Méthode non autorisée.']);
            exit;
        }

        $this->checkCsrfToken($_POST['csrf_token'] ?? '');

        // SÉCURITÉ (Anti Mass-Assignment) : Extraction et nettoyage stricts
        $cleanData = [
            'last_name'     => trim(strip_tags($_POST['last_name'] ?? '')),
            'mobile'        => trim(strip_tags($_POST['mobile'] ?? '')),
            'province_name' => trim(strip_tags($_POST['province_name'] ?? '')),
            'city_name'     => trim(strip_tags($_POST['city_name'] ?? '')),
            'postal_code'   => trim(strip_tags($_POST['postal_code'] ?? '')),
            'address'       => trim(strip_tags($_POST['address'] ?? ''))
        ];

        // Validation des champs obligatoires
        if (empty($cleanData['last_name']) || empty($cleanData['mobile']) || empty($cleanData['city_name']) || empty($cleanData['postal_code']) || empty($cleanData['address'])) {
            echo json_encode(['status' => 'error', 'message' => 'Veuillez remplir tous les champs obligatoires.']);
            exit;
        }

        // Appel sécurisé au modèle
        $addressId = $this->model->addAddress($cleanData);
        $userId = (int)Model::sessionGet('userId');

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

    public function summary(): void 
    {
        header('Location: ' . URL . 'Order/payment');
        exit;
    }

    public function payment(): void 
    {
        $this->checkLogin();
        $this->checkCartNotEmpty(); 

        $addressId = Model::sessionGet('selected_address_id');
        $shippingTypeId = Model::sessionGet('selected_shipping_type_id');
        $userId = (int)Model::sessionGet('userId');

        if (!$addressId || !$shippingTypeId) {
            header('Location: ' . URL . 'Order/address?error=address_missing');
            exit;
        }

        $addressInfo = $this->model->getAddressById((int)$addressId, $userId);
        if (!$addressInfo) {
            header('Location: ' . URL . 'Order/address?error=unauthorized_address');
            exit;
        }

        $shippingPrice = $this->model->getShippingPrice((int)$shippingTypeId);
        $status = $this->model->getPaymentStatus();

        $data = [
            'status'      => $status,
            'cartData'    => $this->processCartData(),
            'addressInfo' => $addressInfo,
            'postPrice'   => $shippingPrice,
            'postType'    => $shippingTypeId,
            'csrf_token'  => $this->generateCsrfToken()
        ];
        
        $this->view('order/step4_payment', $data);
    }

    public function saveAddressSession(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('HTTP/1.1 405 Method Not Allowed');
            echo json_encode(['status' => 'error', 'message' => 'Méthode non autorisée.']);
            exit;
        }

        $this->checkCsrfToken($_POST['csrf_token'] ?? '');

        // SÉCURITÉ : Typage strict
        $addressId = (int)($_POST['addressId'] ?? 0);
        $shippingId = (int)($_POST['shippingId'] ?? 0);

        if ($addressId > 0 && $shippingId > 0) {
            Model::sessionSet('selected_address_id', $addressId);
            Model::sessionSet('selected_shipping_type_id', $shippingId);
            echo json_encode(['status' => 'success']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Données invalides.']);
        }
        exit;
    }

    public function checkPromoCode(): void 
    {
        $this->checkLogin();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['status' => 'error', 'message' => 'Méthode non autorisée.']);
            exit;
        }

        $this->checkCsrfToken($_POST['csrf_token'] ?? '');
        
        // Nettoyage du code promo
        $safeCode = trim(strip_tags($_POST['code'] ?? ''));
        
        $result = $this->model->verifyPromoCode($safeCode);
        $totalPrice = $this->model->calculateTotalPrice($safeCode);

        echo json_encode([$result, $totalPrice]);
        exit;
    }

   
}
?>