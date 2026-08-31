<?php

class Cart extends Controller
{
    public function __construct()
    {
        parent::__construct();
        // Initialisation de la session pour la gestion sécurisée du panier
        Model::sessionInit();
    }

    
}
?>