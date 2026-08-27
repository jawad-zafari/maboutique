<?php
// Récupération sécurisée avec compatibilité des clés transmises par le contrôleur
$reviews = $data['reviews'] ?? $data['expertReviews'] ?? []; 
$specs = $data['specs'] ?? $data['specifications'] ?? []; 
$comments = $data['comments'] ?? []; 
$paramNames = $data['comment_params'] ?? $data['commentParamNames'] ?? []; 
$paramScores = $data['comment_scores'] ?? $data['commentParamScores'] ?? [];
$questions = $data['questions'] ?? []; 
$answers = $data['answers'] ?? [];

$productInfo = $data['productInfo'] ?? [];
$productId = (int)($productInfo['id'] ?? 0);
$activeTab = $data['activeTab'] ?? 'reviews';
?>

<div class="product-tabs-wrapper">
    
    <nav class="tabs-nav" role="tablist" aria-label="Informations détaillées du produit">
        <button type="button" role="tab" aria-selected="<?= $activeTab === 'expert' ? 'true' : 'false' ?>" aria-controls="tab-expert" id="btn-tab-expert" class="btn-tab <?= $activeTab === 'expert' ? 'active' : '' ?>" data-target="tab-expert">
            <i class="fa-solid fa-pen-nib" aria-hidden="true"></i> Évaluations d'experts
        </button>
        <button type="button" role="tab" aria-selected="<?= $activeTab === 'specs' ? 'true' : 'false' ?>" aria-controls="tab-specs" id="btn-tab-specs" class="btn-tab <?= $activeTab === 'specs' ? 'active' : '' ?>" data-target="tab-specs">
            <i class="fa-solid fa-list-check" aria-hidden="true"></i> Spécifications techniques
        </button>
        <button type="button" role="tab" aria-selected="<?= $activeTab === 'reviews' ? 'true' : 'false' ?>" aria-controls="tab-reviews" id="btn-tab-reviews" class="btn-tab <?= $activeTab === 'reviews' ? 'active' : '' ?>" data-target="tab-reviews">
            <i class="fa-solid fa-comments" aria-hidden="true"></i> Avis
        </button>
        <button type="button" role="tab" aria-selected="<?= $activeTab === 'qa' ? 'true' : 'false' ?>" aria-controls="tab-qa" id="btn-tab-qa" class="btn-tab <?= $activeTab === 'qa' ? 'active' : '' ?>" data-target="tab-qa">
            <i class="fa-solid fa-circle-question" aria-hidden="true"></i> Questions & Réponses
        </button>
    </nav>

    <div class="tab-content-wrapper">
        
        <div id="tab-expert" class="tab-pane <?= $activeTab === 'expert' ? 'active' : '' ?>" role="tabpanel" aria-labelledby="btn-tab-expert">
            <div class="expert-reviews-container">
                <?php if (!empty($reviews)): foreach ($reviews as $rev): ?>
                    <div class="expert-review-card">
                        <h4><?= htmlspecialchars($rev['title'] ?? 'Avis Expert', ENT_QUOTES, 'UTF-8') ?></h4>
                        <p><?= htmlspecialchars($rev['description'] ?? $rev['content'] ?? '', ENT_QUOTES, 'UTF-8') ?></p>
                    </div>
                <?php endforeach; else: ?>
                    <p class="empty-text">Aucune évaluation d'expert disponible pour ce produit.</p>
                <?php endif; ?>
            </div>
        </div>

        <div id="tab-specs" class="tab-pane <?= $activeTab === 'specs' ? 'active' : '' ?>" role="tabpanel" aria-labelledby="btn-tab-specs">
            <div class="specs-table-wrapper">
                <?php if (!empty($specs)): ?>
                    <table class="specs-table" aria-label="Caractéristiques techniques du produit">
                        <tbody>
                            <?php foreach ($specs as $spec): ?>
                                <tr>
                                    <th scope="row"><?= htmlspecialchars($spec['title'] ?? '', ENT_QUOTES, 'UTF-8') ?></th>
                                    <td><?= htmlspecialchars($spec['value'] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p class="empty-text">Aucune spécification technique disponible.</p>
                <?php endif; ?>
            </div>
        </div>

        