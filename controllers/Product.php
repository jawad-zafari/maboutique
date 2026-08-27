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
        

    
    
}
?>