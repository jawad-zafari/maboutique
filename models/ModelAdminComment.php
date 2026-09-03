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

    public function confirm(array $cleanData): void
    {
        if (empty($cleanData)) return;

        $safeIds = [];

        foreach ($cleanData as $comment) {
            $sql = "UPDATE comments SET title = ?, positive_points = ?, negative_points = ?, content = ? WHERE id = ?";
            
            // Les données sont déjà nettoyées par le contrôleur. Le modèle exécute seulement la requête.
            $params = [
                $comment['title'], 
                $comment['positive_points'], 
                $comment['negative_points'], 
                $comment['content'], 
                $comment['id']
            ];
            
            $this->doQuery($sql, $params);
            
            // Stockage de l'ID pour la mise à jour globale du statut
            $safeIds[] = $comment['id'];
        }

        // Utilisation de placeholders dynamiques pour la clause IN
        if (!empty($safeIds)) {
            $placeholders = rtrim(str_repeat('?,', count($safeIds)), ',');
            $sqlApprove = "UPDATE comments SET is_approved = 1 WHERE id IN ($placeholders)";
            $this->doQuery($sqlApprove, $safeIds);
        }
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