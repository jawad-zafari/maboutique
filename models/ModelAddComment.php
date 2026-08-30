<?php
class ModelAddComment extends Model
{
    public function __construct()
    {
        parent::__construct();
    }

    // Récupère les informations du produit
    public function productInfo(int $productId): array
    {
        $sql = "SELECT * FROM products WHERE id = ?";
        $result = $this->doSelect($sql, [$productId], 'fetch');
        
        return $result ?: [];
    }

    // Récupère les paramètres d'évaluation de la catégorie du produit
    public function getParam(int $productId): array
    {
        $productInfo = $this->productInfo($productId);
        $categoryId = (int) ($productInfo['category_id'] ?? 0);
        
        $sql = "SELECT * FROM review_parameters WHERE category_id = ?";
        return $this->doSelect($sql, [$categoryId]);
    }

    // Enregistre ou met à jour le commentaire
    public function saveComment(array $cleanData, int $productId, int $userId): void
    {
        if ($userId <= 0) return;

        // Extraction des données déjà nettoyées par le contrôleur
        $title      = $cleanData['title'] ?? '';
        $positive   = $cleanData['positive'] ?? '';
        $negative   = $cleanData['negative'] ?? '';
        $comment    = $cleanData['comment'] ?? '';
        $parameters = serialize($cleanData['parameters'] ?? []);

        date_default_timezone_set('Europe/Paris');
        $createdAt = date('Y-m-d H:i:s');

        // Vérification de l'existence d'un commentaire précédent
        $sqlCheck = "SELECT id FROM comments WHERE user_id = ? AND product_id = ?";
        $result = $this->doSelect($sqlCheck, [$userId, $productId], 'fetch');

        if (!empty($result)) {
            // Mise à jour (remise en attente d'approbation)
            $commentId = (int) $result['id'];
            $sqlUpdate = "UPDATE comments SET title = ?, content = ?, positive_points = ?, negative_points = ?, parameters = ?, is_approved = 0, created_at = ? WHERE id = ?";
            
            $this->doQuery($sqlUpdate, [
                $title, 
                $comment, 
                $positive, 
                $negative, 
                $parameters, 
                $createdAt, 
                $commentId
            ]);
        } else {
            // Insertion
            $sqlInsert = "INSERT INTO comments (title, content, created_at, positive_points, negative_points, product_id, parameters, user_id, is_approved) VALUES (?, ?, ?, ?, ?, ?, ?, ?, 0)";
            
            $this->doQuery($sqlInsert, [
                $title, 
                $comment, 
                $createdAt, 
                $positive, 
                $negative, 
                $productId, 
                $parameters, 
                $userId
            ]);
        }
    }

    // Récupère les infos d'un commentaire existant
    public function commentInfo(int $productId, int $userId): array
    {
        $sql = "SELECT * FROM comments WHERE product_id = ? AND user_id = ?";
        $result = $this->doSelect($sql, [$productId, $userId], 'fetch');
        
        return $result ?: [];
    }
}
?>