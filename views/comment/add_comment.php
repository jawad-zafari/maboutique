<?php

$productInfo   = $data['productInfo'] ?? [];
$commentInfo   = $data['commentInfo'] ?? [];
$params        = $data['params'] ?? [];
$commentParams = $data['commentParams'] ?? [];
$csrfToken     = $data['csrf_token'] ?? '';
$productId     = (int)($productInfo['id'] ?? 0);
?>

<div class="comment-container">
    <form method="post" action="<?= URL ?>AddComment/saveComment/<?= $productId ?>" class="comment-form" id="formComment">
        
        <!-- SÉCURITÉ : Jeton CSRF -->
        <input type="hidden" name="csrf_token" value="<?= $this->e($csrfToken) ?>">

        <aside class="product-summary">
            <img src="<?= URL ?>public/images/products/<?= $productId ?>/product_220.jpg" alt="<?= $this->e($productInfo['title'] ?? 'Produit') ?>" class="product-img" onerror="this.src='https://placehold.co/220x220/f1f3f5/3b5bdb?text=Produit'">
            <h3 class="product-title">Évaluez ce produit</h3>
            <p class="product-desc">Partagez votre expérience pour aider les autres utilisateurs.</p>
        </aside>

        <main class="evaluation-section">
            
            <h4 class="section-title"><i class="fa-solid fa-star-half-stroke" aria-hidden="true"></i> Vos critères d'évaluation</h4>
            
            <div class="sliders-grid">
                <?php foreach ($params as $row): 
                    $paramId = (int)($row['id'] ?? 0);
                    // Récupération de la note précédente ou 3 par défaut
                    $defaultValue = isset($commentParams[$paramId]) ? (int)$commentParams[$paramId] : 3;
                ?>
                    <div class="slider-group">
                        <label for="param_<?= $paramId ?>"><?= $this->e($row['title'] ?? '') ?></label>
                        <div class="range-wrapper">
                            <input type="range" 
                                   id="param_<?= $paramId ?>" 
                                   name="param<?= $paramId ?>" 
                                   min="1" max="5" step="1" 
                                   value="<?= $defaultValue ?>" 
                                   class="native-range"
                                   aria-valuemin="1"
                                   aria-valuemax="5"
                                   aria-valuenow="<?= $defaultValue ?>">
                            <span class="range-badge"><?= $defaultValue ?></span>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <hr class="divider">

            <h4 class="section-title"><i class="fa-solid fa-pen-to-square" aria-hidden="true"></i> Rédigez votre avis</h4>

            <div class="form-group">
                <input type="text" id="commentTitle" name="title" aria-label="Titre de votre avis" value="<?= $this->e($commentInfo['title'] ?? '') ?>" placeholder="Titre de votre avis (ex: Excellent produit !)" class="form-control" required>
            </div>
            
            <div class="form-row">
                <div class="form-group half">
                    <input type="text" id="commentPositive" name="positive" aria-label="Points forts du produit" value="<?= $this->e($commentInfo['positive_points'] ?? '') ?>" placeholder="Points forts (séparés par des virgules)" class="form-control input-success">
                </div>
                <div class="form-group half">
                    <input type="text" id="commentNegative" name="negative" aria-label="Points faibles du produit" value="<?= $this->e($commentInfo['negative_points'] ?? '') ?>" placeholder="Points faibles (séparés par des virgules)" class="form-control input-danger">
                </div>
            </div>

            <div class="form-group">
                <textarea id="commentContent" name="comment" aria-label="Détail de votre avis" placeholder="Expliquez pourquoi vous avez aimé ou non ce produit..." class="form-control textarea-large" required><?= $this->e($commentInfo['content'] ?? '') ?></textarea>
            </div>
            
           

        </main>
    </form>
</div>

<script src="<?= URL ?>public/assets/js/comment.js" defer></script>