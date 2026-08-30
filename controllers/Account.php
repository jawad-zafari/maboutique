<?php
class Account extends Controller
{
    public function __construct()
    {
        parent::__construct();
        // Initialisation sécurisée de la session globale
        Model::sessionInit();
    }

  
    
}
?>