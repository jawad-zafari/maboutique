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

    }
}
?>