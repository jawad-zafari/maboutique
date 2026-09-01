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

    //  * Ajoute ou modifie un produit
    public function addProduct(int $productId = 0): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // VÉRIFICATION CSRF CENTRALISÉE
            $this->checkCsrfToken($_POST['csrf_token'] ?? '');
            
            $image = $_FILES['image'] ?? null;
            $this->model->addProductAction($_POST, $productId, $image);
            
            header('Location: ' . URL . 'AdminProduct/index?success=product_saved');
            exit;
        }

        $data = [
            'category' => $this->model->getCategory(),
            'color' => $this->model->getColor(),
            'garantee' => $this->model->getGarantee(),
            'productInfo' => $this->model->getProductInfo($productId),
            'csrf_token' => $this->generateCsrfToken()
        ];
        
        $this->view('admin/admin_product/add_product', $data);
    }

    //  Supprime un ou plusieurs produits
    public function deleteProduct(): void
    {
        // Bloquer tout accès direct via GET
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('HTTP/1.1 405 Method Not Allowed');
            exit('Méthode non autorisée');
        }

        $this->checkCsrfToken($_POST['csrf_token'] ?? '');
        
        $ids = $_POST['id'] ?? [];
        if (!empty($ids) && is_array($ids)) {
            $this->model->deleteProduct($ids);
        }
        
        header('Location: ' . URL . 'AdminProduct/index?success=product_deleted');
        exit;
    }

    // GESTION DE LA GALERIE D'IMAGES

    public function gallery(int $productId): void
    {
        $data = [
            'gallery' => $this->model->getGallery($productId),
            'productInfo' => $this->model->getProductInfo($productId),
            'csrf_token' => $this->generateCsrfToken()
        ];
        $this->view('admin/admin_product/gallery', $data);
    }

    public function addGallery(int $productId): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->checkCsrfToken($_POST['csrf_token'] ?? '');
            $this->model->addGallery($productId, $_FILES['images'] ?? null);
        }
        header('Location: ' . URL . 'AdminProduct/gallery/' . $productId . '?success=image_added');
        exit;
    }

    public function deleteGallery(int $productId): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('HTTP/1.1 405 Method Not Allowed');
            exit('Méthode non autorisée');
        }

        $this->checkCsrfToken($_POST['csrf_token'] ?? '');
        
        $ids = $_POST['id'] ?? [];
        if (!empty($ids) && is_array($ids)) {
            $this->model->deleteGallery($ids);
        }
        
        header('Location: ' . URL . 'AdminProduct/gallery/' . $productId . '?success=image_deleted');
        exit;
    }

   
}
?>