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

   
}
?>