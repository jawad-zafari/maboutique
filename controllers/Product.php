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
        
        
    }

    
    
}
?>