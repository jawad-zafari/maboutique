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

    
}
?>