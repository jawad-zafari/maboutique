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

    // Nettoyage strict des données du formulaire pour éviter les attaques XSS et l'injection SQL
        $ids = $_POST['id'] ?? [];
        
        if (!empty($ids) && is_array($ids)) {
            $cleanCommentsData = [];

            foreach ($ids as $id) {
                $safeId = (int) $id;
                
                // Nettoyage strict avec trim et strip_tags contre la faille XSS stockée
                $cleanCommentsData[] = [
                    'id'              => $safeId,
                    'title'           => trim(strip_tags($_POST['title_' . $safeId] ?? '')),
                    'positive_points' => trim(strip_tags($_POST['positive_points_' . $safeId] ?? '')),
                    'negative_points' => trim(strip_tags($_POST['negative_points_' . $safeId] ?? '')),
                    'content'         => trim(strip_tags($_POST['content_' . $safeId] ?? ''))
                ];
            }

            // Envoi d'un tableau propre, structuré et indépendant du formulaire HTML au modèle
            $this->model->confirm($cleanCommentsData);
        }
        
        header('Location: ' . URL . 'AdminComment/index');
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
        
        header('Location: ' . URL . 'AdminComment/index');
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
        
        header('Location: ' . URL . 'AdminComment/index');
        exit;
    }
}
?>