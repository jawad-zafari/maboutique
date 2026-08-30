<?php

class ModelAccount extends Model
{
    public function __construct()
    {
        parent::__construct();
    }

    // Récupère les informations d'un utilisateur
    public function getUserInfo(int $userId): array
    {
        $sql = "SELECT * FROM users WHERE id = ?";
        $result = $this->doSelect($sql, [$userId]);
        return $result[0] ?? [];
    }

   
}
?>