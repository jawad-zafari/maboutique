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
        
        // SÉCURITÉ ANTI-XSS : Échappement des caractères spéciaux avant l'insertion en base de données
        $lastName = htmlspecialchars(trim($data['last_name'] ?? ''), ENT_QUOTES, 'UTF-8');
        $mobile = htmlspecialchars(trim($data['mobile'] ?? ''), ENT_QUOTES, 'UTF-8');
        $newsletter = isset($data['newsletter']) ? 1 : 0;
        
       
    }
}
?>