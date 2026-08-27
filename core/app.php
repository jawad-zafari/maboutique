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

           
        }

       
}