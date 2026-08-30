<?php

class Collection extends Controller
{
    public function __construct()
    {
        parent::__construct();
        // Initialisation de la session pour gérer les favoris et le panier
        Model::sessionInit(); 
    }

    
}
?>