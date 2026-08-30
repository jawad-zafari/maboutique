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

   
}
?>