<?php

class ModelSearch extends Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getAttr(int $categoryId): array
    {
        $sql = "SELECT * FROM attributes WHERE category_id = ? AND is_filter = 1";
        $result = $this->doSelect($sql, [$categoryId]);
        
        if (is_array($result)) {
            foreach ($result as $key => $row) {
                $sqlValues = "SELECT * FROM attribute_values WHERE attribute_id = ?";
                $result[$key]['values'] = $this->doSelect($sqlValues, [(int)$row['id']]);
            }
        }
        return is_array($result) ? $result : [];
    }

    public function getAttrRight(int $categoryId): array
    {
        $sql = "SELECT * FROM attributes WHERE category_id = ? AND is_right_filter = 1";
        $result = $this->doSelect($sql, [$categoryId]);

        if (is_array($result)) {
            foreach ($result as $key => $row) {
                $sqlValues = "SELECT * FROM attribute_values WHERE attribute_id = ?";
                $result[$key]['values'] = $this->doSelect($sqlValues, [(int)$row['id']]);
            }
        }
        return is_array($result) ? $result : [];
    }

    public function getColors(): array
    {
        $sql = "SELECT * FROM colors";
        $result = $this->doSelect($sql);
        return is_array($result) ? $result : [];
    }

    // variables propres et fortement typées
    public function doSearch(string $keyword, int $categoryId, int $inStock, int $orderType1, int $orderType2, int $limit, int $offset): array
    {
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

        // Calcul du nombre total de résultats pour la pagination
        $sqlCount = "SELECT COUNT(id) as total FROM products WHERE $whereSql";
        $resultCount = $this->doSelect($sqlCount, $params, 'fetch');
        $totalProducts = (int)($resultCount['total'] ?? 0);

        // Définition des colonnes de tri (Whitelisting pour la sécurité)
        $orderBy = "id";
        if ($orderType1 === 1) { $orderBy = "price"; }
        if ($orderType1 === 2) { $orderBy = "views"; }
        
        $orderDir = ($orderType2 === 2) ? "DESC" : "ASC";
        
        // Requête principale
        $sqlData = "SELECT * FROM products WHERE $whereSql ORDER BY $orderBy $orderDir LIMIT $limit OFFSET $offset";
        $productsRaw = $this->doSelect($sqlData, $params);
        
        $pageNumber = ($totalProducts > 0) ? (int) ceil($totalProducts / $limit) : 1;

        // Retourne un tableau contenant les produits et le nombre de pages pour la pagination
        return [is_array($productsRaw) ? $productsRaw : [], $pageNumber];
    }

    public function suggestProducts(string $keyword): array
    {
        if (mb_strlen($keyword, 'UTF-8') < 2) {
            return [];
        }
        
        $sql = "SELECT * FROM products WHERE title LIKE ? LIMIT 5";
        $results = $this->doSelect($sql, ['%' . $keyword . '%']);
        
        return is_array($results) ? $results : [];
    }
}
?>