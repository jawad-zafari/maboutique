<?php

class Page extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    // Redirection sécurisée si l'utilisateur accède à /Page/ directement
    public function index(): void
    {
        header('Location: ' . URL . 'Index/index');
        exit;
    }

    // Page: Politique de confidentialité (RGPD)
    public function privacy(): void
    {
        $this->view('page/privacy');
    }

    // Page: Mentions légales (LCEN)
    public function legal(): void
    {
        $this->view('page/legal');
    }

    // Page: Conditions Générales de Vente (CGV) - Réservé aux achats
    public function cgv(): void
    {
        $this->view('page/cgv');
    }

    // Page: Conditions Générales d'Utilisation (CGU) - Pour tous les visiteurs
    public function cgu(): void
    {
        $this->view('page/cgu');
    }

    // Page: Conditions d'inscription (utilisé dans la page register)
    public function conditions(): void
    {
        $this->view('page/conditions');
    }

    // Page: Retours et remboursements
    public function returns(): void
    {
        $this->view('page/returns');
    }

    // Page: Foire Aux Questions
    public function faq(): void
    {
        $this->view('page/faq');
    }

    // Page: Comment passer une commande ?
    public function howToOrder(): void
    {
        $this->view('page/how_to_order');
    }

    // Page: Modes de livraison
    public function shipping(): void
    {
        $this->view('page/shipping');
    }

    // Page: Moyens de paiement
    public function paymentMethods(): void
    {
        $this->view('page/payment_methods');
    }
}
?>