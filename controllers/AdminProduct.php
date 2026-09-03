<?php

class AdminProduct extends Controller
{
    public function __construct()
    {
        parent::__construct();
        
        // Vérification stricte des droits d'accès
        Model::sessionInit();
        $level = (int) Model::getUserLevel();
        if ($level !== 1) {
            header('Location: ' . URL . 'AdminLogin/index');
            exit;
        }
    }

    public function index(): void
    {
        $data = [
            'product' => $this->model->getProduct(),
            'csrf_token' => $this->generateCsrfToken()
        ];
        $this->view('admin/admin_product/products', $data);
    }

    public function addProduct(int $productId = 0): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->checkCsrfToken($_POST['csrf_token'] ?? '');
            
            // Le contrôleur nettoie et valide toutes les entrées
            $cleanData = [
                'title'       => trim(strip_tags($_POST['title'] ?? '')),
                'description' => strip_tags(trim($_POST['description'] ?? ''), '<b><i><strong><em><u><ul><li><ol><p><br>'),
                'categoryId'  => (int)($_POST['categoryId'] ?? 0),
                'price'       => (int)($_POST['price'] ?? 0),
                'discount'    => (int)($_POST['discount'] ?? 0),
                'color'       => isset($_POST['color']) && is_array($_POST['color']) ? array_map('intval', $_POST['color']) : [],
                'garantee'    => isset($_POST['garantee']) && is_array($_POST['garantee']) ? array_map('intval', $_POST['garantee']) : []
            ];

            if (empty($cleanData['title'])) {
                header('Location: ' . URL . 'AdminProduct/addProduct/' . $productId);
                exit;
            }

            // On demande au modèle de sauvegarder et on récupère l'ID du produit
            $savedProductId = $this->model->addProductAction($productId, $cleanData);
            $targetId = $productId > 0 ? $productId : $savedProductId;

            // Le contrôleur s'occupe de l'upload des fichiers
            if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
                $this->handleProductImage($_FILES['image'], $targetId);
            }
            
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

    

    public function deleteProduct(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('HTTP/1.1 405 Method Not Allowed');
            exit('Méthode non autorisée');
        }

        $this->checkCsrfToken($_POST['csrf_token'] ?? '');
        
        $ids = $_POST['id'] ?? [];
        if (!empty($ids) && is_array($ids)) {
            $safeIds = array_map('intval', $ids);
            $this->model->deleteProduct($safeIds);
        }
        
        header('Location: ' . URL . 'AdminProduct/index?success=product_deleted');
        exit;
    }

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
           
        
        header('Location: ' . URL . 'AdminProduct/gallery/' . $productId . '?success=image_deleted');
        exit;
    }

    public function attributes(int $productId): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->checkCsrfToken($_POST['csrf_token'] ?? '');
            
           
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
           
        
        header('Location: ' . URL . 'AdminProduct/gallery/' . $productId . '?success=image_deleted');
        exit;
    }

    public function attributes(int $productId): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    $valId = (int)($_POST['x' . $attrId] ?? 0);
                    $cleanAttributes[(int)$attrId] = $valId;
                }
            }
            
            $this->model->editAttribute($productId, $cleanAttributes);
            header('Location: ' . URL . 'AdminProduct/attributes/' . $productId . '?success=1');
            exit;
        }

        $data = [
            'attr' => $this->model->getProductAttr($productId),
            'productInfo' => $this->model->getProductInfo($productId),
            'csrf_token' => $this->generateCsrfToken()
        ];
        $this->view('admin/admin_product/attributes', $data);
    }

    public function reviews(int $productId): void
    {
        $data = [
            'naghd' => $this->model->getReview($productId),
            'productInfo' => $this->model->getProductInfo($productId),
            'csrf_token' => $this->generateCsrfToken()
        ];
        $this->view('admin/admin_product/reviews', $data);
    }

    public function addReview(int $productId, int $reviewId = 0): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->checkCsrfToken($_POST['csrf_token'] ?? '');
            
            $title = trim(strip_tags($_POST['title'] ?? ''));
            $description = strip_tags(trim($_POST['description'] ?? ''), '<b><i><strong><em><u><ul><li><ol><p><br>');

            if (!empty($title) && !empty($description)) {
                $this->model->addReview($productId, $reviewId, $title, $description);
            }
            
            header('Location: ' . URL . 'AdminProduct/reviews/' . $productId);
            exit;
        }

        $data = [
            'productInfo' => $this->model->getProductInfo($productId),
            'naghdInfo' => $this->model->getReviewInfo($reviewId),
            'csrf_token' => $this->generateCsrfToken()
        ];
        $this->view('admin/admin_product/add_review', $data);
    }

    public function deleteReview(int $productId): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('HTTP/1.1 405 Method Not Allowed');
            exit('Méthode non autorisée');
        }

        $this->checkCsrfToken($_POST['csrf_token'] ?? '');
        
        $ids = $_POST['id'] ?? [];
        if (!empty($ids) && is_array($ids)) {
            $safeIds = array_map('intval', $ids);
            $this->model->deleteReview($safeIds);
        }
        
        header('Location: ' . URL . 'AdminProduct/reviews/' . $productId);
        exit;
    }
}
?>