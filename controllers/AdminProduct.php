<?php

class AdminProduct extends Controller
{
    public function __construct()
    {
        parent::__construct();
        
        // Vérification stricte des droits d'accès (Seul l'admin a accès)
        Model::sessionInit();
        $level = (int) Model::getUserLevel();
        if ($level !== 1) {
            header('Location: ' . URL . 'AdminLogin/index');
            exit;
        }
    }

    
    // Affiche la liste des produits
     
    public function index(): void
    {
        $data = [
            'product' => $this->model->getProduct(),
            'csrf_token' => $this->generateCsrfToken()
        ];
        $this->view('admin/admin_product/products', $data);
    }

    
}
?>