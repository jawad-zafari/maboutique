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

    public function addSlider(array $data, array $files): bool
    {
        // Nettoyage des données d'entrée
        $title = $data['title'] ?? '';
        $link = !empty($data['link']) ? $data['link'] : '#';
        $description = $data['description'] ?? '';
        $button_text = !empty($data['button_text']) ? $data['button_text'] : 'Découvrir';
        $text_color = !empty($data['text_color']) ? $data['text_color'] : '#ffffff';
        
        $file = $files['image'] ?? null;
        $target = '';

        // Validation de l'image
        if ($file && !empty($file['name']) && $file['error'] === 0) {
            
            $allowedExtensions = ['jpg', 'jpeg', 'png', 'webp'];
            $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

            if (in_array($ext, $allowedExtensions)) {
                
                $mimeType = mime_content_type($file['tmp_name']);
                
                if (strpos((string)$mimeType, 'image/') === 0 && (int)$file['size'] <= 5242880) {
                    $targetMain = 'public/images/slider/';
                    $newName = uniqid('slide_') . '.' . $ext;
                    
                    if (!file_exists($targetMain)) {
                        mkdir($targetMain, 0777, true);
                    }
                    
                    $target = $targetMain . $newName;
                    if (!move_uploaded_file($file['tmp_name'], $target)) {
                        return false; 
                    }
                } else {
                    return false; 
                }
            } else {
                return false; 
            }
        } else {
            // L'image est requise pour l'ajout
            return false;
        }

        $sql = "INSERT INTO sliders (title, link, image_path, description, button_text, text_color) VALUES (?, ?, ?, ?, ?, ?)";
        $this->doQuery($sql, [$title, $link, $target, $description, $button_text, $text_color]);
        return true;
    }

    public function updateSlider(int $id, array $data, array $files): bool
    {
        $title = $data['title'] ?? '';
        $link = !empty($data['link']) ? $data['link'] : '#';
        $description = $data['description'] ?? '';
        $button_text = !empty($data['button_text']) ? $data['button_text'] : 'Découvrir';
        $text_color = !empty($data['text_color']) ? $data['text_color'] : '#ffffff';

        $sliderInfo = $this->getSliderById($id);
        $imagePath = $sliderInfo['image_path'] ?? '';
        $file = $files['image'] ?? null;

        if ($file && !empty($file['name']) && $file['error'] === 0) {
            
            $allowedExtensions = ['jpg', 'jpeg', 'png', 'webp'];
            $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

            if (in_array($ext, $allowedExtensions)) {
                $mimeType = mime_content_type($file['tmp_name']);
                
                if (strpos((string)$mimeType, 'image/') === 0 && (int)$file['size'] <= 5242880) {
                    $targetMain = 'public/images/slider/';
                    $newName = uniqid('slide_') . '.' . $ext;
                    $target = $targetMain . $newName;

                    if (!file_exists($targetMain)) mkdir($targetMain, 0777, true);

                    if (move_uploaded_file($file['tmp_name'], $target)) {
                        if (!empty($imagePath) && file_exists($imagePath)) {
                            unlink($imagePath);
                        }
                        $imagePath = $target;
                    } else {
                        return false;
                    }
                } else {
                    return false; 
                }
            } else {
                return false; 
            }
        }

        $sql = "UPDATE sliders SET title = ?, link = ?, image_path = ?, description = ?, button_text = ?, text_color = ? WHERE id = ?";
        $this->doQuery($sql, [$title, $link, $imagePath, $description, $button_text, $text_color, $id]);
        return true;
    }

   
}
?>