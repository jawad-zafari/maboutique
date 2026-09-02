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
            exit('Méthode non autorisée');
        }
            
        $this->checkCsrfToken($_POST['csrf_token'] ?? '');
        
        // Nettoyage des données POST pour éviter les injections et les entrées malveillantes
        $cleanData = [];
        foreach ($_POST as $key => $value) {
            $cleanData[$key] = is_string($value) ? trim($value) : $value;
        }

        // Gestion spécifique de la case à cocher (checkbox)
        if (!isset($cleanData['maintenance_mode'])) {
            $cleanData['maintenance_mode'] = '0';
        }

        // Envoi des données propres au modèle
        $this->model->saveSetting($cleanData);
        
        header('Location: ' . URL . 'AdminSetting/index?success=1');
        exit;
    }
}
?>