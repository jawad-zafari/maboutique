<?php

class AddComment extends Controller
{
    public function __construct()
    {
        parent::__construct();
        
        // SÉCURITÉ : Vérification de l'authentification
        Model::sessionInit();
        $userId = (int) Model::sessionGet('userId');

        if ($userId === 0) {
            header('Location: ' . URL . 'Login/index');
            exit;
        }
    }

    // Affiche le formulaire d'ajout ou de modification de commentaire
    public function index(string $productId): void
    {
        $userId = (int) Model::sessionGet('userId');
        $productIdInt = (int) $productId;

        $commentInfo = $this->model->commentInfo($productIdInt, $userId);
        
        // ARCHITECTURE MVC : Préparation des données dans le contrôleur (et non dans la vue)
        $commentParams = [];
        if (!empty($commentInfo['parameters'])) {
            // SÉCURITÉ : Prévention de l'attaque PHP Object Injection
            $commentParams = unserialize($commentInfo['parameters'], ['allowed_classes' => false]);
        }
        if (!is_array($commentParams)) {
            $commentParams = [];
        }

        $data = [
            'params'        => $this->model->getParam($productIdInt),
            'productInfo'   => $this->model->productInfo($productIdInt),
            'commentInfo'   => $commentInfo,
            'commentParams' => $commentParams,
            // SÉCURITÉ : Jeton CSRF
            'csrf_token'    => $this->generateCsrfToken()
        ];
        
        $this->view('comment/add_comment', $data);
    }

    // Sauvegarde le commentaire en base de données
    public function saveComment(string $productId): void
    {
        // SÉCURITÉ : Bloque le Method Spoofing
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('HTTP/1.1 405 Method Not Allowed');
            exit;
        }

        // SÉCURITÉ : Vérification CSRF
        $this->checkCsrfToken($_POST['csrf_token'] ?? '');

        $userId = (int) Model::sessionGet('userId');
        $productIdInt = (int) $productId;

        // SÉCURITÉ (Input Sanitization) : Nettoyage strict des entrées
        $cleanData = [
            'title'    => trim(strip_tags($_POST['title'] ?? '')),
            'positive' => trim(strip_tags($_POST['positive'] ?? '')),
            'negative' => trim(strip_tags($_POST['negative'] ?? '')),
            'comment'  => trim(strip_tags($_POST['comment'] ?? ''))
        ];

        // Validation basique
        if (empty($cleanData['title']) || empty($cleanData['comment'])) {
            header('Location: ' . URL . 'AddComment/index/' . $productIdInt . '?error=empty');
            exit;
        }

        // Extraction et sécurisation des notes (Curseurs)
        $commentParams = $this->model->getParam($productIdInt);
        $paramScores = [];
        
        foreach ($commentParams as $row) {
            $paramId = $row['id'];
            $score = isset($_POST['param' . $paramId]) ? (int) $_POST['param' . $paramId] : 3;
            
            // Validation des limites (1 à 5)
            if ($score < 1) $score = 1;
            if ($score > 5) $score = 5;
            
            $paramScores[$paramId] = $score;
        }
        
        $cleanData['parameters'] = $paramScores;

        // Appel au modèle avec des données 100% sécurisées
        $this->model->saveComment($cleanData, $productIdInt, $userId);
        
        // Redirection 
        header('Location: ' . URL . 'Product/index/' . $productIdInt . '?success=comment');
        exit;
    }
}
?>