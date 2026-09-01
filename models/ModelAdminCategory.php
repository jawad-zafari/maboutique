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

    
}
?>