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

    <main class="account-main-content">
        
        <section id="tabDashboard" class="account-tab-content active">
            <div class="dashboard-header">
                <h2>Bienvenue, <?= $this->e($userName) ?> !</h2>
                <p>Depuis votre <span>tableau de bord</span>, vous pouvez avoir un aperçu de vos activités récentes.</p>
            </div>

            <div class="dashboard-stats-row">
                <div class="stat-card">
                    <div class="stat-icon bg-blue-light"><i class="fa-solid fa-bag-shopping color-blue" aria-hidden="true"></i></div>
                    <div class="stat-details"><span class="stat-title">COMMANDES TOTALES</span><span class="stat-value"><?= $totalOrdersCount ?></span></div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon bg-green-light"><i class="fa-solid fa-wallet color-green" aria-hidden="true"></i></div>
                    <div class="stat-details"><span class="stat-title">TOTAL DES DÉPENSES</span><span class="stat-value"><?= number_format($totalSpentAmount, 2, ',', ' ') ?> €</span></div>
                </div>
            </div>

            <div class="recent-order-section">
                <div class="section-title-row">
                    <h3>Votre dernière commande</h3>
                    <button class="link-btn" id="btnViewAllOrdersShortcut">Voir tout</button>
                </div>
                <?php if($latestOrder): ?>
                    <div class="recent-order-card modern-order-card-box">
                        <div class="recent-order-flex-row">
                            <div>
                                <strong class="order-ref-highlight">Commande #<?= (int)$latestOrder['id'] ?></strong><br>
                                <span class="order-date-label">Passée le <?= $this->e($latestOrder['created_date'] ?? '') ?></span>
                            </div>
                            <div class="order-amount-large"><?= number_format((float)($latestOrder['total_amount'] ?? 0), 2, ',', ' ') ?> €</div>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="recent-order-card empty-order">
                        <div class="order-info"><span class="order-number">Aucune commande récente.</span></div>
                    </div>
                <?php endif; ?>
            </div>
        </section>

        <section id="tabOrders" class="account-tab-content">
            <div class="dashboard-header">
                <h2>Mes commandes</h2>
                <p>Consultez l'historique et les détails de vos achats.</p>
            </div>
            <div class="account-table-wrapper">
                <table class="account-table" aria-label="Historique de vos commandes">
                    <thead>
                        <tr>
                            <th scope="col">Référence</th>
                            <th scope="col">Date</th>
                            <th scope="col">Montant</th>
                            <th scope="col">Statut</th>
                            <th scope="col" class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(!empty($ordersList)): foreach($ordersList as $order): ?>
                        <tr>
                            <td><strong>#<?= (int)$order['id'] ?></strong></td>
                            <td><?= $this->e($order['created_date'] ?? '') ?></td>
                            <td><strong><?= number_format((float)($order['total_amount'] ?? 0), 2, ',', ' ') ?> €</strong></td>
                            <td>
                                <?php if(isset($order['is_paid']) && (int)$order['is_paid'] === 1): ?>
                                    <span class="status-badge-paid">Payée</span>
                                <?php else: ?>
                                    <span class="status-badge-pending">En attente</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-center">
                                <button type="button" class="btn-view-order" data-id="<?= (int)$order['id'] ?>" title="Voir les détails de la commande" aria-label="Détails de la commande">
                                    <i class="fa-solid fa-eye" aria-hidden="true"></i> Détails
                                </button>
                            </td>
                        </tr>
                        <?php endforeach; else: ?>
                        <tr><td colspan="5" class="text-empty-table text-center">Aucune commande récente.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </section>

        <section id="tabVouchers" class="account-tab-content">
            <div class="dashboard-header">
                <h2>Bons de réduction</h2>
                <p>Activez et gérez vos codes promotionnels.</p>
            </div>
            <div class="voucher-activation-box">
                <label for="voucherCode">Activer un bon de réduction :</label>
                <div class="input-group-flex">
                    <input type="text" id="voucherCode" class="form-control" placeholder="Saisir un code...">
                    <button type="button" id="btnActivateVoucher" class="btn-account-submit">Activer</button>
                </div>
            </div>
        </section>

       