<?php

class AdminSetting extends Controller
{
    public function __construct()
    {
        parent::__construct();
        
        // Vérification stricte des droits d'accès
        Model::sessionInit();
        $userLevel = (int) Model::getUserLevel();
        if ($userLevel !== 1) {
            header('Location: ' . URL . 'AdminLogin/index');
            exit;
        }
    }

    public function index(): void
    {
        $settings = $this->model->getSettings();
        
        $data = [
            'option' => $settings,
            'csrf_token' => $this->generateCsrfToken()
        ];

        $this->view('admin/admin_setting/settings', $data);
    }

    public function update(): void
    {
        // Bloquer tout accès direct via GET
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('HTTP/1.1 405 Method Not Allowed');
            exit;
        }
            
        $this->checkCsrfToken($_POST['csrf_token'] ?? '');
        
        // Le contrôleur nettoie toutes les données (Protection XSS)
        $cleanData = [];
        foreach ($_POST as $key => $value) {
            $cleanData[$key] = is_string($value) ? trim(strip_tags($value)) : $value;
        }

        // Le contrôleur supprime le jeton CSRF avant d'envoyer les données au modèle
        if (isset($cleanData['csrf_token'])) {
            unset($cleanData['csrf_token']);
        }

        // Gestion spécifique de la case à cocher (checkbox)
        if (!isset($cleanData['maintenance_mode'])) {
            $cleanData['maintenance_mode'] = '0';
        }

        // Envoi d'un tableau de données parfaitement propre au modèle
        $this->model->saveSetting($cleanData);
        
        header('Location: ' . URL . 'AdminSetting/index?success=1');
        exit;
    }
}
?>