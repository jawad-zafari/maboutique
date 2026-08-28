<?php

class ModelRegister extends Model 
{
    public function __construct() 
    {
        parent::__construct();
    }
    

    public function insertUser(array $data): bool 
    {
        // Nettoyage de l'e-mail
        $email = filter_var($data['email'] ?? '', FILTER_SANITIZE_EMAIL);
        $password = $data['password'] ?? '';
        
       
    }
}
?>