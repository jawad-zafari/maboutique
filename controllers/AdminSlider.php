<?php

class AdminSlider extends Controller
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
        $sliders = $this->model->getslider();
        
        $data = [
            'slider' => $sliders,
            'editSlider' => null,
            'csrf_token' => $this->generateCsrfToken()
        ];
        
        $this->view('admin/admin_slider/slider', $data);
    }

    public function add(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('HTTP/1.1 405 Method Not Allowed');
            exit;
        }

        $this->checkCsrfToken($_POST['csrf_token'] ?? '');

        // Nettoyage des données POST pour éviter les injections et les entrées malveillantes
        $cleanData = [];
        foreach ($_POST as $key => $value) {
            if ($key === 'link') {
                $cleanData[$key] = filter_var(trim((string)$value), FILTER_SANITIZE_URL);
            } else {
                $cleanData[$key] = is_string($value) ? trim($value) : $value;
            }
        }

        $result = $this->model->addSlider($cleanData, $_FILES);
        if ($result) {
            header('Location: ' . URL . 'AdminSlider/index?success=add');
        } else {
            header('Location: ' . URL . 'AdminSlider/index?error=upload');
        }
        exit;
    }

    public function edit(int $id): void
    {
        $sliders = $this->model->getslider();
        $editSlider = $this->model->getSliderById($id);

        if (empty($editSlider)) {
            header('Location: ' . URL . 'AdminSlider/index');
            exit;
        }

        $data = [
            'slider' => $sliders,
            'editSlider' => $editSlider,
            'csrf_token' => $this->generateCsrfToken()
        ];

        $this->view('admin/admin_slider/slider', $data);
    }

   
}
?>