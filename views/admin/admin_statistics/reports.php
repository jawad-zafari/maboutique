<div class="admin-container">
    
    <header class="admin-header">
        <div class="admin-breadcrumb" aria-label="Fil d'Ariane">
            <i class="fa-solid fa-chart-line" aria-hidden="true"></i> Statistiques et Rapports
        </div>
    </header>

    <form action="<?= URL ?>AdminStat/orderStatistics" method="post" id="formStatistics">
        
        <!-- Jeton CSRF sécurisé -->
        <input type="hidden" name="csrf_token" value="<?= $this->e($data['csrf_token'] ?? '') ?>">

        
    </form>
</div>

<script src="<?= URL ?>public/assets/js/admin_statistics.js" defer></script>