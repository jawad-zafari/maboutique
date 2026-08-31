<?php

$orderInfo = $data['orderInfo'] ?? [];
$orderId = (int)($orderInfo['id'] ?? 0);
?>
<div class="payment-container container-medium">

    <div class="payment-section-header">
        <h2><i class="fa-solid fa-building-columns" aria-hidden="true"></i> Informations de virement bancaire</h2>
    </div>

    <form action="<?= URL ?>Checkout/bankTransfer/<?= $orderId ?>" method="post">
        
        <input type="hidden" name="csrf_token" value="<?= $this->e($data['csrf_token'] ?? '') ?>">

        <div class="info-box box-transparent border-warning">
            <p class="text-muted margin-bottom-md">Veuillez saisir les détails de votre virement ou transfert par carte bancaire ci-dessous :</p>

            <div class="form-grid">
                <div class="form-group">
                    <label for="creditcard">Numéro de carte / Compte :</label>
                    <input id="creditcard" name="creditcard" type="text" class="form-control" dir="ltr" aria-required="true" required placeholder="Ex: 1234567890123456">
                </div>

                <div class="form-group">
                    <label for="bank">Banque émettrice :</label>
                    <input id="bank" name="bank" type="text" class="form-control" aria-required="true" required placeholder="Ex: BNP Paribas">
                </div>
            </div>

           