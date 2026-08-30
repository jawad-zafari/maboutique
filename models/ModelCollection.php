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
            
        } 
}
?>