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

   
    //   Récupèrer toutes les informations d'un produit spécifique et calcule les prix.
     
    public function productInfo($id)
    {
        // SÉCURITÉ : Forçage du type
        $id = (int)$id; 
        
        // Incrémentation du compteur de vues du produit
        $sqlUpdateView = "UPDATE products SET views = views + 1 WHERE id = ?";
        $this->doQuery($sqlUpdateView, [$id]);

        $sql = "SELECT * FROM products WHERE id = ?";
        $result = $this->doSelect($sql, [$id], true);
        
        if (!$result) return [];

        // Calcul des prix et des remises
        $price = $result['price'] ?? 0;
        $discount = $result['discount_percent'] ?? 0;
        $priceCalculate = $this->calculateDiscount($price, $discount);
        $result['price_discount'] = $priceCalculate[0];
        $result['price_total'] = $priceCalculate[1];

        // Calculer la date d'expiration si c'est une offre spéciale
        $timeSpecial = $result['special_offer_expires_at'] ?? 0;
        $options = self::getoption();
        $durationSpecial = $options['special_time'] ?? 0;
        $timeEnd = $timeSpecial + $durationSpecial;
        
        date_default_timezone_set('Europe/Paris');
        $result['date_special'] = date('F d,Y H:i:s', $timeEnd);

        // Récupération des couleurs disponibles
        $sqlColors = "SELECT * FROM product_colors pc JOIN colors c ON pc.color_id = c.id WHERE pc.product_id = ?";
        $result['colors'] = $this->doSelect($sqlColors, [$id]);

        // Récupération des garanties disponibles
        $sqlGuarantees = "SELECT * FROM product_guarantees pg JOIN guarantees g ON pg.guarantee_id = g.id WHERE pg.product_id = ?";
        $result['guarantees'] = $this->doSelect($sqlGuarantees, [$id]);

        return $result;
    }

    
    //  Récupère une liste de produits marqués comme exclusifs (Limite à 5).
    
    public function getExclusiveProducts()
    {
        $sql = "SELECT * FROM products WHERE is_exclusive = 1 ORDER BY id DESC LIMIT 5";
        return $this->doSelect($sql);
    }

   
    // Récupère la galerie d'images secondaires du produit.
     
    public function getGallery($id)
    {
        // L'image principale (is_main) est affichée en premier
        $sql = "SELECT * FROM product_galleries WHERE product_id = ? ORDER BY is_main DESC";
        return $this->doSelect($sql, [(int)$id]);
    }

    //   Récupère les évaluations et avis rédigés par les experts.
    
    public function getExpertReviews($id)
    {
        $sql = "SELECT * FROM product_reviews WHERE product_id = ?";
        return $this->doSelect($sql, [(int)$id]);
    }

    
    //  Récupère les spécifications techniques du produit selon sa catégorie.
    
    public function getTechnicalSpecs($categoryId, $productId)
    {
        $sql = "SELECT a.title, av.value 
                FROM attributes a 
                LEFT JOIN attribute_values av ON a.id = av.attribute_id AND av.product_id = ? 
                WHERE a.category_id = ?";
        return $this->doSelect($sql, [(int)$productId, (int)$categoryId]);
    }

    //  Récupèrer les critères d'évaluation de la catégorie et calcule les scores moyens.
     
    public function getCommentParameters($categoryId, $productId)
    {
        $sqlParams = "SELECT * FROM review_parameters WHERE category_id = ?";
        $params = $this->doSelect($sqlParams, [(int)$categoryId]);

        $sqlScores = "SELECT parameter_id, AVG(score) as avg_score 
                      FROM comment_scores cs 
                      JOIN comments c ON cs.comment_id = c.id 
                      WHERE c.product_id = ? 
                      GROUP BY parameter_id";
        $scoresRaw = $this->doSelect($sqlScores, [(int)$productId]);
        
        $scores = [];
        foreach ($scoresRaw as $row) {
            $scores[$row['parameter_id']] = $row['avg_score'];
        }

        return [$params, $scores];
    }

    //   Récupère les commentaires validés par les administrateurs (is_approved = 1).
     
    public function getProductComments($id)
    {
        $sql = "SELECT c.*, u.username as first_name, u.last_name 
                FROM comments c 
                LEFT JOIN users u ON c.user_id = u.id 
                WHERE c.product_id = ? AND c.is_approved = 1 
                ORDER BY c.id DESC";
        return $this->doSelect($sql, [(int)$id]);
    }

   
}
?>