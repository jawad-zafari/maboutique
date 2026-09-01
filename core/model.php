<?php


class Model
{
    public static ?PDO $conn = null;
    public array $totalMenu = [];

    public function __construct()
    {
        $envPath = __DIR__ . '/env.php';

        if (file_exists($envPath)) {
            require_once $envPath;
        } else {
            die("Erreur : Le fichier de configuration 'core/env.php' est introuvable.");
        }

        if (!defined('DB_HOST') || !defined('DB_USER') || !defined('DB_PASS') || !defined('DB_NAME')) {
            die("Erreur : Les variables de connexion à la base de données sont incomplètes.");
        }

        $servername = DB_HOST;
        $username   = DB_USER;
        $password   = DB_PASS;
        $dbname     = DB_NAME;

        // Configuration des options PDO 
        $attr = [
            1002 => "SET NAMES utf8mb4", 
            PDO::ATTR_EMULATE_PREPARES => false,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ];

        // Modèle Singleton pour la connexion PDO
        if (self::$conn === null) {
            try {
                self::$conn = new PDO('mysql:host=' . $servername . ';dbname=' . $dbname, $username, $password, $attr);
            } catch (PDOException $e) {
                die("Erreur critique : Connexion à la base de données échouée.");
            }
        }
    }

    // Récupère les paramètres globaux du système depuis la base de données
    public static function getoption(): array
    {
        if (self::$conn === null) {
            new self();
        }
        $sql = "SELECT * FROM settings";
        $stmt = self::$conn->prepare($sql);
        $stmt->execute();
        $optionsList = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $options_new = [];

        foreach ($optionsList as $option) {
            $setting = $option['setting_key'];
            $value = $option['setting_value'];
            $options_new[$setting] = $value;
        }
        return $options_new;
    }

    // Calcule la remise sur le prix d'un produit
    public function calculateDiscount(float $price, float $discount): array
    {
        $price_discount = ($discount * $price) / 100;
        $price_total = $price - $price_discount;
        return [$price_discount, $price_total];
    }

    // Applique le calcul de remise à une liste de produits
    public function calculateProductsPrices(array $products): array
    {
        if (empty($products)) return [];
        foreach ($products as $key => $product) {
            $price = (float)($product['price'] ?? 0);
            $discount = (float)($product['discount_percent'] ?? 0);
            $prices = $this->calculateDiscount($price, $discount);

            $products[$key]['price_discount'] = $prices[0];
            $products[$key]['price_total'] = $prices[1];
        }
        return $products;
    }

    // Exécute une requête SELECT de manière sécurisée (Requêtes préparées)
    public function doSelect(string $sql, array $values = [], string $fetch = '', int $fetchStyle = PDO::FETCH_ASSOC): mixed
    {
        $stmt = self::$conn->prepare($sql);
        foreach ($values as $key => $value) {
            $stmt->bindValue($key + 1, $value);
        }
        $stmt->execute();

        if ($fetch === '') {
            $result = $stmt->fetchAll($fetchStyle);
        } else {
            $result = $stmt->fetch($fetchStyle);
        }
        return $result;
    }

    // Exécute une requête INSERT, UPDATE ou DELETE
    public function doQuery(string $sql, array $values = []): void
    {
        $stmt = self::$conn->prepare($sql);
        foreach ($values as $key => $value) {
            $stmt->bindValue($key + 1, $value);
        }
        $stmt->execute();
    }

    // Récupère le nombre total de favoris pour un utilisateur spécifique
    public function getFavoriteCount(int $userId): int
    {
        if ($userId <= 0) {
            return 0;
        }

        $sql = "SELECT COUNT(*) as total FROM favorites WHERE user_id = ?";
        $result = $this->doSelect($sql, [$userId], 'fetch');

        return isset($result['total']) ? (int)$result['total'] : 0;
    }

    // Redimensionne et sauvegarde une image
    public function create_thumbnail(string $file, string $pathToSave, int $w, int $h = 0, bool $crop = false): bool
    {
        if (!file_exists($file)) return false;

        $new_height = $h;
        $imageSize = getimagesize($file);
        if (!$imageSize) return false;
        
        $width = $imageSize[0];
        $height = $imageSize[1];
        if (!$width || !$height) return false;

        $r = $width / $height;

        if ($crop) {
            if ($width > $height) {
                $width = (int) round($width - ($width * abs($r - $w / $h)));
            } else {
                $height = (int) round($height - ($height * abs($r - $w / $h)));
            }
            $newwidth = $w;
            $newheight = $h;
        } else {
            if ($w / $h > $r) {
                $newwidth = (int) round($h * $r);
                $newheight = $h;
            } else {
                $newheight = (int) round($w / $r);
                $newwidth = $w;
            }
        }

        switch (strtolower($imageSize['mime'])) {
            case 'image/png': $src = imagecreatefrompng($file); break;
            case 'image/jpeg': $src = imagecreatefromjpeg($file); break;
            case 'image/gif': $src = imagecreatefromgif($file); break;
            case 'image/webp': $src = imagecreatefromwebp($file); break;
            default: return false;
        }

        if ($new_height !== 0) {
            $newheight = $new_height;
        }

        $dst = imagecreatetruecolor($newwidth, $newheight);

        if (strtolower($imageSize['mime']) === 'image/png' || strtolower($imageSize['mime']) === 'image/webp') {
            imagealphablending($dst, false);
            imagesavealpha($dst, true);
            $transparent = imagecolorallocatealpha($dst, 255, 255, 255, 127);
            imagefilledrectangle($dst, 0, 0, $newwidth, $newheight, $transparent);
        }

        imagecopyresampled($dst, $src, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);

        $ext = strtolower(pathinfo($pathToSave, PATHINFO_EXTENSION));
        switch ($ext) {
            case 'png': imagepng($dst, $pathToSave); break;
            case 'webp': imagewebp($dst, $pathToSave, 90); break;
            case 'gif': imagegif($dst, $pathToSave); break;
            default: imagejpeg($dst, $pathToSave, 95);
        }

        imagedestroy($src);
        imagedestroy($dst);

        return true;
    }

    // Gestion propre du démarrage de la session
    public static function sessionInit(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public static function sessionSet(string $name, mixed $value): void
    {
        self::sessionInit();
        $_SESSION[$name] = $value;
    }

    public static function sessionGet(string $name): mixed
    {
        self::sessionInit();
        return $_SESSION[$name] ?? false;
    }

    // Génère un identifiant unique pour le panier via un cookie
    public static function getCartCookie(): string
    {
        if (!empty($_COOKIE['cart'])) {
            return $_COOKIE['cart'];
        } else {
            $expire = time() + 7 * 24 * 3600;
            $value = bin2hex(random_bytes(16));

            setcookie('cart', $value, [
                'expires' => $expire,
                'path' => '/',
                'httponly' => true,
                'samesite' => 'Lax'
            ]);

            return $value;
        }
    }

    // Récupère les données complètes du panier de l'utilisateur
    public function getCart(): array
    {
        $sql = "SELECT c.quantity AS quantity, c.id AS cartRow, p.*, cl.title AS colorTitle, g.title AS garanteeTitle
         FROM cart_items c 
         LEFT JOIN products p ON c.product_id = p.id
         LEFT JOIN colors cl ON c.color_id = cl.id
         LEFT JOIN guarantees g ON c.guarantee_id = g.id
         WHERE c.session_cookie = ?";

        $cookie = self::getCartCookie();
        $result = $this->doSelect($sql, [$cookie]);
        $discountTotalAll = 0;
        $priceTotalAll = 0;

        foreach ($result as $key => $row) {
            $discount = (($row['discount_percent'] ?? 0) * ($row['price'] ?? 0)) / 100;
            $quantity = (int)($row['quantity'] ?? 1);
            $discountTotal = $quantity * $discount;
            $discountTotalAll += $discountTotal;
            $result[$key]['discountTotal'] = $discountTotal;

            $price = (float)($row['price'] ?? 0);
            $priceTotal = $price * $quantity;
            $priceTotalAll += $priceTotal;
        }

        return [$result, $priceTotalAll, $discountTotalAll];
    }

    // Calcule les frais de livraison (Méthodes locales uniquement)
    public function calculatePostPrice(int $cityId = 0): array
    {
        $sql = "SELECT id, price FROM shipping_methods";
        $methods = $this->doSelect($sql);
        
        $prices = [
            'express' => 5.00, 
            'standard' => 0.00
        ];

        if (is_array($methods)) {
            foreach ($methods as $method) {
                $id = (int)$method['id'];
                $price = (float)$method['price'];
                
                if ($id === 1) { 
                    $prices['express'] = $price;
                } elseif ($id === 2) { 
                    $prices['standard'] = $price;
                }
            }
        }

        return $prices;
    }

    public static function getCurrentDate(string $format = 'Y-m-d H:i:s'): string 
    {
        return date($format);
    }

    public static function formatDateForDB(string $dateStr, string $format = '/'): string
    {
        try {
            $cleanDate = str_replace('/', '-', $dateStr);
            $date = new DateTime($cleanDate);
            return $date->format('Y-m-d');
        } catch (Exception $e) {
            return date('Y-m-d');
        }
    }

    public static function formatDateForDisplay(string $dateStr, string $format = '/'): string
    {
        try {
            $cleanDate = str_replace('/', '-', $dateStr);
            $date = new DateTime($cleanDate);
            return $date->format('d' . $format . 'm' . $format . 'Y');
        } catch (Exception $e) {
            return date('d/m/Y');
        }
    }

    // Récupère l'arborescence des catégories (Menu)
    public function getMenu(int $parentId = 0): array
    {
        $data = [];
        $sql = "SELECT * FROM categories WHERE parent_id = ?";
        $result = $this->doSelect($sql, [$parentId]);
        foreach ($result as $row) {
            $children = $this->getMenu((int)$row['id']);
            if (!empty($children)) {
                $row['children'] = $children;
            }
            $data[] = $row;
        }
        return $data;
    }

    // Récupère le niveau d'accès de l'utilisateur (Pour le RBAC)
    public static function getUserLevel(): int
    {
        self::sessionInit();
        $userId = (int)self::sessionGet('userId');
        if ($userId <= 0) return 0;

        $sql = "SELECT role_id FROM users WHERE id = ?";
        $model_instance = new self();
        $userInfo = $model_instance->doSelect($sql, [$userId], 'fetch');
        return (int)($userInfo['role_id'] ?? 0);
    }
}
?>