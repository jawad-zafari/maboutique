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

        <ul class="account-nav-list">
            <li class="nav-item active" data-target="tabDashboard"><i class="fa-solid fa-chart-pie" aria-hidden="true"></i> Tableau de bord</li>
            <li class="nav-item" data-target="tabOrders"><i class="fa-solid fa-box-open" aria-hidden="true"></i> Mes commandes</li>
            
            <li class="nav-item">
                <a href="<?= URL ?>Account/favorites" class="account-sidebar-link">
                    <i class="fa-solid fa-heart" aria-hidden="true"></i> Mes favoris
                </a>
            </li>
            
            <li class="nav-item" data-target="tabVouchers"><i class="fa-solid fa-ticket" aria-hidden="true"></i> Mes réductions</li>
            <li class="nav-item" data-target="tabInfo"><i class="fa-solid fa-user-pen" aria-hidden="true"></i> Mes informations</li>
            <li class="nav-separator"></li>
            <li><button type="button" class="nav-link-danger" id="btnOpenLogoutModal"><i class="fa-solid fa-arrow-right-from-bracket" aria-hidden="true"></i> Déconnexion</button></li>
            <li><button type="button" class="nav-link-danger delete-account-btn" id="btnOpenDeleteModal"><i class="fa-solid fa-user-xmark" aria-hidden="true"></i> Supprimer le compte</button></li>
        </ul>
    </aside>

    