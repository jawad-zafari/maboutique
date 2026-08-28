<?php

class ModelLogin extends Model
{
    public function __construct()
    {
        parent::__construct();
    }

    
    //  Vérifie les identifiants de l'utilisateur
    
    public function checkUser(array $form): bool
    {
        // SÉCURITÉ : Nettoyage et validation de l'adresse e-mail
        $email = filter_var($form['email'] ?? '', FILTER_SANITIZE_EMAIL);
        $password = $form['password'] ?? '';

        if (empty($email) || empty($password)) {
            return false;
        }

        // Prévention de l'injection SQL grâce aux requêtes préparées PDO
        $sql = "SELECT id, password FROM users WHERE email = ?";
        $user = $this->doSelect($sql, [$email], 'fetch', PDO::FETCH_ASSOC);

        // Vérification du mot de passe haché 
        if ($user && password_verify($password, $user['password'])) {
            
           
            
            return true;
        }
        
        return false;
    }
}
?>