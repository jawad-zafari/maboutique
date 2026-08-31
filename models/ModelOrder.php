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

    
}
?>