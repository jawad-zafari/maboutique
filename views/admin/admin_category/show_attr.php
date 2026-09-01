<div class="admin-container">
    
    <header class="admin-header">
        <div class="admin-breadcrumb" aria-label="Fil d'Ariane des spécifications">
            <i class="fa-solid fa-list-check" aria-hidden="true"></i> Gestion des Attributs
            <span class="separator">/</span>
            
            <a href="<?= URL ?>AdminCategory/showAttributes/<?= (int)($data['categoryInfo']['id'] ?? 0) ?>">
                Catégorie : <?= $this->e($data['categoryInfo']['title'] ?? '') ?>
            </a>

            <?php if (!empty($data['attrInfo']['id'])): ?>
                <span class="separator">/</span>
                <span class="active-breadcrumb-item">Attribut : <?= $this->e($data['attrInfo']['title'] ?? '') ?></span>
            <?php endif; ?>
        </div>

        <div class="admin-actions">
            <a href="<?= URL ?>AdminCategory/addAttribute/<?= (int)($data['categoryInfo']['id'] ?? 0) ?>/<?= (int)($data['attrInfo']['id'] ?? 0) ?>" class="btn-admin-primary" aria-label="Ajouter un nouvel attribut">
                <i class="fa-solid fa-plus" aria-hidden="true"></i> Ajouter
            </a>
            <button type="button" id="btnDeleteAttribute" class="btn-admin-danger" aria-label="Supprimer les attributs cochés">
                <i class="fa-solid fa-trash-can" aria-hidden="true"></i> Supprimer
            </button>
            <a href="<?= URL ?>AdminCategory/showChildren/<?= (int)($data['categoryInfo']['parent_id'] ?? 0) ?>" class="btn-admin-back" aria-label="Retour aux catégories">
                <i class="fa-solid fa-arrow-left" aria-hidden="true"></i> Retour
            </a>
        </div>
    </header>

    