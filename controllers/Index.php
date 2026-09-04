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

        // Calcul de la date de fin de l'offre spéciale
        $firstSpecialOffer = $specialOffersRaw[0] ?? [];
        $timeSpecial = (int)($firstSpecialOffer['special_offer_expires_at'] ?? 0);
        $timeEnd = $timeSpecial + $durationSpecial;
        
        // Définition du fuseau horaire pour l'affichage de la date
        date_default_timezone_set('Europe/Paris');
        $dateEnd = date('F d,Y H:i:s', $timeEnd);

        //Calcul des prix pour les produits récupérés
        $slider2Items   = $this->model->calculateProductsPrices($specialOffersRaw);
        $latestProducts = $this->model->calculateProductsPrices($latestProductsRaw);
        $exclusives     = $this->model->calculateProductsPrices($exclusivesRaw);
        $mostViewed     = $this->model->calculateProductsPrices($mostViewedRaw);

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