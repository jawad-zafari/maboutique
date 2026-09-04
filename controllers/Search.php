<?php

class Search extends Controller
{
    public function __construct()
    {
        parent::__construct();
        // Initialisation de la session globale
        Model::sessionInit();
    }

    // Page principale de recherche
    public function index(string $categoryId = '0'): void
    {
        // Cast en entier pour éviter les injections via l'URL
        $categoryIdInt = (int)$categoryId;
        
        $attributes = $this->model->getAttr($categoryIdInt);
        $attributesRight = $this->model->getAttrRight($categoryIdInt);
        $colors = $this->model->getColors();
        
        // Assainissement strict du mot-clé
        $keyword = isset($_POST['keyword']) ? trim(strip_tags($_POST['keyword'])) : '';
        
        $data = [
            'attr'       => $attributes, 
            'attrRight'  => $attributesRight, 
            'colors'     => $colors,
            'categoryId' => $categoryIdInt,
            'keyword'    => $keyword,
            // SÉCURITÉ : Jeton CSRF pour protéger les requêtes AJAX
            'csrf_token' => $this->generateCsrfToken() 
        ];
        
        $this->view('search/search', $data);
    }

    // Méthode appelée via AJAX pour traiter la recherche
    public function doSearch(): void
    {
        header('Content-Type: application/json; charset=utf-8');
        ob_clean();

        // SÉCURITÉ : Bloquer tout accès direct (GET)
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['error' => 'Méthode non autorisée. Veuillez utiliser POST.']);
            exit;
        }

        // Vérification obligatoire du jeton CSRF
        $this->checkCsrfToken($_POST['csrf_token'] ?? '');

        // Extraction et typage strict des données autorisées
        $keyword     = isset($_POST['keyword']) ? trim(strip_tags($_POST['keyword'])) : '';
        $categoryId  = isset($_POST['categoryId']) ? (int)$_POST['categoryId'] : 0;
        $inStock     = isset($_POST['in_stock']) ? (int)$_POST['in_stock'] : 0;
        $orderType1  = isset($_POST['orderType1']) ? (int)$_POST['orderType1'] : 3;
        $orderType2  = isset($_POST['orderType2']) ? (int)$_POST['orderType2'] : 2;
        
        $currentPage = isset($_POST['current_page']) ? (int)$_POST['current_page'] : 1;
        if ($currentPage < 1) { $currentPage = 1; }

        $limit = isset($_POST['limit']) ? (int)$_POST['limit'] : 20;
        if (!in_array($limit, [20, 40, 60])) { $limit = 20; }
        
        $offset = ($currentPage - 1) * $limit;

        // Appel sécurisé au modèle avec des paramètres strictement typés
        $searchResult = $this->model->doSearch($keyword, $categoryId, $inStock, $orderType1, $orderType2, $limit, $offset);
        
        $productsRaw = $searchResult[0] ?? [];
        $totalPages  = $searchResult[1] ?? 1;

        // Logique métier (Business Logic) : Le contrôleur calcule les prix finaux
        $products = $this->calculateProductsPrices($productsRaw);
        
        echo json_encode([$products, $totalPages]);
        exit;
    }

    // Méthode pour l'auto-complétion (Suggestions en direct)
    public function autoSuggest(): void
    {
        header('Content-Type: application/json; charset=utf-8');
        ob_clean();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['error' => 'Méthode non autorisée.']);
            exit;
        }

        $keyword = isset($_POST['keyword']) ? trim(strip_tags($_POST['keyword'])) : '';
        
        $resultsRaw = $this->model->suggestProducts($keyword);
        
        // Logique métier dans le contrôleur
        $results = $this->calculateProductsPrices($resultsRaw);
        
        echo json_encode($results);
        exit;
    }

   
}
?>