<?php
class Index extends Controller
{
    public function __construct()
    {
        parent::__construct();
        // Initialisation de la session
        Model::sessionInit();
    }

    public function index()
    {
      

        // Affichage de la vue
        $this->view('index/index', $data);
    }
}
?>