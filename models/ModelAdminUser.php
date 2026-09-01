<?php

class ModelAdminUser extends Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getUsers(): array
    {
        $sql = "SELECT users.*, user_roles.title as levelTitle
                FROM users
                LEFT JOIN user_roles ON users.role_id = user_roles.id
                ORDER BY users.id DESC";
                
        $result = $this->doSelect($sql);
        return is_array($result) ? $result : [];
    }

    public function changeLevel1(array $ids): void
    {
        if (empty($ids)) return;
        
        // Génération dynamique des placeholders
        $safeIds = array_map('intval', $ids);
        $placeholders = rtrim(str_repeat('?,', count($safeIds)), ',');
        
        $sql = "UPDATE users SET role_id = 1 WHERE id IN ($placeholders)";
        $this->doQuery($sql, $safeIds);
    }
    
    public function changeLevel2(array $ids): void
    {
        if (empty($ids)) return;
        
        $safeIds = array_map('intval', $ids);
        $placeholders = rtrim(str_repeat('?,', count($safeIds)), ',');
        
        $sql = "UPDATE users SET role_id = 2 WHERE id IN ($placeholders)";
        $this->doQuery($sql, $safeIds);
    }

   
}
?>