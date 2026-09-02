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

    public function orderStatistics(): void
    {
        // Vérification stricte de la méthode POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('HTTP/1.1 405 Method Not Allowed');
            exit;
        }
            
        $this->checkCsrfToken($_POST['csrf_token'] ?? '');
        
        // Extraction et validation des dates depuis le formulaire
        $y1 = (int)($_POST['year1'] ?? 0);
        $m1 = (int)($_POST['month1'] ?? 0);
        $d1 = (int)($_POST['day1'] ?? 0);
        
        $y2 = (int)($_POST['year2'] ?? 0);
        $m2 = (int)($_POST['month2'] ?? 0);
        $d2 = (int)($_POST['day2'] ?? 0);

        if ($y1 === 0 || $m1 === 0 || $d1 === 0) {
            $startDate = '';
            $endDate = '';
        } else {
            // Formatage standard des dates (YYYY-MM-DD)
            $startDate = sprintf('%04d-%02d-%02d', $y1, $m1, $d1);
            $endDate = sprintf('%04d-%02d-%02d', $y2, $m2, $d2);
        }
        
        // Envoi des données propres au modèle
        $statistics = $this->model->order($startDate, $endDate);
        
        $data = [
            'stat' => $statistics,
            'csrf_token' => $this->generateCsrfToken()
        ];
        
        $this->view('admin/admin_statistics/results', $data);
    }
}
?>