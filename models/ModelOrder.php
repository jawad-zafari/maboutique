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

   
}
?>