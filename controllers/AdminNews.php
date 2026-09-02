<?php

class AdminNews extends Controller
{
    public function __construct()
    {
        parent::__construct();
        
        // Initialisation de la session pour vérifier le niveau d'accès
        Model::sessionInit();
        $userLevel = (int) Model::getUserLevel();
        
        if ($userLevel !== 1 && $userLevel !== 2) {
            header('Location: ' . URL . 'AdminLogin/index');
            exit; 
        }
    }

    public function index(): void
    {
        $news = $this->model->getNews();
        
        $data = [
            'news' => $news, 
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

        // Nettoyage des données POST pour éviter les injections et les entrées malveillantes
        $cleanData = [];
        foreach ($_POST as $key => $value) {
            $cleanData[$key] = is_string($value) ? trim($value) : $value;
        }

        $this->model->addNews($cleanData, $_FILES);
        
        header('Location: ' . URL . 'AdminNews/index');
        exit;
    }

    public function edit(int $id): void
    {
        $newsInfo = $this->model->getNewsById($id);
        
        $data = [
            'newsInfo' => $newsInfo, 
            'activeMenu' => 'news',
            'csrf_token' => $this->generateCsrfToken()
        ];
        
        $this->view('admin/admin_news/edit', $data);
    }

    
}
?>