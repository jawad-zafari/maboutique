<div class="admin-container">
    
    <header class="admin-header">
        <div class="admin-breadcrumb" aria-label="Fil d'Ariane">
            <i class="fa-solid fa-comments" aria-hidden="true"></i> Modération des Commentaires
        </div>

        <div class="admin-actions">
            <select id="actionSelect" class="action-select-inline" aria-label="Sélectionner l'action de groupe">
                <option value="1">Approuver et Enregistrer</option>
                <option value="2">Rejeter (Masquer)</option>
                <option value="3">Supprimer définitivement</option>
            </select>
            <button type="button" class="btn-admin-primary" id="btnApplyAction" aria-label="Appliquer l'action aux commentaires sélectionnés">
                <i class="fa-solid fa-check-double" aria-hidden="true"></i> Appliquer l'action
            </button>
        </div>
    </header>

</div>

<script src="<?= URL ?>public/assets/js/admin_comment.js" defer></script>