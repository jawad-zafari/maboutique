<div class="admin-container">
    
    <header class="admin-header">
        <div class="admin-breadcrumb" aria-label="Fil d'Ariane">
            <i class="fa-solid fa-users" aria-hidden="true"></i> Gestion des Utilisateurs
        </div>
        
        <div class="admin-actions">
            <label for="actionSelect" class="sr-only">Choisir une action en masse</label>
            <select id="actionSelect" class="form-control action-select-inline" aria-label="Action de groupe pour les utilisateurs">
                <option value="1">Promouvoir Administrateur (Niveau 1)</option>
                <option value="2">Modifier en Employé (Niveau 2)</option>
                <option value="3">Définir comme Utilisateur Normal (Niveau 3)</option>
                <option value="4">Supprimer le compte définitivement</option>
            </select>
            <button type="button" class="btn-admin-primary" id="btnApplyUserAction" aria-label="Appliquer l'action aux comptes sélectionnés">
                <i class="fa-solid fa-bolt" aria-hidden="true"></i> Appliquer
            </button>
        </div>
    </header>

   