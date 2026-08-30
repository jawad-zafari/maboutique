<?php

class Collection extends Controller
{
    public function __construct()
    {
        parent::__construct();
        // Initialisation de la session pour gérer les favoris et le panier
        Model::sessionInit(); 
    }

    //  Gère l'affichage des collections et des catégories
     
    public function index(string $type = 'latest', string $param1 = '1', string $param2 = '1'): void
    {
        // Validation stricte du type de collection
        $allowedTypes = ['latest', 'special', 'exclusive', 'mostviewed', 'category'];
        if (!in_array($type, $allowedTypes, true)) {
            $type = 'latest'; 
        }

        // Validation de la limite d'affichage
        $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 20;
        if (!in_array($limit, [20, 40, 60], true)) {
            $limit = 20; 
        }

        // Extraction et typage des filtres
        $inStock = isset($_GET['in_stock']) ? (int)$_GET['in_stock'] : 0;
        $orderType1 = isset($_GET['orderType1']) ? (int)$_GET['orderType1'] : 3;
        $orderType2 = isset($_GET['orderType2']) ? (int)$_GET['orderType2'] : 2;

        $categoryId = 0;
        $page = 1;

       
    }
}
?>