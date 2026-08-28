<?php

class ModelRegister extends Model 
{
    public function __construct() 
    {
        parent::__construct();
    }
    
    // Insère un nouvel utilisateur dans la base de données
    public function insertUser(array $cleanData): bool 
    {
        // Étape 1 : Vérification de l'existence de l'e-mail pour éviter les doublons
        $sqlCheck = "SELECT id FROM users WHERE email = ?";
        $result = $this->doSelect($sqlCheck, [$cleanData['email']], 'fetch', PDO::FETCH_ASSOC);

        if (!empty($result)) {
            // Un compte avec cet e-mail existe déjà
            return false; 
        }

        // Étape 2 : Insertion des données avec requête préparée (PDO) pour contrer les injections SQL
        $sqlInsert = "INSERT INTO users (email, username, password, last_name, national_id, phone, mobile, birth_date, address, city, postal_code, gender, newsletter, role_id, created_at) 
                      VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        // Configuration des valeurs par défaut pour les champs vides
        $values = [
            $cleanData['email'], 
            '', // username
            $cleanData['password'], 
            $cleanData['last_name'], 
            '', // national_id
            '', // phone
            $cleanData['mobile'], 
            '', // birth_date
            '', // address
            '', // city
            '', // postal_code
            1,  // gender (par défaut)
            $cleanData['newsletter'], 
            $cleanData['role_id'], 
            $cleanData['created_at']
        ];

        // Exécution de la requête d'insertion
        $this->doQuery($sqlInsert, $values);
        
        return true;
    }
}
?>