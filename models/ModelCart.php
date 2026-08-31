<?php

class ModelCart extends Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getCartData(): array
    {
        // Appelle la méthode globale du modèle parent
        return parent::getCart();
    }

    
}
?>