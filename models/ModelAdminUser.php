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

   
}
?>