<?php


class Controller
{
    protected $model;

    public function __construct()
    {
        // Détecter automatiquement le nom du contrôleur enfant
        $controllerName = get_class($this);

        // Construire le nom du modèle correspondant
        $modelName = 'Model' . $controllerName;
        $modelPath = 'models/' . $modelName . '.php';

       
    }

   
   
}
?>