<?php

class AdminQuestion extends Controller
{
    public function __construct()
    {
        parent::__construct();
        
        // Initialisation de la session pour vérifier le niveau d'accès
        Model::sessionInit();
        $level = (int) Model::getUserLevel();
        if ($level !== 1) {
            header('Location: ' . URL . 'AdminLogin/index');
            exit;
        }
    }

    public function index(): void
    {
        $questions = $this->model->getQuestions();
        
        $data = [
            'questions' => $questions,
            'csrf_token' => $this->generateCsrfToken() 
        ];
        
        $this->view('admin/admin_question/question', $data);
    }

    public function confirm(): void
    {
        //  SÉCURITÉ : Vérification stricte de la méthode POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('HTTP/1.1 405 Method Not Allowed');
            exit;
        }

        $this->checkCsrfToken($_POST['csrf_token'] ?? '');

        // SÉCURITÉ : Nettoyage des données POST pour éviter les injections et les entrées malveillantes
        $cleanData = [];
        foreach ($_POST as $key => $value) {
            if (is_array($value)) {
                $cleanData[$key] = $value;
            } else {
                $cleanData[$key] = is_string($value) ? trim($value) : $value;
            }
        }

        $this->model->confirm($cleanData);
        
        header('Location: ' . URL . 'AdminQuestion/index');
        exit;
    }

    public function unconfirm(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('HTTP/1.1 405 Method Not Allowed');
            exit;
        }

        $this->checkCsrfToken($_POST['csrf_token'] ?? '');

        $ids = $_POST['id'] ?? [];
        if (!empty($ids) && is_array($ids)) {
            $this->model->unconfirm($ids);
        }
        
        header('Location: ' . URL . 'AdminQuestion/index');
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
            $this->model->delete($ids);
        }
        
        header('Location: ' . URL . 'AdminQuestion/index');
        exit;
    }
}
?>