<?php

class AdminComment extends Controller
{
    public function __construct()
    {
        parent::__construct();
        
        // Vérification stricte des droits d'accès
        Model::sessionInit();
        $level = (int) Model::getUserLevel();
        if ($level !== 1) {
            header('Location: ' . URL . 'AdminLogin/index');
            exit;
        }
    }

    public function index(): void
    {
        $data = [
            'comment' => $this->model->getComment(),
            'csrf_token' => $this->generateCsrfToken()
        ];
        
        $this->view('admin/admin_comment/comment', $data);
    }

   
}
?>