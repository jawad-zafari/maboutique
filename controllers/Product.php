<?php

class Product extends Controller
{
    public function __construct()
    {
        parent::__construct();
        Model::sessionInit(); 
    }

    
    //  Affiche la page principale d'un produit
    
    public function index($id, $activeTab = 'reviews')
    {
        // PROTECTION CSRF
        $csrf_token = $this->generateCsrfToken();

        $productId = (int)$id;
        $productInfo = $this->model->productInfo($productId);
        
        // Gestion d'erreur
        if (empty($productInfo)) {
            header('Location: ' . URL . 'Index/index');
            exit;
        }

        // Récupération des données associées au produit
        $exclusives = $this->model->getExclusiveProducts();
        $gallery = $this->model->getGallery($productId);

        $idCategory = (int)($productInfo['category_id'] ?? 0);
        $expertReviews = $this->model->getExpertReviews($productId);
        $specifications = $this->model->getTechnicalSpecs($idCategory, $productId);
        
        // Récupération des paramètres et des scores pour les avis clients
        $commentParam = $this->model->getCommentParameters($idCategory, $productId);
        $commentParamNames = $commentParam[0] ?? [];
        $commentParamScores = $commentParam[1] ?? [];
        
        $comments = $this->model->getProductComments($productId);
        
        // Récupération des questions et des réponses associées
        $qaData = $this->model->getQuestionsAndAnswers($productId);
        $questions = $qaData[0] ?? [];
        $answers = $qaData[1] ?? [];

        // Préparation des données pour la vue 
        $data = [
            'productInfo'    => $productInfo,
            'exclusives'     => $exclusives,
            'gallery'        => $gallery,
            'reviews'        => $expertReviews,
            'specs'          => $specifications,
            'comment_params' => $commentParamNames,
            'comment_scores' => $commentParamScores,
            'comments'       => $comments,
            'questions'      => $questions,
            'answers'        => $answers,
            // Protection XSS sur la variable de l'onglet actif
            'activeTab'      => htmlspecialchars($activeTab, ENT_QUOTES, 'UTF-8'),
            'csrf_token'     => $csrf_token
        ];

       
    }

    
    
}
?>