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

    public function confirm(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('HTTP/1.1 405 Method Not Allowed');
            exit;
        }

        $this->checkCsrfToken($_POST['csrf_token'] ?? '');

        // Nettoyage des données POST pour éviter les injections et les entrées malveillantes
        $cleanData = [];
        foreach ($_POST as $key => $value) {
            if (is_array($value)) {
                $cleanData[$key] = $value; 
            } else {
                $cleanData[$key] = is_string($value) ? trim($value) : $value;
            }
        }

        $this->model->confirm($cleanData);
        
        header('Location: ' . URL . 'AdminComment/index');
        exit;
    }

    
}
?>