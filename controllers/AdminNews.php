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


        $this->model->deleteNews($id);
        
        header('Location: ' . URL . 'AdminNews/index');
        exit;
    }

    

?>