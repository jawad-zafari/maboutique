<?php


class Error404 extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index(): void
    {
        // Indiquer explicitement au navigateur que la page n'existe pas
        header("HTTP/1.0 404 Not Found");
        
        // Appel de la vue personnalisée
        $this->view('errors/404');
    }
}
?>