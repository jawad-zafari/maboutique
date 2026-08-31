<?php

$orderInfo = $data['orderInfo'] ?? [];
$orderId = (int)($orderInfo['id'] ?? 0);
$csrfToken = $this->e($data['csrf_token'] ?? '');

//Protection contre l'injection d'objets via unserialize
$basketProducts = !empty($orderInfo['cart_data']) ? unserialize($orderInfo['cart_data'], ['allowed_classes' => false]) : [];

$isPaid = !empty($orderInfo['is_paid']); 
$paymentMethodId = (int)($orderInfo['payment_method_id'] ?? 1); 
    
// Logique conditionnelle pour lancer la simulation si c'est un paiement en ligne non effectué
$isPendingOnlinePayment = (!$isPaid && $paymentMethodId === 1);

// Calculs financiers sécurisés
$subTotal = 0;
if (is_array($basketProducts)) {
    foreach ($basketProducts as $item) {
        $qty = (int)($item['quantity'] ?? 1);
        $price = (float)($item['price'] ?? 0);
        $subTotal += ($price * $qty);
    }
}
$shippingCost = (float)($orderInfo['shipping_price'] ?? 0);
$totalPayable = (float)($orderInfo['total_amount'] ?? ($subTotal + $shippingCost));
?>
<div class="checkout-modern-container order-page-wrapper" data-csrf="<?= $csrfToken ?>">

    <?php if ($isPendingOnlinePayment): ?>
        
        <div id="mockPaymentLoader" data-order-id="<?= $orderId ?>" class="payment-processing-box text-center padding-xl margin-top-md">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Chargement...</span>
            </div>
            <h2 class="margin-top-md color-primary"><i class="fa-solid fa-lock" aria-hidden="true"></i> Traitement de votre paiement...</h2>
            <p class="text-muted-sm font-size-large margin-top-sm">Connexion sécurisée au serveur bancaire en cours.</p>
            <div class="alert-info-bank margin-top-md" style="display: inline-block; text-align: left;">
                <i class="fa-solid fa-triangle-exclamation" aria-hidden="true"></i> Veuillez ne pas fermer ni rafraîchir cette page.
            </div>
        </div>

       

    <?php else: ?>

        <div class="invoice-container margin-top-md">
            
            <?php if ($isPaid): ?>
                <div class="elegant-alert-banner success">
                    <div class="banner-icon"><i class="fa-solid fa-circle-check" aria-hidden="true"></i></div>
                    <div class="banner-content">
                        <h2>Commande validée avec succès !</h2>
                        <p>Merci pour votre achat. Votre paiement a bien été reçu (Réf transaction : <strong><?= $this->e($orderInfo['transaction_id_after'] ?? 'N/A') ?></strong>).</p>
                    </div>
                </div>
            <?php elseif ($paymentMethodId === 2): ?>
                <div class="elegant-alert-banner info">
                    <div class="banner-icon"><i class="fa-solid fa-clock" aria-hidden="true"></i></div>
                    <div class="banner-content">
                        <h2>Commande en attente de virement</h2>
                        <p>Votre commande est enregistrée. Elle sera expédiée dès réception de votre virement bancaire.</p>
                    </div>
                </div>
            <?php endif; ?>

           
            