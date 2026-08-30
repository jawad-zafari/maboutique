<?php

class Search extends Controller
{
    public function __construct()
    {
        parent::__construct();
        // Initialisation de la session
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
        
        // Assainissement strict du mot-clé (Input Sanitization)
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

    public function doSearch(): void
    {
        // Définir le type de contenu et prévenir le crash JSON
        header('Content-Type: application/json; charset=utf-8');
        ob_clean();

        // SÉCURITÉ : Bloquer tout accès direct (GET)
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['error' => 'Méthode non autorisée. Veuillez utiliser POST.']);
            exit;
        }

        // Vérification obligatoire du jeton CSRF pour les filtres complexes
        $this->checkCsrfToken($_POST['csrf_token'] ?? '');

        // Extraction et nettoyage stricts des données autorisées
        $cleanData = [
            'keyword'      => isset($_POST['keyword']) ? trim(strip_tags($_POST['keyword'])) : '',
            'categoryId'   => isset($_POST['categoryId']) ? (int)$_POST['categoryId'] : 0,
            'in_stock'     => isset($_POST['in_stock']) ? (int)$_POST['in_stock'] : 0,
            'orderType1'   => isset($_POST['orderType1']) ? (int)$_POST['orderType1'] : 3,
            'orderType2'   => isset($_POST['orderType2']) ? (int)$_POST['orderType2'] : 2,
            'current_page' => isset($_POST['current_page']) ? (int)$_POST['current_page'] : 1,
            'limit'        => isset($_POST['limit']) ? (int)$_POST['limit'] : 20
        ];

        // Appel sécurisé au modèle
        $result = $this->model->doSearch($cleanData);
        
        echo json_encode($result);
        exit;
    }

    // Méthode pour l'auto-complétion (Suggestions en direct dans le Header)
    public function autoSuggest(): void
    {
        header('Content-Type: application/json; charset=utf-8');
        ob_clean();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['error' => 'Méthode non autorisée.']);
            exit;
        }

        // Assainissement de l'entrée
        $keyword = isset($_POST['keyword']) ? trim(strip_tags($_POST['keyword'])) : '';
        
        $results = $this->model->suggestProducts($keyword);
        
        echo json_encode($results);
        exit;
    }
}
?>