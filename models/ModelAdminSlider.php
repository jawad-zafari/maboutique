<?php

class ModelAdminSlider extends Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getslider(): array
    {
        $sql = "SELECT * FROM sliders ORDER BY id DESC";
        $result = $this->doSelect($sql);
        return is_array($result) ? $result : [];
    }

    public function getSliderById(int $id): array
    {
        $sql = "SELECT * FROM sliders WHERE id = ?";
        $result = $this->doSelect($sql, [$id], 'fetch');
        return is_array($result) ? $result : [];
    }

    // Le contrôleur récupère les chemins d'images pour pouvoir supprimer les fichiers physiques
    public function getSliderImagesByIds(array $safeIds): array
    {
        if (empty($safeIds)) return [];
        $placeholders = rtrim(str_repeat('?,', count($safeIds)), ',');
        $sql = "SELECT image_path FROM sliders WHERE id IN ($placeholders)";
        $result = $this->doSelect($sql, $safeIds);
        return is_array($result) ? $result : [];
    }

    // Le modèle ne reçoit que des variables propres et parfaitement typées
    public function addSlider(string $title, string $link, string $imagePath, string $description, string $buttonText, string $textColor): void
    {
        $sql = "INSERT INTO sliders (title, link, image_path, description, button_text, text_color) VALUES (?, ?, ?, ?, ?, ?)";
        $this->doQuery($sql, [$title, $link, $imagePath, $description, $buttonText, $textColor]);
    }

    public function updateSlider(int $id, string $title, string $link, string $imagePath, string $description, string $buttonText, string $textColor): void
    {
        $sql = "UPDATE sliders SET title = ?, link = ?, image_path = ?, description = ?, button_text = ?, text_color = ? WHERE id = ?";
        $this->doQuery($sql, [$title, $link, $imagePath, $description, $buttonText, $textColor, $id]);
    }

    public function delete(array $safeIds): void
    {
        if (empty($safeIds)) return;
        
        $placeholders = rtrim(str_repeat('?,', count($safeIds)), ',');
        $sql = "DELETE FROM sliders WHERE id IN ($placeholders)";
        $this->doQuery($sql, $safeIds);
    }
}
?>