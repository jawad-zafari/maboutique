<?php

class AdminUser extends Controller
{
    public function __construct()
    {
        parent::__construct();
        
        // Seul l'administrateur principal (Niveau 1) peut gérer les utilisateurs
        Model::sessionInit();
        $level = (int) Model::getUserLevel();
        if ($level !== 1) {
            header('Location: ' . URL . 'AdminLogin/index');
            exit;
        }
    }

    public function index(): void
    {
        $users = $this->model->getUsers();
        
        $data = [
            'users' => $users,
            'csrf_token' => $this->generateCsrfToken()
        ];
        
        $this->view('admin/admin_user/users', $data);
    }

    public function changeLevel1(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('HTTP/1.1 405 Method Not Allowed');
            exit('Méthode non autorisée');
        }

        $this->checkCsrfToken($_POST['csrf_token'] ?? '');
            
        $ids = $_POST['id'] ?? [];
        if (!empty($ids) && is_array($ids)) {
            $this->model->changeLevel1($ids);
        }
        
        header('Location: ' . URL . 'AdminUser/index');
        exit;
    }

    
}
?>