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

    public function deleteCartItem(int $cartRowId): void
    {
        $cookie = parent::getCartCookie();

        // vérifie que la ligne appartient bien au visiteur actuel
        $sql = "DELETE FROM cart_items WHERE id = ? AND session_cookie = ?";
        $this->doQuery($sql, [$cartRowId, $cookie]);
    }

    // le modèle reçoit des variables propres, pas de $_POST
    public function updateCartItem(int $cartRowId, int $quantity): void
    {
        $cookie = parent::getCartCookie();

        // Sécurité supplémentaire au niveau du modèle
        if ($quantity > 0 && $cartRowId > 0) {
            $sql = "UPDATE cart_items SET quantity = ? WHERE id = ? AND session_cookie = ?";
            $this->doQuery($sql, [$quantity, $cartRowId, $cookie]);
        }
    }

    public function addToCart(int $productId, int $quantity = 1, int $colorId = 0, int $guaranteeId = 0): int
    {
        $cookie = parent::getCartCookie();

        // Vérification de l'existence du produit en base de données
        // Empêche un attaquant d'ajouter un produit fantôme via l'inspecteur d'éléments
        $sqlCheckProduct = "SELECT id FROM products WHERE id = ?";
        $productExists = $this->doSelect($sqlCheckProduct, [$productId], 'fetch');
        
        if (empty($productExists)) {
            // Si le produit n'existe pas, on retourne simplement le total actuel
            return $this->getCartTotalCount();
        }

       
    }
}
?>