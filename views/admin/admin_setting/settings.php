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

       
    </form>
</div>

<script src="<?= URL ?>public/assets/js/admin_setting.js" defer></script>