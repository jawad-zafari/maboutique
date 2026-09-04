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

    // variables propres, pas de $_POST
    public function changeLevel1(array $safeIds): void
    {
        if (empty($safeIds)) return;
        
        // Génération dynamique des marqueurs pour la requête préparée
        $placeholders = rtrim(str_repeat('?,', count($safeIds)), ',');
        
        $sql = "UPDATE users SET role_id = 1 WHERE id IN ($placeholders)";
        $this->doQuery($sql, $safeIds);
    }
    
    public function changeLevel2(array $safeIds): void
    {
        if (empty($safeIds)) return;
        
        $placeholders = rtrim(str_repeat('?,', count($safeIds)), ',');
        
        $sql = "UPDATE users SET role_id = 2 WHERE id IN ($placeholders)";
        $this->doQuery($sql, $safeIds);
    }

    public function changeLevel3(array $safeIds): void
    {
        if (empty($safeIds)) return;
        
        $placeholders = rtrim(str_repeat('?,', count($safeIds)), ',');
        
        $sql = "UPDATE users SET role_id = 3 WHERE id IN ($placeholders)";
        $this->doQuery($sql, $safeIds);
    }

    public function delete(array $safeIds): void
    {
        if (empty($safeIds)) return;
        
        $placeholders = rtrim(str_repeat('?,', count($safeIds)), ',');
        
        $sql = "DELETE FROM users WHERE id IN ($placeholders)";
        $this->doQuery($sql, $safeIds);
    }
}
?>