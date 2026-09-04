<?php

class Product extends Controller
{
    public function __construct()
    {
        parent::__construct();
        Model::sessionInit(); 
    }

    // Affiche la page principale d'un produit
    public function index($id, $activeTab = 'reviews'): void
    {
        $csrfToken = $this->generateCsrfToken();
        $productId = (int)$id;
        
        // Récupération des données brutes du produit
        $productInfo = $this->model->productInfo($productId);
        
        if (empty($productInfo)) {
            header('Location: ' . URL . 'Index/index');
            exit;
        }

        // Logique métier : Calcul des prix et des remises dans le contrôleur
        $price = (float)($productInfo['price'] ?? 0);
        $discount = (int)($productInfo['discount_percent'] ?? 0);
        $priceCalculate = $this->model->calculateDiscount($price, $discount);
        $productInfo['price_discount'] = $priceCalculate[0];
        $productInfo['price_total'] = $priceCalculate[1];

        // Calcul de la date d'expiration si c'est une offre spéciale
        $options = Model::getoption();
        $durationSpecial = (int)($options['special_time'] ?? 0);
        $timeSpecial = (int)($productInfo['special_offer_expires_at'] ?? 0);
        $timeEnd = $timeSpecial + $durationSpecial;
        
        date_default_timezone_set('Europe/Paris');
        $productInfo['date_special'] = date('F d,Y H:i:s', $timeEnd);

        // Récupération des données associées
        $exclusives = $this->model->getExclusiveProducts();
        $gallery = $this->model->getGallery($productId);

        $idCategory = (int)($productInfo['category_id'] ?? 0);
        $expertReviews = $this->model->getExpertReviews($productId);
        $specifications = $this->model->getTechnicalSpecs($idCategory, $productId);
        
        $commentParam = $this->model->getCommentParameters($idCategory, $productId);
        $commentParamNames = $commentParam[0] ?? [];
        $commentParamScores = $commentParam[1] ?? [];
        
        $comments = $this->model->getProductComments($productId);
        
        $qaData = $this->model->getQuestionsAndAnswers($productId);
        $questions = $qaData[0] ?? [];
        $answers = $qaData[1] ?? [];

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
            'activeTab'      => htmlspecialchars($activeTab, ENT_QUOTES, 'UTF-8'),
            'csrf_token'     => $csrfToken
        ];

        $this->view('product/product', $data);
    }

   
}
?>