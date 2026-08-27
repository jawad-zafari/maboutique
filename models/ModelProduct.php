<?php

class ModelProduct extends Model
{
    public function __construct()
    {
        parent::__construct();
    }


    public function findProductImage($id, $size = 350) 
    {
        $basePath = 'public/images/products/' . (int)$id . '/product_' . (int)$size;
        $extensions = ['jpg', 'webp', 'png', 'jpeg'];
        
        foreach ($extensions as $ext) {
            if (file_exists($basePath . '.' . $ext)) {
                return URL . $basePath . '.' . $ext . '?v=' . time();
            }
        }
        return 'https://placehold.co/' . (int)$size . 'x' . (int)$size . '/f8f9fa/adb5bd?text=Image';
    }

   
    
}
?>