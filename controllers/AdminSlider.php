<?php

class AdminSlider extends Controller
{
    public function __construct()
    {
        parent::__construct();
        
        // Initialisation de la session et vérification des droits
        Model::sessionInit();
        $level = (int) Model::getUserLevel();
        if ($level !== 1) {
            header('Location: ' . URL . 'AdminLogin/index');
            exit;
        }
    }

    public function index(): void
    {
        $sliders = $this->model->getslider();
        
        $data = [
            'slider' => $sliders,
            'editSlider' => null,
            'csrf_token' => $this->generateCsrfToken()
        ];
        
        $this->view('admin/admin_slider/slider', $data);
    }

    public function add(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('HTTP/1.1 405 Method Not Allowed');
            exit;
        }

        $this->checkCsrfToken($_POST['csrf_token'] ?? '');

        // Le contrôleur nettoie et structure les données (Protection XSS)
        $title = trim(strip_tags($_POST['title'] ?? ''));
        $link = filter_var(trim($_POST['link'] ?? '#'), FILTER_SANITIZE_URL);
        $description = trim(strip_tags($_POST['description'] ?? ''));
        $buttonText = trim(strip_tags($_POST['button_text'] ?? 'Découvrir'));
        $textColor = trim(strip_tags($_POST['text_color'] ?? '#ffffff'));

        if (empty($link)) $link = '#';
        if (empty($buttonText)) $buttonText = 'Découvrir';
        if (empty($textColor)) $textColor = '#ffffff';

        // Le contrôleur gère l'upload de l'image
        $imagePath = '';
        if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
            $imagePath = $this->handleImageUpload($_FILES['image']);
        }

        if (empty($imagePath)) {
            header('Location: ' . URL . 'AdminSlider/index?error=upload');
            exit;
        }

        // Le modèle reçoit des données 100% propres et le chemin textuel de l'image
        $this->model->addSlider($title, $link, $imagePath, $description, $buttonText, $textColor);
        
        header('Location: ' . URL . 'AdminSlider/index?success=add');
        exit;
    }

    public function edit(int $id): void
    {
        $sliders = $this->model->getslider();
        $editSlider = $this->model->getSliderById($id);

        if (empty($editSlider)) {
            header('Location: ' . URL . 'AdminSlider/index');
            exit;
        }

        $data = [
            'slider' => $sliders,
            'editSlider' => $editSlider,
            'csrf_token' => $this->generateCsrfToken()
        ];

        $this->view('admin/admin_slider/slider', $data);
    }

    public function update(int $id): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('HTTP/1.1 405 Method Not Allowed');
            exit;
        }

        $this->checkCsrfToken($_POST['csrf_token'] ?? '');
            
       

        $this->model->updateSlider($id, $title, $link, $imagePath, $description, $buttonText, $textColor);
        
        header('Location: ' . URL . 'AdminSlider/index?success=update');
        exit;
    }

    public function delete(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('HTTP/1.1 405 Method Not Allowed');
            exit;
        }

        $this->checkCsrfToken($_POST['csrf_token'] ?? '');
            
        $ids = $_POST['id'] ?? [];
        if (!empty($ids) && is_array($ids)) {
            $safeIds = array_map('intval', $ids);
            
            // Le contrôleur supprime les fichiers physiques
            $images = $this->model->getSliderImagesByIds($safeIds);
            foreach ($images as $img) {
                if (!empty($img['image_path']) && file_exists($img['image_path'])) {
                    unlink($img['image_path']);
                }
            }
            
            // Le modèle supprime les lignes de la base de données
            $this->model->delete($safeIds);
        }

        header('Location: ' . URL . 'AdminSlider/index?success=delete');
        exit;
    }

    // Méthode privée pour gérer l'upload des images (logique métier dans le contrôleur)
    private function handleImageUpload(array $file): string
    {
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'webp'];
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

        if (!in_array($ext, $allowedExtensions)) {
            return '';
        }

        $mimeType = mime_content_type($file['tmp_name']);
        if (strpos((string)$mimeType, 'image/') !== 0 || (int)$file['size'] > 5242880) {
            return '';
        }

        $targetMain = 'public/images/slider/';
        $newName = uniqid('slide_') . '.' . $ext;
        
        if (!file_exists($targetMain)) {
            mkdir($targetMain, 0777, true);
        }
        
        $destination = $targetMain . $newName;
        
        if (move_uploaded_file($file['tmp_name'], $destination)) {
            return $destination;
        }

        return '';
    }
}
?>