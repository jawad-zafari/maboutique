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

    public function getProductInfo(int $id): array 
    {
        if (empty($id)) return [];

        $sql = "SELECT * FROM products WHERE id = ?";
        $result = $this->doSelect($sql, [$id], 'fetch');
        
        if ($result && is_array($result)) {
            $sqlColors = "SELECT c.* FROM product_colors pc JOIN colors c ON pc.color_id = c.id WHERE pc.product_id = ?";
            $result['colorsInfo'] = $this->doSelect($sqlColors, [$id]);
            
            $sqlGarantees = "SELECT g.* FROM product_guarantees pg JOIN guarantees g ON pg.guarantee_id = g.id WHERE pg.product_id = ?";
            $result['garanteesInfo'] = $this->doSelect($sqlGarantees, [$id]);
        }
        
        return is_array($result) ? $result : [];
    }

    // Le modèle ne reçoit plus de variable globale, uniquement des données structurées et propres
    public function addProductAction(int $productId, array $cleanData): int
    {
        if (empty($productId)) {
            $sql = "INSERT INTO products (title, category_id, price, discount_percent, description) VALUES (?, ?, ?, ?, ?)";
            $this->doQuery($sql, [
                $cleanData['title'], 
                $cleanData['categoryId'], 
                $cleanData['price'], 
                $cleanData['discount'], 
                $cleanData['description']
            ]);
            $productId = (int)self::$conn->lastInsertId();
        } else {
            $sql = "UPDATE products SET title = ?, category_id = ?, price = ?, discount_percent = ?, description = ? WHERE id = ?";
            $this->doQuery($sql, [
                $cleanData['title'], 
                $cleanData['categoryId'], 
                $cleanData['price'], 
                $cleanData['discount'], 
                $cleanData['description'], 
                $productId
            ]);
            
            $this->doQuery("DELETE FROM product_colors WHERE product_id = ?", [$productId]);
            $this->doQuery("DELETE FROM product_guarantees WHERE product_id = ?", [$productId]);
        }

        foreach ($cleanData['color'] as $colorId) {
            $this->doQuery("INSERT INTO product_colors (product_id, color_id) VALUES (?, ?)", [$productId, $colorId]);
        }
        
        foreach ($cleanData['garantee'] as $gId) {
            $this->doQuery("INSERT INTO product_guarantees (product_id, guarantee_id) VALUES (?, ?)", [$productId, $gId]);
        }

        return $productId;
    }

    public function deleteProduct(array $safeIds): void
    {
        if (empty($safeIds)) return;
        
        $placeholders = rtrim(str_repeat('?,', count($safeIds)), ',');
        $sql = "DELETE FROM products WHERE id IN ($placeholders)";
        $this->doQuery($sql, $safeIds);
    }

    public function getGallery(int $productId): array
    {
        $result = $this->doSelect("SELECT * FROM product_galleries WHERE product_id = ? ORDER BY id DESC", [$productId]);
        return is_array($result) ? $result : [];
    }

    public function addGallery(int $productId, array $fileNames): void
    {
        foreach ($fileNames as $fileName) {
            $this->doQuery("INSERT INTO product_galleries (product_id, image_name) VALUES (?, ?)", [$productId, $fileName]);
        }
    }

    // Le contrôleur a besoin des noms d'images pour supprimer les fichiers physiques
    public function getGalleryImagesByIds(array $safeIds): array
    {
        if (empty($safeIds)) return [];
        $placeholders = rtrim(str_repeat('?,', count($safeIds)), ',');
        $sql = "SELECT image_name FROM product_galleries WHERE id IN ($placeholders)";
        $result = $this->doSelect($sql, $safeIds);
        return is_array($result) ? $result : [];
    }

    public function deleteGallery(array $safeIds): void
    {
        if (empty($safeIds)) return;
        
        $placeholders = rtrim(str_repeat('?,', count($safeIds)), ',');
        $sql = "DELETE FROM product_galleries WHERE id IN ($placeholders)";
        $this->doQuery($sql, $safeIds);
    }

    public function getProductAttr(int $productId): array
    {
        $productInfo = $this->getProductInfo($productId);
        $categoryId = (int)($productInfo['category_id'] ?? 0);
        
        $sql = "SELECT a.*, (SELECT value_id FROM product_attribute_values pav WHERE pav.attribute_id = a.id AND pav.product_id = ?) as selected_val 
                FROM attributes a WHERE a.category_id = ?";
        
        $attributes = $this->doSelect($sql, [$productId, $categoryId]);

        if (is_array($attributes)) {
            foreach ($attributes as $key => $attr) {
                $sqlVals = "SELECT * FROM attribute_values WHERE attribute_id = ?";
                $attributes[$key]['possible_values'] = $this->doSelect($sqlVals, [(int)$attr['id']]);
            }
        }
        
        return is_array($attributes) ? $attributes : [];
    }

    public function editAttribute(int $productId, array $cleanAttributes): void
    {
        foreach ($cleanAttributes as $attrId => $valId) {
            $this->doQuery("DELETE FROM product_attribute_values WHERE product_id = ? AND attribute_id = ?", [$productId, $attrId]);
            
            if ($valId > 0) {
                $this->doQuery("INSERT INTO product_attribute_values (product_id, attribute_id, value_id) VALUES (?, ?, ?)", [$productId, $attrId, $valId]);
            }
        }
    }

    public function getReview(int $productId): array
    {
        $result = $this->doSelect("SELECT * FROM reviews WHERE product_id = ? ORDER BY id DESC", [$productId]);
        return is_array($result) ? $result : [];
    }

    public function getReviewInfo(int $reviewId): array
    {
        if (empty($reviewId)) return [];
        $result = $this->doSelect("SELECT * FROM reviews WHERE id = ?", [$reviewId], 'fetch');
        return is_array($result) ? $result : [];
    }

    public function addReview(int $productId, int $reviewId, string $title, string $description): void
    {
        if (empty($reviewId)) {
            $sql = "INSERT INTO reviews (product_id, title, description) VALUES (?, ?, ?)";
            $this->doQuery($sql, [$productId, $title, $description]);
        } else {
            $sql = "UPDATE reviews SET title = ?, description = ? WHERE id = ?";
            $this->doQuery($sql, [$title, $description, $reviewId]);
        }
    }

    public function deleteReview(array $safeIds): void
    {
        if (empty($safeIds)) return;
        
        $placeholders = rtrim(str_repeat('?,', count($safeIds)), ',');
        $sql = "DELETE FROM reviews WHERE id IN ($placeholders)";
        $this->doQuery($sql, $safeIds);
    }
}
?>