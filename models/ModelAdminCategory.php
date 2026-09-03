<?php

class ModelAdminCategory extends Model
{
    public $allChildrenIds = [];

    public function __construct()
    {
        parent::__construct();
    }

    public function getCategory()
    {
        $sql = "SELECT * FROM categories";
        return $this->doSelect($sql);
    }

    public function getChildren($categoryId)
    {
        $sql = "SELECT * FROM categories WHERE parent_id = ?";
        return $this->doSelect($sql, [(int)$categoryId]);
    }

    public function getParents($categoryId)
    {
        $categoryInfo = $this->categoryInfo((int)$categoryId);
        if (!$categoryInfo) return [];

        $parentId = $categoryInfo['parent_id'];
        $allParents = [];

        while ($parentId != 0) {
            $sql = "SELECT * FROM categories WHERE id = ?";
            $parentCategory = $this->doSelect($sql, [(int)$parentId], true);
            if ($parentCategory) {
                $allParents[] = $parentCategory;
                $parentId = $parentCategory['parent_id'];
            } else {
                break;
            }
        }

        return $allParents;
    }

    public function categoryInfo($categoryId)
    {
        $sql = "SELECT * FROM categories WHERE id = ?";
        return $this->doSelect($sql, [(int)$categoryId], true);
    }

    // Récupère les catégories principales (parent_id = 0)
    public function addCategory(string $title, int $parentId, int $editId)
    {
        if ($editId > 0) {
            $sql = "UPDATE categories SET title = ?, parent_id = ? WHERE id = ?";
            $this->doQuery($sql, [$title, $parentId, $editId]);
        } else {
            $sql = "INSERT INTO categories (title, parent_id) VALUES (?, ?)";
            $this->doQuery($sql, [$title, $parentId]);
        }
    }

    public function getChildrenIds($categoryId)
    {
        $sql = "SELECT id FROM categories WHERE parent_id = ?";
        $children = $this->doSelect($sql, [(int)$categoryId]);
        
        foreach ($children as $child) {
            $this->allChildrenIds[] = $child['id'];
            $this->getChildrenIds($child['id']); // Récursivité 
        }
    }

    public function deleteCategory($ids)
    {
        // Réinitialisation du tableau pour éviter les effets de bord lors d'appels multiples
        $this->allChildrenIds = []; 
        
        $idsToVerify = array_map('intval', $ids);
        
        foreach ($idsToVerify as $id) {
            $this->allChildrenIds[] = $id;
            $this->getChildrenIds($id);
        }
        
        $this->allChildrenIds = array_unique($this->allChildrenIds);
        
        if (!empty($this->allChildrenIds)) {
            $idsString = implode(',', $this->allChildrenIds);
            
            // Suppression des catégories
            $sql = "DELETE FROM categories WHERE id IN ($idsString)";
            $this->doQuery($sql);
            
            // Nettoyage des attributs liés
            $sqlAttr = "DELETE FROM attributes WHERE category_id IN ($idsString)";
            $this->doQuery($sqlAttr);
        }
    }

    public function attrInfo($attrId)
    {
        $sql = "SELECT * FROM attributes WHERE id = ?";
        return $this->doSelect($sql, [(int)$attrId], true);
    }

    public function getAttr($categoryId, $parentId)
    {
        $sql = "SELECT * FROM attributes WHERE category_id = ? AND parent_id = ? ORDER BY id DESC";
        return $this->doSelect($sql, [(int)$categoryId, (int)$parentId]);
    }

    // Récupère les attributs principaux (parent_id = 0) pour une catégorie donnée
    public function addAttribute(string $title, int $categoryId, int $parentId, int $editId)
    {
        if ($editId > 0) {
            $sql = "UPDATE attributes SET title = ?, parent_id = ? WHERE id = ?";
            $this->doQuery($sql, [$title, $parentId, $editId]);
        } else {
            $sql = "INSERT INTO attributes (title, category_id, parent_id) VALUES (?, ?, ?)";
            $this->doQuery($sql, [$title, $categoryId, $parentId]);
        }
    }

    public function deleteAttr($ids)
    {
        $idsString = implode(',', array_map('intval', $ids));
        
        if (!empty($idsString)) {
            $sql = "DELETE FROM attributes WHERE id IN ($idsString) OR parent_id IN ($idsString)";
            $this->doQuery($sql);
            
            // Nettoyage des valeurs orphelines
            $sqlVal = "DELETE FROM attribute_values WHERE attribute_id IN ($idsString)";
            $this->doQuery($sqlVal);
        }
    }

    public function getAttrVal($attrId)
    {
        $sql = "SELECT * FROM attribute_values WHERE attribute_id = ?";
        return $this->doSelect($sql, [(int)$attrId]);
    }

    // Récupère les valeurs d'attribut pour un attribut donné
    public function saveAttrVal(array $newValues, array $existingValues, int $attrId)
    {
        // Insérer les nouvelles valeurs
        foreach ($newValues as $val) {
            $sql = "INSERT INTO attribute_values (attribute_id, value) VALUES (?, ?)";
            $this->doQuery($sql, [$attrId, $val]);
        }
        
        // Mettre à jour ou supprimer les valeurs existantes
        foreach ($existingValues as $valId => $val) {
            if ($val !== '') {
                $sql = "UPDATE attribute_values SET value = ? WHERE id = ?";
                $this->doQuery($sql, [$val, $valId]);
            } else {
                $sqlDelete = "DELETE FROM attribute_values WHERE id = ?";
                $this->doQuery($sqlDelete, [$valId]);
            }
        }
    }
}
?>