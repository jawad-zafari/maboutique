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

    public function addNews(array $data, array $files): void
    {
        // Les données sont stockées brutes. PDO empêche l'injection SQL.
        $title = $data['title'] ?? '';
        $shortDesc = $data['short_desc'] ?? '';
        $createdAt = date('Y-m-d'); 

        if (empty($title)) return;

        $sql = "INSERT INTO news (title, short_desc, image_path, created_at) VALUES (?, ?, '', ?)";
        $this->doQuery($sql, [$title, $shortDesc, $createdAt]);
        
        $newsId = (int) self::$conn->lastInsertId();

        $this->uploadImage($files, $newsId);
    }

    public function editNews(int $id, array $data, array $files): void
    {
        $title = $data['title'] ?? '';
        $shortDesc = $data['short_desc'] ?? '';
        $createdAt = $data['created_at'] ?? date('Y-m-d');

        if (empty($title)) return;

        $sql = "UPDATE news SET title = ?, short_desc = ?, created_at = ? WHERE id = ?";
        $this->doQuery($sql, [$title, $shortDesc, $createdAt, $id]);

        $this->uploadImage($files, $id, true);
    }

    public function deleteNews(int $id): void
    {
        $news = $this->getNewsById($id);
        
        // Supprimer physiquement l'image du serveur
        if (!empty($news['image_path']) && file_exists($news['image_path'])) {
            unlink($news['image_path']);
        }

        $sql = "DELETE FROM news WHERE id = ?";
        $this->doQuery($sql, [$id]);
    }

   
}
?>