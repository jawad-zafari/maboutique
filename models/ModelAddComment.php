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

   
}
?>