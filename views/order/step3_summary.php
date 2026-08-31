<?php
$cart = $data['cartData'][0] ?? [];

if (!is_array($cart)) { 
    $cart = []; 
}

$totalProductsPrice = (float)($data['cartData'][1] ?? 0);
$totalDiscount = (float)($data['cartData'][2] ?? 0);
$shippingPrice = (float)($data['postPrice'] ?? 0);

$addressInfo = $data['addressInfo'] ?? [];
if (!is_array($addressInfo)) {
    $addressInfo = [];
}

$shippingType = (int)($data['postType'] ?? 1);
$finalTotal = $totalProductsPrice + $shippingPrice - $totalDiscount;
?>
<div class="checkout-modern-container order-page-wrapper">

    <div class="checkout-grid-layout">
        
        <div class="checkout-left-column">
            
            <div class="checkout-back-nav">
                <a href="<?= URL ?>Order/address" class="link-back-navigation"><i class="fa-solid fa-chevron-left" aria-hidden="true"></i> Retour à la livraison</a>
            </div>

            <nav class="checkout-stepper-modern-bar">
                <ul class="stepper-steps-flex">
                    <li class="completed">Connexion</li>
                    <li class="completed">Livraison</li>
                    <li class="active" aria-current="step">Résumé</li>
                    <li>Paiement</li>
                </ul>
            </nav>

           