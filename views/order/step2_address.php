<?php
$cart = $data['cartData'][0] ?? [];
if (!is_array($cart)) { $cart = []; }

$totalProductsPrice = (float)($data['cartData'][1] ?? 0);
$totalDiscount = (float)($data['cartData'][2] ?? 0);
$addresses = $data['addresses'] ?? [];
$postTypes = $data['postType'] ?? [];
?>
<!-- Jeton CSRF sécurisé avec la méthode $this->e() -->
<div class="checkout-modern-container order-page-wrapper" id="step2AddressWrapper" data-csrf="<?= $this->e($data['csrf_token'] ?? '') ?>">

    <?php if(isset($_GET['error']) && $_GET['error'] === 'address_missing'): ?>
        <div class="alert-sticky danger alert-box-modern" role="alert">
            <i class="fa-solid fa-triangle-exclamation" aria-hidden="true"></i> Veuillez sélectionner une adresse et un mode de livraison avant de continuer.
        </div>
    <?php endif; ?>

    