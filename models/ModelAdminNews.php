<?php

class ModelAdminNews extends Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getNews(): array
    {
        $sql = "SELECT * FROM news ORDER BY id DESC";
        $result = $this->doSelect($sql);
        return is_array($result) ? $result : [];
    }

    public function getNewsById(int $id): array
    {
        $sql = "SELECT * FROM news WHERE id = ?";
        $result = $this->doSelect($sql, [$id]);
        return $result[0] ?? [];
    }

    // Le modèle ne reçoit que des variables propres, pas de $_FILES
    public function addNews(string $title, string $shortDesc, string $imagePath, string $createdAt): void
    {
        $sql = "INSERT INTO news (title, short_desc, image_path, created_at) VALUES (?, ?, ?, ?)";
        $this->doQuery($sql, [$title, $shortDesc, $imagePath, $createdAt]);
    }

    public function editNews(int $id, string $title, string $shortDesc, string $imagePath, string $createdAt): void
    {
        $sql = "UPDATE news SET title = ?, short_desc = ?, image_path = ?, created_at = ? WHERE id = ?";
        $this->doQuery($sql, [$title, $shortDesc, $imagePath, $createdAt, $id]);
    }

    public function deleteNews(int $id): void
    {
        $sql = "DELETE FROM news WHERE id = ?";
        $this->doQuery($sql, [$id]);
    }
}
?>