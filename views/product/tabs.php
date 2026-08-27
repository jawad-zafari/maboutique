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

   