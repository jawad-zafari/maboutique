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

    // Récupère les informations d'une actualité spécifique

    private function uploadImage(array $files, int $id, bool $isEdit = false): void
    {
        if (!empty($files['image']['name']) && $files['image']['error'] === 0) {
            
            // Liste blanche des extensions autorisées
            $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
            $fileName = $files['image']['name'];
            $fileTmpName = $files['image']['tmp_name'];
            
            $extension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

            // Bloquer si l'extension n'est pas valide
            if (!in_array($extension, $allowedExtensions)) {
                return; 
            }

            // Vérification du type MIME réel
            $mimeType = mime_content_type($fileTmpName);
            if (strpos((string)$mimeType, 'image/') !== 0) {
                return;
            }

            $uploadDir = 'public/images/news/';
            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            $newFileName = 'news_' . $id . '_' . time() . '.' . $extension;
            $destination = $uploadDir . $newFileName;

            if (move_uploaded_file($fileTmpName, $destination)) {
                
                // Supprimer l'ancienne image si c'est une modification
                if ($isEdit) {
                    $oldNews = $this->getNewsById($id);
                    if (!empty($oldNews['image_path']) && file_exists($oldNews['image_path'])) {
                        unlink($oldNews['image_path']);
                    }
                }

                $sqlUpdate = "UPDATE news SET image_path = ? WHERE id = ?";
                $this->doQuery($sqlUpdate, [$destination, $id]);
            }
        }
    }
}
?>