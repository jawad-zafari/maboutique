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

