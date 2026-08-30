<?php

class ModelSearch extends Model
{
    public function __construct()
    {
        parent::__construct();
    }

    // Récupère les attributs de filtre pour la catégorie donnée
    public function getAttr(int $categoryId): array
    {
        $sql = "SELECT * FROM attributes WHERE category_id = ? AND is_filter = 1";
        $result = $this->doSelect($sql, [$categoryId]);
        
        foreach ($result as $key => $row) {
            $sqlValues = "SELECT * FROM attribute_values WHERE attribute_id = ?";
            $result[$key]['values'] = $this->doSelect($sqlValues, [(int)$row['id']]);
        }
        return $result;
    }

    // Récupère les attributs secondaires de filtre
    public function getAttrRight(int $categoryId): array
    {
        $sql = "SELECT * FROM attributes WHERE category_id = ? AND is_right_filter = 1";
        $result = $this->doSelect($sql, [$categoryId]);

        foreach ($result as $key => $row) {
            $sqlValues = "SELECT * FROM attribute_values WHERE attribute_id = ?";
            $result[$key]['values'] = $this->doSelect($sqlValues, [(int)$row['id']]);
        }
        return $result;
    }

    // Récupère la liste de toutes les couleurs disponibles
    public function getColors(): array
    {
        $sql = "SELECT * FROM colors";
        return $this->doSelect($sql);
    }

    // Moteur de recherche principal avec filtrage dynamique et pagination
    public function doSearch(array $cleanData): array
    {
        // Les données sont déjà assainies par le contrôleur (Architecture MVC stricte)
        $keyword     = $cleanData['keyword'] ?? '';
        $categoryId  = $cleanData['categoryId'] ?? 0;
        $inStock     = $cleanData['in_stock'] ?? 0;
        $orderType1  = $cleanData['orderType1'] ?? 3; 
        $orderType2  = $cleanData['orderType2'] ?? 2; 

        $currentPage = $cleanData['current_page'] ?? 1;
        if ($currentPage < 1) { $currentPage = 1; }

        $limit = $cleanData['limit'] ?? 20;
        if (!in_array($limit, [20, 40, 60])) { $limit = 20; }

        $offset = ($currentPage - 1) * $limit;

        // Construction dynamique des clauses WHERE
        $whereClauses = ["1=1"];
        $params = [];

        if (!empty($keyword)) {
            $whereClauses[] = "title LIKE ?";
            $params[] = '%' . $keyword . '%';
        }

        if ($categoryId > 0) {
            $whereClauses[] = "(category_id = ? OR secondary_category_id = ?)";
            $params[] = $categoryId;
            $params[] = $categoryId;
        }

        if ($inStock === 1) {
            $whereClauses[] = "stock_quantity > 0"; 
        }

        $whereSql = implode(" AND ", $whereClauses);

        // Calcul du nombre total de résultats
        $sqlCount = "SELECT COUNT(id) as total FROM products WHERE $whereSql";
        $resultCount = $this->doSelect($sqlCount, $params, 'fetch');
        $totalProducts = (int)($resultCount['total'] ?? 0);

        // Tri et récupération (Validation stricte pour éviter l'injection SQL)
        $orderBy = "id";
        if ($orderType1 === 1) { $orderBy = "price"; }
        if ($orderType1 === 2) { $orderBy = "views"; }
        
        $orderDir = ($orderType2 === 2) ? "DESC" : "ASC";
        
        $sqlData = "SELECT * FROM products WHERE $whereSql ORDER BY $orderBy $orderDir LIMIT $limit OFFSET $offset";
        $productsRaw = $this->doSelect($sqlData, $params);
        
        // Calcul des remises
        $products = $this->calculateProductsPrices($productsRaw);

        // Calcul du nombre de pages
        $pageNumber = ($totalProducts > 0) ? (int) ceil($totalProducts / $limit) : 1;

        return [$products, $pageNumber];
    }

    // Recherche instantanée (Auto-suggestion)
    public function suggestProducts(string $keyword): array
    {
        // Utilisation de mb_strlen pour supporter l'UTF-8
        if (mb_strlen($keyword, 'UTF-8') < 2) {
            return [];
        }
        
        $sql = "SELECT * FROM products WHERE title LIKE ? LIMIT 5";
        $results = $this->doSelect($sql, ['%' . $keyword . '%']);
        
        return $this->calculateProductsPrices($results);
    }

   
}
?>