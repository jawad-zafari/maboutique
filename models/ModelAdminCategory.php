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

    public function addCategory($data, $parentId, $editId)
    {
        // Les données sont stockées brutes. PDO empêche l'injection SQL.
        $title = trim($data['title'] ?? '');
        $parent = (int)($data['parent'] ?? $parentId);

        if (empty($title)) return;

        if ($editId > 0) {
            $sql = "UPDATE categories SET title = ?, parent_id = ? WHERE id = ?";
            $this->doQuery($sql, [$title, $parent, (int)$editId]);
        } else {
            $sql = "INSERT INTO categories (title, parent_id) VALUES (?, ?)";
            $this->doQuery($sql, [$title, $parent]);
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

    public function addAttribute($data, $categoryId, $editId)
    {
        // Les données sont stockées brutes. PDO protège.
        $title = trim($data['title'] ?? '');
        $parentId = (int)($data['parent'] ?? 0);
        
        if (empty($title)) return;

        if ($editId > 0) {
            $sql = "UPDATE attributes SET title = ?, parent_id = ? WHERE id = ?";
            $this->doQuery($sql, [$title, $parentId, (int)$editId]);
        } else {
            $sql = "INSERT INTO attributes (title, category_id, parent_id) VALUES (?, ?, ?)";
            $this->doQuery($sql, [$title, (int)$categoryId, $parentId]);
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

    public function saveAttrVal($data, $attrId)
    {
        $safeAttrId = (int)$attrId;

        // Insérer les nouvelles valeurs brutes
        $attrValNew = array_filter($data['attrvalnew'] ?? []);
        foreach ($attrValNew as $val) {
            $rawVal = trim($val);
            if (!empty($rawVal)) {
                $sql = "INSERT INTO attribute_values (attribute_id, value) VALUES (?, ?)";
                $this->doQuery($sql, [$safeAttrId, $rawVal]);
            }
        }
        
       
    }
}
?>