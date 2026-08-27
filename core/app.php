<?php

class App
{
    protected $controller = 'Index';
    protected $method = 'index';
    protected $params = [];

    public function __construct()
    {
        // 1. Récupération et découpage de l'URL
        if (isset($_GET['url'])) {
            $url = $this->parseUrl($_GET['url']);

            if (!empty($url[0])) {
                $this->controller = ucfirst($url[0]);
                unset($url[0]);
            }

            if (isset($url[1])) {
                $this->method = $url[1];
                unset($url[1]);
            }

            // Réindexer le tableau des paramètres restants
            $this->params = array_values($url);
        }

        // Bloquer les caractères spéciaux 
        if (!preg_match('/^[a-zA-Z0-9_]+$/', $this->controller) || !preg_match('/^[a-zA-Z0-9_]+$/', $this->method)) {
            die("Erreur de sécurité : Caractères non autorisés détectés dans l'URL.");
        }

        
    }
}