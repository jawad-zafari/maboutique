<?php
class Index extends Controller
{
    public function __construct()
    {
        parent::__construct();
        // Initialisation de la session
        Model::sessionInit();
    }

    
}
?>