<?php
class Index extends Controller
{
    public function __construct()
    {
        parent::__construct();
        // Initialisation de la session globale
        Model::sessionInit();
    }

    public function index(): void
    {
        // Récupération des options depuis le modèle
        $options = Model::getoption();
        $limitSlider = (int)($options['limit_slider'] ?? 10);
        $durationSpecial = (int)($options['special_time'] ?? 0);

        // Gère la session et les droits d'accès
        $slider1           = $this->model->getMainSliders();
        $specialOffersRaw  = $this->model->getSpecialOffers();
        $exclusivesRaw     = $this->model->getExclusiveProducts();
        
        // Récupération des produits les plus vus et des derniers produits
        $mostViewedRaw     = $this->model->getMostViewedProducts($limitSlider);
        $latestProductsRaw = $this->model->getLatestProducts($limitSlider);
        
        // Actualités, Marques, Boutique TV
        $latestNews = $this->model->getLatestNews();
        $brands     = $this->model->getBrands();
        $tvSettings = $this->model->getTvSettings();


        // Préparation du tableau de données à envoyer à la Vue
        $data = [
            'slider1'         => $slider1, 
            'slider2_items'   => $slider2Items,
            'date_end'        => $dateEnd,
            'exclusives'      => $exclusives,
            'most_viewed'     => $mostViewed,
            'latest_products' => $latestProducts,
            'latest_news'     => $latestNews,
            'brands'          => $brands,
            'tv_settings'     => $tvSettings,
            // Génération du jeton pour protéger les actions AJAX sur la page d'accueil
            'csrf_token'      => $this->generateCsrfToken()
        ];

        // Affichage de la vue
        $this->view('index/index', $data);
    }
}
?>