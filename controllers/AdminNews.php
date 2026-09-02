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

    
}
?>