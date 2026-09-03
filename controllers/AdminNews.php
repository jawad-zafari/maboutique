<?php

class AdminNews extends Controller
{
    public function __construct()
    {
        parent::__construct();
        
        // On vérifie que l'utilisateur est bien admin ou employé
        Model::sessionInit();
        $userLevel = (int) Model::getUserLevel();
        
        if ($userLevel !== 1 && $userLevel !== 2) {
            header('Location: ' . URL . 'AdminLogin/index');
            exit; 
        }
    }

    public function index(): void
    {
        $data = [
            'news' => $this->model->getNews(), 
            'activeMenu' => 'news',
            'csrf_token' => $this->generateCsrfToken()
        ];
        
        $this->view('admin/admin_news/news', $data);
    }

    public function add(): void
    {
        $data = [
            'activeMenu' => 'news',
            'csrf_token' => $this->generateCsrfToken()
        ];
        
        $this->view('admin/admin_news/add', $data);
    }

    public function doAdd(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('HTTP/1.1 405 Method Not Allowed');
            exit;
        }

        $this->checkCsrfToken($_POST['csrf_token'] ?? '');

        // On nettoie les textes saisis par l'utilisateur pour éviter les failles
        $title = trim(strip_tags($_POST['title'] ?? ''));
        $shortDesc = trim(strip_tags($_POST['short_desc'] ?? ''));
        $createdAt = date('Y-m-d');
        
        if (empty($title) || empty($shortDesc)) {
            header('Location: ' . URL . 'AdminNews/add?error=empty');
            exit;
        }

        // Le contrôleur s'occupe de l'upload et récupère le chemin du fichier
        $imagePath = '';
        if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
            $imagePath = $this->handleImageUpload($_FILES['image']);
        }

        // On envoie des données 100% propres au modèle
        $this->model->addNews($title, $shortDesc, $imagePath, $createdAt);
        
        header('Location: ' . URL . 'AdminNews/index');
        exit;
    }

    public function edit(int $id): void
    {
        $data = [
            'newsInfo' => $this->model->getNewsById($id), 
            'activeMenu' => 'news',
            'csrf_token' => $this->generateCsrfToken()
        ];
        
        $this->view('admin/admin_news/edit', $data);
    }

    public function doEdit(int $id): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('HTTP/1.1 405 Method Not Allowed');
            exit;
        }

        $this->checkCsrfToken($_POST['csrf_token'] ?? '');

        $title = trim(strip_tags($_POST['title'] ?? ''));
        $shortDesc = trim(strip_tags($_POST['short_desc'] ?? ''));
        $createdAt = trim(strip_tags($_POST['created_at'] ?? date('Y-m-d')));

        if (empty($title)) {
            header('Location: ' . URL . 'AdminNews/edit/' . $id . '?error=empty');
            exit;
        }

        // On récupère l'ancienne image pour pouvoir la supprimer si besoin
        $newsInfo = $this->model->getNewsById($id);
        $imagePath = $newsInfo['image_path'] ?? '';

        if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
            $newImagePath = $this->handleImageUpload($_FILES['image']);
            
            if ($newImagePath !== '') {
                // On supprime l'ancien fichier physique du serveur
                if (!empty($imagePath) && file_exists($imagePath)) {
                    unlink($imagePath);
                }
                $imagePath = $newImagePath;
            }
        }

        $this->model->editNews($id, $title, $shortDesc, $imagePath, $createdAt);
        
        header('Location: ' . URL . 'AdminNews/index');
        exit;
    }

    public function delete(int $id): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('HTTP/1.1 405 Method Not Allowed');
            exit;
        }

        $this->checkCsrfToken($_POST['csrf_token'] ?? '');

        // Le contrôleur supprime le fichier image avant de demander au modèle de supprimer la ligne
        $newsInfo = $this->model->getNewsById($id);
        if (!empty($newsInfo['image_path']) && file_exists($newsInfo['image_path'])) {
            unlink($newsInfo['image_path']);
        }

        $this->model->deleteNews($id);
        
        header('Location: ' . URL . 'AdminNews/index');
        exit;
    }

    // Méthode privée pour gérer l'upload des images (logique métier dans le contrôleur)
    private function handleImageUpload(array $file): string
    {
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        $fileName = $file['name'];
        $fileTmpName = $file['tmp_name'];
        
        $extension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

        // On vérifie l'extension et le type MIME pour la sécurité
        if (!in_array($extension, $allowedExtensions)) {
            return ''; 
        }

        $mimeType = mime_content_type($fileTmpName);
        if (strpos((string)$mimeType, 'image/') !== 0) {
            return '';
        }

        $uploadDir = 'public/images/news/';
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        // On génère un nom unique pour éviter d'écraser un autre fichier
        $newFileName = 'news_' . uniqid() . '.' . $extension;
        $destination = $uploadDir . $newFileName;

        if (move_uploaded_file($fileTmpName, $destination)) {
            return $destination;
        }

        return '';
    }
}
?>