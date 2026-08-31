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

    
}
?>