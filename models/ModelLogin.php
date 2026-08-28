<?php

class ModelLogin extends Model
{
    public function __construct()
    {
        parent::__construct();
    }

    // Récupère les données de l'utilisateur basé sur son e-mail
    public function getUserByEmail(string $email): array|false
    {
        // SÉCURITÉ : Requête préparée (PDO) pour éviter les injections SQL
        $sql = "SELECT id, password FROM users WHERE email = ?";
        
        // Exécution de la requête
        $result = $this->doSelect($sql, [$email], 'fetch', PDO::FETCH_ASSOC);

        // Retourne le tableau de l'utilisateur ou false s'il n'existe pas
        return $result ?: false;
    }
}
?>