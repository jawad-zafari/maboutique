<?php

class ModelOrder extends Model 
{
    public function __construct() 
    {
        parent::__construct();
    }

    public function getAddresses(): array 
    {
        $sql = "SELECT * FROM user_addresses WHERE user_id = ?";
        Model::sessionInit();
        $userId = (int)Model::sessionGet('userId');
        return $this->doSelect($sql, [$userId]);
    }

    public function getAddressById(int $addressId, int $userId): array
    {
        $sql = "SELECT * FROM user_addresses WHERE id = ? AND user_id = ?";
        $result = $this->doSelect($sql, [$addressId, $userId], 'fetch', PDO::FETCH_ASSOC);
        return $result ?: [];
    }

    // Le contrôleur a déjà nettoyé $cleanData via strip_tags et trim
    public function addAddress(array $cleanData): int
    {
        Model::sessionInit();
        $userId = (int)Model::sessionGet('userId');

        $lastName     = $cleanData['last_name'] ?? '';
        $mobile       = $cleanData['mobile'] ?? '';
        $provinceName = $cleanData['province_name'] ?? '';
        $cityName     = $cleanData['city_name'] ?? '';
        $postalCode   = $cleanData['postal_code'] ?? '';
        $address      = $cleanData['address'] ?? '';

        $sql = "INSERT INTO user_addresses (user_id, last_name, mobile, province_name, city_name, postal_code, address, phone, province_id, city_id, neighborhood) 
                VALUES (?, ?, ?, ?, ?, ?, ?, '', '', '', '')";
        
        $params = [$userId, $lastName, $mobile, $provinceName, $cityName, $postalCode, $address];
        
        $this->doQuery($sql, $params);
        return (int)self::$conn->lastInsertId();
    }

    public function getShippingTypes(): array 
    {
        $sql = "SELECT * FROM shipping_methods";
        return $this->doSelect($sql);
    }

    public function getShippingPrice(int $shippingId): float
    {
        $sql = "SELECT price FROM shipping_methods WHERE id = ?";
        $result = $this->doSelect($sql, [$shippingId], 'fetch', PDO::FETCH_ASSOC);
        return isset($result['price']) ? (float)$result['price'] : 0.0;
    }

    public function getCartData(): array 
    {
        return parent::getCart();
    }

    public function getPaymentStatus(): array 
    {
        $sql = "SELECT * FROM settings WHERE setting_key = 'payment_status'";
        $result = $this->doSelect($sql, [], 'fetch', PDO::FETCH_ASSOC);
        return $result ?: [];
    }

    public function verifyPromoCode(string $code)
    {
        $sql = "SELECT * FROM discount_codes WHERE code = ? AND is_used = 0 AND expires_at > ?";
        $currentDate = date('Y-m-d');
        return $this->doSelect($sql, [$code, $currentDate], 'fetch', PDO::FETCH_ASSOC);
    }

    public function calculateTotalPrice(string $code = ''): float
    {
        $cartData = $this->getCartData();
        $totalPrice = (float)($cartData[1] ?? 0);
        $discountTotal = (float)($cartData[2] ?? 0);

        if (!empty($code)) {
            $promo = $this->verifyPromoCode($code);
            if ($promo && isset($promo['discount_percent'])) {
                $promoDiscount = ($totalPrice * (float)$promo['discount_percent']) / 100;
                $discountTotal += $promoDiscount;
            }
        }

        return max(0, $totalPrice - $discountTotal);
    }

    
    //  Enregistrement sécurisé de la commande.
    
    public function saveOrder(array $cleanData): int
    {
        Model::sessionInit();
        $userId = (int)Model::sessionGet('userId');
        $addressId = (int)Model::sessionGet('selected_address_id');
        $shippingMethodId = (int)Model::sessionGet('selected_shipping_type_id');

        if (!$userId || !$addressId || !$shippingMethodId) {
            return 0;
        }

        $addressInfo = $this->getAddressById($addressId, $userId);
        if (!$addressInfo) {
            return 0;
        }

        $cartInfo = $this->getCartData();
        $cartItems = $cartInfo[0] ?? [];
        if (empty($cartItems)) {
            return 0;
        }

        $totalProductsPrice = (float)($cartInfo[1] ?? 0);
        $totalDiscount = (float)($cartInfo[2] ?? 0);
        $shippingPrice = $this->getShippingPrice($shippingMethodId);

        $codePromo = $cleanData['code_promo'] ?? '';
        if (!empty($codePromo)) {
            $promo = $this->verifyPromoCode($codePromo);
            if ($promo && isset($promo['discount_percent'])) {
                $promoDiscount = ($totalProductsPrice * (float)$promo['discount_percent']) / 100;
                $totalDiscount += $promoDiscount;
            }
        }

        $totalAmount = max(0, $totalProductsPrice + $shippingPrice - $totalDiscount);

        // RÉCUPÉRATION DU MODE DE PAIEMENT SÉLECTIONNÉ
        $paymentMethodId = (int)($cleanData['payment_method'] ?? 1);
        $maskedCard = '';
        $payBankName = '';

        if ($paymentMethodId === 1) {
            $payBankName = 'Carte Bancaire';
            // SÉCURITÉ PCI-DSS : Masquage de la carte
            $rawCardNumber = preg_replace('/\D/', '', $cleanData['card_number'] ?? '');
            if (!empty($rawCardNumber)) {
                $last4Digits = substr($rawCardNumber, -4);
                $maskedCard = '**** **** **** ' . $last4Digits;
            }
        } else if ($paymentMethodId === 2) {
            $payBankName = 'Virement Bancaire';
            $maskedCard = 'N/A';
        }

       
    }
}
?>