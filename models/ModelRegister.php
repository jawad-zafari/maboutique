<?php

class ModelRegister extends Model 
{
    public function __construct() 
    {
        parent::__construct();
    }
    
    // variables propres et fortement typées
    public function insertUser(string $email, string $password, string $lastName, string $mobile, int $newsletter, int $roleId, string $createdAt): bool 
    {
        // Étape 1 : Vérification de l'existence de l'e-mail pour éviter les doublons
        $sqlCheck = "SELECT id FROM users WHERE email = ?";
        $result = $this->doSelect($sqlCheck, [$email], 'fetch', PDO::FETCH_ASSOC);

        if (!empty($result)) {
            // Un compte avec cet e-mail existe déjà
            return false; 
        }

        // Étape 2 : Insertion des données avec requête préparée (PDO) pour contrer les injections SQL
        $sqlInsert = "INSERT INTO users (email, username, password, last_name, national_id, phone, mobile, birth_date, address, city, postal_code, gender, newsletter, role_id, created_at) 
                      VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        // Configuration des valeurs par défaut pour les champs vides
        $values = [
            $email, 
            '', // username vide par défaut
            $password, 
            $lastName, 
            '', // national_id
            '', // phone
            $mobile, 
            '', // birth_date
            '', // address
            '', // city
            '', // postal_code
            1,  // gender (par défaut)
            $newsletter, 
            $roleId, 
            $createdAt
        ];

        // Exécution de la requête d'insertion
        $this->doQuery($sqlInsert, $values);
        
        return true;
    }
}
?>