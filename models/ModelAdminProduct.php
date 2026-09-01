<?php

class ModelAdminProduct extends Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function findProductImage(int $id, int $size = 220): string 
    {
        $basePath = 'public/images/products/' . $id . '/product_' . $size;
        $extensions = ['jpg', 'webp', 'png', 'jpeg'];
        
        foreach ($extensions as $ext) {
            if (file_exists($basePath . '.' . $ext)) {
                return URL . $basePath . '.' . $ext . '?v=' . time();
            }
        }
        return '';
    }

    public function getProduct(): array
    {
        $sql = "SELECT * FROM products ORDER BY id DESC";
        $products = $this->doSelect($sql);
        
        if (is_array($products)) {
            foreach($products as $key => $p) {
                $products[$key]['thumb_url'] = $this->findProductImage((int)$p['id'], 220);
            }
        }
        return is_array($products) ? $products : [];
    }

    public function getCategory(): array { return $this->doSelect("SELECT * FROM categories") ?: []; }
    public function getColor(): array { return $this->doSelect("SELECT * FROM colors") ?: []; }
    public function getGarantee(): array { return $this->doSelect("SELECT * FROM guarantees") ?: []; }

    
}
?>