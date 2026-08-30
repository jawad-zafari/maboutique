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

   