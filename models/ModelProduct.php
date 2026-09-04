<?php

class ModelProduct extends Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function productInfo(int $id): array
    {
        // Incrémentation sécurisée du compteur de vues
        $sqlUpdateView = "UPDATE products SET views = views + 1 WHERE id = ?";
        $this->doQuery($sqlUpdateView, [$id]);

        $sql = "SELECT * FROM products WHERE id = ?";
        $result = $this->doSelect($sql, [$id], 'fetch', PDO::FETCH_ASSOC);
        
        if (!$result) return [];

        // Récupération des couleurs disponibles
        $sqlColors = "SELECT pc.*, c.title FROM product_colors pc JOIN colors c ON pc.color_id = c.id WHERE pc.product_id = ?";
        $result['colors'] = $this->doSelect($sqlColors, [$id]);

        // Récupération des garanties disponibles
        $sqlGuarantees = "SELECT pg.*, g.title FROM product_guarantees pg JOIN guarantees g ON pg.guarantee_id = g.id WHERE pg.product_id = ?";
        $result['guarantees'] = $this->doSelect($sqlGuarantees, [$id]);

        return $result;
    }

    public function getExclusiveProducts(): array
    {
        $sql = "SELECT * FROM products WHERE is_exclusive = 1 ORDER BY id DESC LIMIT 5";
        $result = $this->doSelect($sql);
        return is_array($result) ? $result : [];
    }

    public function getGallery(int $id): array
    {
        $sql = "SELECT * FROM product_galleries WHERE product_id = ? ORDER BY is_main DESC";
        $result = $this->doSelect($sql, [$id]);
        return is_array($result) ? $result : [];
    }

    public function getExpertReviews(int $id): array
    {
        $sql = "SELECT * FROM product_reviews WHERE product_id = ?";
        $result = $this->doSelect($sql, [$id]);
        return is_array($result) ? $result : [];
    }

    public function getTechnicalSpecs(int $categoryId, int $productId): array
    {
        $sql = "SELECT a.title, av.value 
                FROM attributes a 
                LEFT JOIN attribute_values av ON a.id = av.attribute_id AND av.product_id = ? 
                WHERE a.category_id = ?";
        $result = $this->doSelect($sql, [$productId, $categoryId]);
        return is_array($result) ? $result : [];
    }

    public function getCommentParameters(int $categoryId, int $productId): array
    {
        $sqlParams = "SELECT * FROM review_parameters WHERE category_id = ?";
        $params = $this->doSelect($sqlParams, [$categoryId]);

        $sqlScores = "SELECT parameter_id, AVG(score) as avg_score 
                      FROM comment_scores cs 
                      JOIN comments c ON cs.comment_id = c.id 
                      WHERE c.product_id = ? 
                      GROUP BY parameter_id";
        $scoresRaw = $this->doSelect($sqlScores, [$productId]);
        
        $scores = [];
        if (is_array($scoresRaw)) {
            foreach ($scoresRaw as $row) {
                $scores[$row['parameter_id']] = $row['avg_score'];
            }
        }

        return [is_array($params) ? $params : [], $scores];
    }

    public function getProductComments(int $id): array
    {
        $sql = "SELECT c.*, u.username as first_name, u.last_name 
                FROM comments c 
                LEFT JOIN users u ON c.user_id = u.id 
                WHERE c.product_id = ? AND c.is_approved = 1 
                ORDER BY c.id DESC";
        $result = $this->doSelect($sql, [$id]);
        return is_array($result) ? $result : [];
    }

    public function getQuestionsAndAnswers(int $id): array
    {
        $sqlQ = "SELECT * FROM questions WHERE product_id = ? AND parent_id = 0 AND is_approved = 1 ORDER BY id DESC";
        $questions = $this->doSelect($sqlQ, [$id]);

        $sqlA = "SELECT * FROM questions WHERE product_id = ? AND parent_id != 0 AND is_approved = 1";
        $answersRaw = $this->doSelect($sqlA, [$id]);
        
        $answers = [];
        if (is_array($answersRaw)) {
            foreach ($answersRaw as $ans) {
                $answers[$ans['parent_id']] = $ans;
            }
        }

        return [is_array($questions) ? $questions : [], $answers];
    }

    public function addQuestion(int $productId, int $userId, string $safeContent): void
    {
        $createdAt = date('Y-m-d H:i:s');
        $sql = "INSERT INTO questions (content, product_id, user_id, parent_id, is_approved, created_at) VALUES (?, ?, ?, 0, 0, ?)";
        $this->doQuery($sql, [$safeContent, $productId, $userId, $createdAt]);
    }
}
?>