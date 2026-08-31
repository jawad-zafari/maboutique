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

        // Requête préparée pour chercher si le produit exact existe déjà avec les mêmes options
        $sqlCheck = "SELECT id, quantity FROM cart_items WHERE product_id = ? AND session_cookie = ? AND color_id = ? AND guarantee_id = ?";
        $result = $this->doSelect($sqlCheck, [$productId, $cookie, $colorId, $guaranteeId]);

        if (!empty($result)) {
            // Si le produit existe déjà, on additionne la quantité
            $newQuantity = (int)$result[0]['quantity'] + $quantity;
            $cartItemId = (int)$result[0]['id'];
            
            $sqlUpdate = "UPDATE cart_items SET quantity = ? WHERE id = ?";
            $this->doQuery($sqlUpdate, [$newQuantity, $cartItemId]);
        } else {
            // Sinon, création d'une nouvelle ligne
            $sqlInsert = "INSERT INTO cart_items (session_cookie, product_id, quantity, color_id, guarantee_id) VALUES (?, ?, ?, ?, ?)";
            $this->doQuery($sqlInsert, [$cookie, $productId, $quantity, $colorId, $guaranteeId]);
        }

        return $this->getCartTotalCount();
    }

    
}
?>