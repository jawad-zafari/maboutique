<?php 
//  Récupération et protection des données transmises par le contrôleur
$userInfo         = $userInfo ?? [];
$userName         = !empty($userInfo['username']) ? $userInfo['username'] : (!empty($userInfo['last_name']) ? $userInfo['last_name'] : 'Utilisateur');
$userEmail        = $userInfo['email'] ?? '';
$userInitial      = strtoupper(mb_substr($userName, 0, 1, 'UTF-8'));

$ordersList       = $orders ?? [];
$totalOrdersCount = (int)($totalOrdersCount ?? count($ordersList));
$totalSpentAmount = (float)($totalSpent ?? 0);
$latestOrder      = $latestOrder ?? ($ordersList[0] ?? null);
?>

<!-- Stockage du jeton CSRF pour les requêtes AJAX -->
<div class="account-dashboard-wrapper" id="mainAccountDashboard" data-csrf="<?= $this->e($csrf_token ?? '') ?>">
    
    <aside class="account-sidebar">
        <div class="user-profile-summary">
            <!-- SÉCURITÉ : Échappement des variables dynamiques contre les failles XSS -->
            <div class="user-avatar"><?= $this->e($userInitial) ?></div>
            <h3 class="user-name"><?= $this->e($userName) ?></h3>
            <span class="user-since">Client(e) de la boutique</span>
        </div>

       