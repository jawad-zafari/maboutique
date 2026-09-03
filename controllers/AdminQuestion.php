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
        // Vérification stricte de la méthode POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('HTTP/1.1 405 Method Not Allowed');
            exit;
        }

        $this->checkCsrfToken($_POST['csrf_token'] ?? '');

        $ids = $_POST['id'] ?? [];
        
        if (!empty($ids) && is_array($ids)) {
            $cleanData = [];
            
            // Le contrôleur extrait, nettoie et structure les données pour le modèle
            foreach ($ids as $id) {
                $safeId = (int)$id;
                $cleanData[] = [
                    'id'       => $safeId,
                    'question' => trim(strip_tags($_POST['question_' . $safeId] ?? '')),
                    'answer'   => trim(strip_tags($_POST['answer_' . $safeId] ?? ''))
                ];
            }

            // Le contrôleur récupère l'identifiant de l'administrateur depuis la session
            $adminId = (int)(Model::sessionGet('userId') ?? 1);

            // On passe un tableau 100% propre et sécurisé au modèle
            $this->model->confirm($cleanData, $adminId);
        }
        
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
            // Le contrôleur s'assure que le tableau ne contient que des entiers
            $safeIds = array_map('intval', $ids);
            $this->model->unconfirm($safeIds);
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
            // Le contrôleur s'assure que le tableau ne contient que des entiers
            $safeIds = array_map('intval', $ids);
            $this->model->delete($safeIds);
        }
        
        header('Location: ' . URL . 'AdminQuestion/index');
        exit;
    }
}
?>