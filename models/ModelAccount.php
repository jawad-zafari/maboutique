<?php

class ModelAccount extends Model
{
    public function __construct()
    {
        parent::__construct();
    }

    // Récupère les informations d'un utilisateur
    public function getUserInfo(int $userId): array
    {
        $sql = "SELECT * FROM users WHERE id = ?";
        $result = $this->doSelect($sql, [$userId]);
        return $result[0] ?? [];
    }

    // Met à jour le profil avec les données nettoyées par le contrôleur
    public function updateProfile(array $cleanData, int $userId): void
    {
        $sql = "UPDATE users SET username = ?, email = ?, last_name = ?, mobile = ?, phone = ?, address = ?, city = ?, postal_code = ?, gender = ?, newsletter = ? WHERE id = ?";
        
        $this->doQuery($sql, [
            $cleanData['username'], 
            $cleanData['email'], 
            $cleanData['last_name'], 
            $cleanData['mobile'], 
            $cleanData['phone'], 
            $cleanData['address'], 
            $cleanData['city'], 
            $cleanData['postal_code'], 
            $cleanData['gender'], 
            $cleanData['newsletter'], 
            $userId
        ]);
    }

    // Récupère le mot de passe haché pour vérification dans le contrôleur
    public function getUserPasswordHash(int $userId): string
    {
        $sql = "SELECT password FROM users WHERE id = ?";
        $result = $this->doSelect($sql, [$userId]);
        
        return $result[0]['password'] ?? '';
    }

    // Sauvegarde le nouveau mot de passe haché
    public function updatePassword(int $userId, string $hashedPassword): void
    {
        $sql = "UPDATE users SET password = ? WHERE id = ?";
        $this->doQuery($sql, [$hashedPassword, $userId]);
    }

    // Supprime un utilisateur de la base de données
    public function deleteUser(int $userId): void
    {
        $sql = "DELETE FROM users WHERE id = ?";
        $this->doQuery($sql, [$userId]);
    }

    // Récupère l'historique complet des commandes
    public function getOrders(int $userId): array
    {
        $sql = "SELECT * FROM orders WHERE user_id = ? ORDER BY id DESC";
        return $this->doSelect($sql, [$userId]);
    }

    // Récupère les détails d'une commande spécifique (Vérification stricte de l'ID utilisateur)
    public function getOrderById(int $orderId, int $userId): array|false
    {
        $sql = "SELECT * FROM orders WHERE id = ? AND user_id = ?";
        $result = $this->doSelect($sql, [$orderId, $userId], 'fetch');
        
        return $result ?: false;
    }

    // Ajoute ou supprime un favori
    public function toggleFavorite(int $userId, int $productId): string
    {
        $sqlCheck = "SELECT id FROM favorites WHERE user_id = ? AND product_id = ?";
        $exists = $this->doSelect($sqlCheck, [$userId, $productId]);

        if (!empty($exists)) {
            $sqlDelete = "DELETE FROM favorites WHERE id = ?";
            $this->doQuery($sqlDelete, [$exists[0]['id']]);
            return 'removed';
        } else {
            $sqlInsert = "INSERT INTO favorites (user_id, product_id, folder_id, title) VALUES (?, ?, 0, '')";
            $this->doQuery($sqlInsert, [$userId, $productId]);
            return 'added';
        }
    }

    
}
?>