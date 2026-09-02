<?php
$option = $data['option'] ?? [];
?>
<div class="admin-container">
    
    <header class="admin-header">
        <div class="admin-breadcrumb" aria-label="Fil d'Ariane">
            <i class="fa-solid fa-gears" aria-hidden="true"></i> Paramètres généraux du site
        </div>
    </header>

    <?php if (isset($_GET['success']) && $_GET['success'] == 1): ?>
        <div class="alert-sticky success" role="alert" aria-live="polite">
            <i class="fa-solid fa-circle-check" aria-hidden="true"></i> La configuration a été mise à jour avec succès !
        </div>
    <?php endif; ?>

    <form action="<?= URL ?>AdminSetting/update" method="post" id="formSettingsManage">
        
        <!-- Jeton CSRF sécurisé avec e() -->
        <input type="hidden" name="csrf_token" value="<?= $this->e($data['csrf_token'] ?? '') ?>">

        <div class="settings-layout-grid">
            
            <div class="admin-form-box">
                <h3 class="settings-section-title">
                    <i class="fa-solid fa-globe" aria-hidden="true"></i> Général & Maintenance
                </h3>
                
                <div class="form-group mb-25">
                    <label class="toggle-switch" for="maintenanceModeCheckbox">
                        <input type="checkbox" id="maintenanceModeCheckbox" name="maintenance_mode" value="1" <?= (isset($option['maintenance_mode']) && $option['maintenance_mode'] == '1') ? 'checked' : '' ?> aria-describedby="maintenanceHelpText">
                        <span class="slider-toggle" aria-hidden="true"></span>
                        <span class="toggle-label text-danger">Activer le mode maintenance</span>
                    </label>
                    <span id="maintenanceHelpText" class="help-text">Bloque l'accès public au site pour vos clients pendant vos mises à jour techniques.</span>
                </div>

                <div class="form-group">
                    <label for="limitSlider">Limite de produits (Sliders d'accueil) * :</label>
                    <!-- Injection des données sécurisées via la méthode e() -->
                    <input type="number" id="limitSlider" name="limit_slider" class="form-control" value="<?= $this->e($option['limit_slider'] ?? '10') ?>" required aria-required="true">
                </div>
            </div>

            <div class="admin-form-box">
                <h3 class="settings-section-title">
                    <i class="fa-solid fa-building" aria-hidden="true"></i> Informations de Contact
                </h3>
                
                <div class="form-group">
                    <label for="contactEmail">E-mail de support * :</label>
                    <input type="email" id="contactEmail" name="email" class="form-control" value="<?= $this->e($option['email'] ?? '') ?>" required aria-required="true" autocomplete="email" dir="ltr">
                </div>
                <div class="form-group">
                    <label for="contactTel">Téléphone * :</label>
                    <input type="text" id="contactTel" name="tel" class="form-control" value="<?= $this->e($option['tel'] ?? '') ?>" required aria-required="true" autocomplete="tel" dir="ltr">
                </div>
                <div class="form-group">
                    <label for="storeAddress">Adresse physique (Facturation) :</label>
                    <textarea id="storeAddress" name="store_address" class="form-control" rows="2" placeholder="Sera affichée sur les factures clients..."><?= $this->e($option['store_address'] ?? '') ?></textarea>
                </div>
            </div>


    </form>
</div>

<script src="<?= URL ?>public/assets/js/admin_setting.js" defer></script>