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

   
}
?>