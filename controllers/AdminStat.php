<?php

class AdminStat extends Controller
{
    public function __construct()
    {
        parent::__construct();
        
        // Initialisation de la session pour vérifier le niveau d'accès
        Model::sessionInit();
        $level = (int) Model::getUserLevel();
        if ($level !== 1) {
            header('Location: ' . URL . 'AdminLogin/index');
            exit;
        }
    }

    public function index(): void
    {
        $currentYear = date('Y');
        
        $data = [
            'currentYear' => $currentYear,
            'csrf_token' => $this->generateCsrfToken()
        ];
        
        $this->view('admin/admin_statistics/reports', $data);
    }

    
}
?>