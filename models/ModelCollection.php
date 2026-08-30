<?php

class ModelCollection extends Model
{
    public function __construct()
    {
        parent::__construct();
    }

    
    public function getCollectionProducts(string $type, int $limit, int $offset, int $categoryId, array $filters): array
    {
        $products = [];
        $total = 0;
        $categoryTitle = '';

        $whereClauses = [];
        $params = [];

        // Condition selon le type de collection
        if ($type === 'category') {
            // Requêtes préparées pour éviter les injections SQL
            $whereClauses[] = "(category_id = ? OR secondary_category_id = ?)";
            $params[] = $categoryId;
            $params[] = $categoryId;

            $sqlCat = "SELECT title FROM categories WHERE id = ?";
            $catRes = $this->doSelect($sqlCat, [$categoryId], 'fetch');
            if (!empty($catRes)) { 
                $categoryTitle = $catRes['title']; 
            }
            
        } elseif ($type === 'special') {
            $whereClauses[] = "(discount_percent > 0 OR is_special_offer = 1)";
        } elseif ($type === 'exclusive') {
            $whereClauses[] = "is_exclusive = 1";
        }

        // Application du filtre de stock
        if (isset($filters['in_stock']) && $filters['in_stock'] === 1) {
            $whereClauses[] = "stock_quantity > 0";
        }

        $whereSql = "";
        if (count($whereClauses) > 0) {
            $whereSql = "WHERE " . implode(" AND ", $whereClauses);
        }

        // Récupération sécurisée du tri (déjà validé par le contrôleur)
        $orderCol = $filters['order_col'] ?? 'id';
        $orderDir = $filters['order_dir'] ?? 'DESC';

        // Requête pour compter le total des résultats
        $sqlCount = "SELECT COUNT(id) as total FROM products $whereSql";
        $resultCount = $this->doSelect($sqlCount, $params, 'fetch');
        
        if (!empty($resultCount)) { 
            $total = (int)$resultCount['total']; 
        }

        
    }
}
?>