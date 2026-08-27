<?php

class Model
{
    public static ?PDO $conn = null;
    public $totalMenu = array();

    public function __construct()
    {
        // Chemin d'accès au fichier de configuration
        $envPath = __DIR__ . '/env.php';

        // Vérification de l'existence du fichier de configuration
        if (file_exists($envPath)) {
            require_once $envPath;
        } else {
            die("Erreur : Le fichier de configuration 'core/env.php' est introuvable. Veuillez le créer à partir de env.example.php.");
        }

        // Vérification des constantes requises pour la base de données
        if (!defined('DB_HOST') || !defined('DB_USER') || !defined('DB_PASS') || !defined('DB_NAME')) {
            die("Erreur : Les variables de connexion à la base de données sont incomplètes.");
        }

        $servername = DB_HOST;
        $username   = DB_USER;
        $password   = DB_PASS;
        $dbname     = DB_NAME;

        $initCommand = defined('Pdo\Mysql::ATTR_INIT_COMMAND') ? \Pdo\Mysql::ATTR_INIT_COMMAND : \PDO::MYSQL_ATTR_INIT_COMMAND;

        // Configuration des options PDO
        $attr = array(
            $initCommand => "SET NAMES utf8mb4",
            PDO::ATTR_EMULATE_PREPARES => false,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        );

        // Initialisation de la connexion à la base de données (Singleton)
        if (self::$conn === null) {
            try {
                self::$conn = new PDO('mysql:host=' . $servername . ';dbname=' . $dbname, $username, $password, $attr);
            } catch (PDOException $e) {
                die("Erreur critique : Connexion à la base de données échouée. Vérifiez vos identifiants.");
            }
        }
    }

    // Récupère les paramètres globaux du système depuis la base de données
    public static function getoption()
    {
        if (self::$conn === null) {
            new self();
        }
        $sql = "SELECT * FROM settings";
        $stmt = self::$conn->prepare($sql);
        $stmt->execute();
        $optionsList = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $options_new = array();

        foreach ($optionsList as $option) {
            $setting = $option['setting_key'];
            $value = $option['setting_value'];
            $options_new[$setting] = $value;
        }
        return $options_new;
    }

    public function calculateDiscount($price, $discount)
    {
        $price_discount = ($discount * $price) / 100;
        $price_total = $price - $price_discount;
        return array($price_discount, $price_total);
    }

    public function calculateProductsPrices($products)
    {
        if (!is_array($products)) return array();
        foreach ($products as $key => $product) {
            $price = $product['price'] ?? 0;
            $discount = $product['discount_percent'] ?? 0;
            $prices = $this->calculateDiscount($price, $discount);

            $products[$key]['price_discount'] = $prices[0];
            $products[$key]['price_total'] = $prices[1];
        }
        return $products;
    }

    // Exécute une requête SELECT de manière sécurisée
    public function doSelect($sql, $values = array(), $fetch = '', $fetchStyle = PDO::FETCH_ASSOC)
    {
        $stmt = self::$conn->prepare($sql);
        foreach ($values as $key => $value) {
            $stmt->bindValue($key + 1, $value);
        }
        $stmt->execute();

        if ($fetch == '') {
            $result = $stmt->fetchAll($fetchStyle);
        } else {
            $result = $stmt->fetch($fetchStyle);
        }
        return $result;
    }

    // Exécute une requête INSERT, UPDATE ou DELETE
    public function doQuery($sql, $values = array())
    {
        $stmt = self::$conn->prepare($sql);
        foreach ($values as $key => $value) {
            $stmt->bindValue($key + 1, $value);
        }
        $stmt->execute();
    }

    // Récupère le nombre total de favoris pour un utilisateur spécifique
    public function getFavoriteCount($userId)
    {
        if (!$userId) {
            return 0;
        }

        $sql = "SELECT COUNT(*) as total FROM favorites WHERE user_id = ?";
        $result = $this->doSelect($sql, [$userId], 1);

        return isset($result['total']) ? (int)$result['total'] : 0;
    }

    // Redimensionne et sauvegarde une image
    public function create_thumbnail($file, $pathToSave, $w, $h = '', $crop = false)
    {
        if (!file_exists($file)) return false;

        $new_height = $h;
        list($width, $height) = getimagesize($file);
        if (!$width || !$height) return false;

        $r = $width / $height;

        if ($crop) {
            if ($width > $height) {
                $width = (int) round($width - ($width * abs($r - $w / $h)));
            } else {
                $height = (int) round($height - ($height * abs($r - $w / $h)));
            }
            $newwidth = (int) $w;
            $newheight = (int) $h;
        } else {
            if ($w / $h > $r) {
                $newwidth = (int) round($h * $r);
                $newheight = (int) $h;
            } else {
                $newheight = (int) round($w / $r);
                $newwidth = (int) $w;
            }
        }

        $what = getimagesize($file);

        switch (strtolower($what['mime'])) {
            case 'image/png': $src = imagecreatefrompng($file); break;
            case 'image/jpeg': $src = imagecreatefromjpeg($file); break;
            case 'image/gif': $src = imagecreatefromgif($file); break;
            case 'image/webp': $src = imagecreatefromwebp($file); break;
            default: return false;
        }

        if ($new_height != '') {
            $newheight = (int) $new_height;
        }

        $dst = imagecreatetruecolor($newwidth, $newheight);

        if (strtolower($what['mime']) == 'image/png' || strtolower($what['mime']) == 'image/webp') {
            imagealphablending($dst, false);
            imagesavealpha($dst, true);
            $transparent = imagecolorallocatealpha($dst, 255, 255, 255, 127);
            imagefilledrectangle($dst, 0, 0, $newwidth, $newheight, $transparent);
        }

        imagecopyresampled($dst, $src, 0, 0, 0, 0, $newwidth, $newheight, (int) $width, (int) $height);

        $ext = strtolower(pathinfo($pathToSave, PATHINFO_EXTENSION));
        switch ($ext) {
            case 'png': imagepng($dst, $pathToSave); break;
            case 'webp': imagewebp($dst, $pathToSave, 90); break;
            case 'gif': imagegif($dst, $pathToSave); break;
            default: imagejpeg($dst, $pathToSave, 95);
        }

        unset($src);
        unset($dst);

        return true;
    }

    
}
?>