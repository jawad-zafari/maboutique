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

    
}
?>