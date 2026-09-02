<?php

class ModelAdminComment extends Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getComment(): array
    {
        $sql = "SELECT * FROM comments ORDER BY id DESC";
        $result = $this->doSelect($sql);
        return is_array($result) ? $result : [];
    }

    public function confirm(array $data): void
    {
        if (empty($data['id']) || !is_array($data['id'])) return;

        foreach ($data['id'] as $id) {
            $sql = "UPDATE comments SET title = ?, positive_points = ?, negative_points = ?, content = ? WHERE id = ?";
            
            // Les données sont déjà nettoyées par le contrôleur
            $title = $data['title_' . $id] ?? '';
            $positive = $data['positive_points_' . $id] ?? '';
            $negative = $data['negative_points_' . $id] ?? '';
            $content = $data['content_' . $id] ?? '';

            $params = [$title, $positive, $negative, $content, (int)$id];
            $this->doQuery($sql, $params);
        }

        // Utilisation de placeholders dynamiques pour la clause IN
        $safeIds = array_map('intval', $data['id']);
        $placeholders = rtrim(str_repeat('?,', count($safeIds)), ',');
        
        $sqlApprove = "UPDATE comments SET is_approved = 1 WHERE id IN ($placeholders)";
        $this->doQuery($sqlApprove, $safeIds);
    }

    public function unconfirm(array $ids): void
    {
        if (empty($ids)) return;

        $safeIds = array_map('intval', $ids);
        $placeholders = rtrim(str_repeat('?,', count($safeIds)), ',');
        
        $sql = "UPDATE comments SET is_approved = 0 WHERE id IN ($placeholders)";
        $this->doQuery($sql, $safeIds);
    }

    public function delete(array $ids): void
    {
        if (empty($ids)) return;

        $safeIds = array_map('intval', $ids);
        $placeholders = rtrim(str_repeat('?,', count($safeIds)), ',');
        
        $sql = "DELETE FROM comments WHERE id IN ($placeholders)";
        $this->doQuery($sql, $safeIds);
    }
}
?>