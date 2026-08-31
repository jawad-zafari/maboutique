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

    <div id="jsErrorMessage" class="alert-sticky danger alert-box-modern display-none-box" role="alert"></div>

    <div class="checkout-grid-layout">
        
        <div class="checkout-left-column">
            
            <div class="checkout-back-nav">
                <a href="<?= URL ?>Order/index" class="link-back-navigation"><i class="fa-solid fa-chevron-left" aria-hidden="true"></i> Retour à la connexion</a>
            </div>

            <nav class="checkout-stepper-modern-bar">
                <ul class="stepper-steps-flex">
                    <li class="completed">Connexion</li>
                    <li class="active" aria-current="step">Livraison</li>
                    <li>Paiement</li>
                </ul>
            </nav>

            <div class="checkout-section-card">
                <div class="section-title-flex">
                    <h3><i class="fa-solid fa-map-location-dot" aria-hidden="true"></i> 1. Choisissez une adresse de livraison</h3>
                    <button type="button" class="btn-add-address-trigger" id="btnToggleAddressForm" aria-expanded="false" aria-controls="inlineAddressFormContainer">
                        <i class="fa-solid fa-plus" aria-hidden="true"></i> Ajouter une adresse
                    </button>
                </div>

                <div class="address-cards-grid" id="addressListContainer">
                    <?php if(!empty($addresses)): foreach($addresses as $addr): ?>
                        <div class="modern-selection-card js-address-card" data-id="<?= (int)$addr['id'] ?>">
                            <div class="card-radio-select">
                                <input type="radio" name="selected_address" id="addr_<?= (int)$addr['id'] ?>" value="<?= (int)$addr['id'] ?>">
                                <!-- SÉCURITÉ : Échappement des données (XSS) -->
                                <label for="addr_<?= (int)$addr['id'] ?>"><strong><?= $this->e($addr['last_name'] ?? '') ?></strong></label>
                            </div>
                            <p class="address-text-summary"><?= $this->e($addr['address'] ?? '') ?></p>
                            <span class="address-city-zip"><?= $this->e($addr['city'] ?? $addr['city_name'] ?? '') ?> (<?= $this->e($addr['postal_code'] ?? '') ?>)</span>
                        </div>
                    <?php endforeach; else: ?>
                        <p class="empty-section-notice" id="emptyAddressNotice">Aucune adresse enregistrée. Veuillez en ajouter une ci-dessous.</p>
                    <?php endif; ?>
                </div>

                <div id="inlineAddressFormContainer" class="inline-address-form-wrapper display-none-box margin-top-md">
                    <div class="inline-form-card">
                        <h4 class="inline-form-title"><i class="fa-solid fa-thumbtack" aria-hidden="true"></i> Saisir une nouvelle adresse</h4>
                        
                        <form id="formAddAddress" method="post" autocomplete="off">
                            <div class="form-grid-double">
                                <div class="form-group"><label for="last_name">Nom complet du destinataire *</label><input type="text" id="last_name" name="last_name" class="form-control" required></div>
                                <div class="form-group"><label for="mobile">Téléphone mobile *</label><input type="text" id="mobile" name="mobile" class="form-control" dir="ltr" required></div>
                                <div class="form-group"><label for="province_name">Région / Province *</label><input type="text" id="province_name" name="province_name" class="form-control" required></div>
                                <div class="form-group"><label for="city_name">Ville *</label><input type="text" id="city_name" name="city_name" class="form-control" required></div>
                                <div class="form-group full-width"><label for="postal_code">Code postal *</label><input type="text" id="postal_code" name="postal_code" class="form-control" dir="ltr" required></div>
                                <div class="form-group full-width"><label for="address">Adresse complète *</label><textarea id="address" name="address" rows="2" class="form-control" required></textarea></div>
                            </div>
                            <div class="inline-form-actions margin-top-md flex-end-gap">
                                <button type="button" class="btn-account-secondary" id="btnCancelAddressInline">Annuler</button>
                                <button type="submit" class="btn-account-submit color-bg-success" id="btnSubmitAddress"><i class="fa-solid fa-save" aria-hidden="true"></i> Enregistrer l'adresse</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="checkout-section-card margin-top-md">
                <h3><i class="fa-solid fa-truck-ramp-box" aria-hidden="true"></i> 2. Choisissez le mode de livraison</h3>
                
                <div class="shipping-methods-grid">
                    <?php if(!empty($postTypes)): foreach($postTypes as $method): ?>
                        <div class="modern-selection-card js-shipping-card" data-id="<?= (int)$method['id'] ?>">
                            <div class="card-radio-select">
                                <input type="radio" name="selected_shipping" id="ship_<?= (int)$method['id'] ?>" value="<?= (int)$method['id'] ?>">
                                <label for="ship_<?= (int)$method['id'] ?>"><strong><?= $this->e($method['title'] ?? '') ?></strong></label>
                            </div>
                            <span class="shipping-price-tag font-weight-bold color-success">
                                <?= (isset($method['price']) && (float)$method['price'] > 0) ? number_format((float)$method['price'], 2, ',', ' ') . ' €' : 'Gratuit' ?>
                            </span>
                        </div>
                    <?php endforeach; endif; ?>
                </div>
            </div>

        </div>

       