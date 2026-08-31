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

   
}
?>