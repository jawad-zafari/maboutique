<div class="admin-container">
    
    <header class="admin-header">
        <div class="admin-breadcrumb" aria-label="Fil d'Ariane de navigation">
            <i class="fa-solid fa-folder-tree" aria-hidden="true"></i> Gestion des Catégories
            <span class="separator">/</span>
            
            <?php if(!empty($data['parents'])): 
                $parents = array_reverse($data['parents']);
                foreach ($parents as $row): ?>
                    <a href="<?= URL ?>AdminCategory/showChildren/<?= (int)$row['id']; ?>">
                        <?= $this->e($row['title']); ?>
                    </a>
                    <span class="separator">&gt;</span>
            <?php endforeach; endif; ?>
            
            <span class="active-breadcrumb-item">
                <?= $this->e($data['categoryInfo']['title'] ?? 'Catégories Principales') ?>
            </span>
        </div>

        <div class="admin-actions">
            <a href="<?= URL ?>AdminCategory/addCategory/<?= (int)($data['categoryInfo']['id'] ?? 0) ?>" class="btn-admin-primary" aria-label="Ajouter une nouvelle catégorie">
                <i class="fa-solid fa-plus" aria-hidden="true"></i> Ajouter
            </a>
            <button type="button" id="btnDeleteCategory" class="btn-admin-danger" aria-label="Supprimer les catégories sélectionnées">
                <i class="fa-solid fa-trash-can" aria-hidden="true"></i> Supprimer
            </button>
        </div>
    </header>

   