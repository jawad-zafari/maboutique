<?php 

require 'views/header.php'; 
?>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jscolor/2.5.1/jscolor.min.js" defer></script>

<div id="mobileOverlay" class="admin-mobile-overlay"></div>

<div class="admin-layout-wrapper">
    
    <?php 
    // Initialisation sécurisée du menu actif
    $activeMenu = $activeMenu ?? '';
    ?>
    
    <aside class="admin-sidebar" id="adminSidebar" aria-label="Menu principal d'administration">
        
        <div class="sidebar-brand">
            <button id="sidebarToggleBtn" class="btn-sidebar-toggle" aria-label="Basculer le menu latéral" aria-expanded="false">
                <span></span>
                <span></span>
                <span></span>
            </button>
            <span class="nav-text font-weight-bold">Administration</span>
        </div>
        
        <div class="sidebar-scrollable">
            <nav class="sidebar-nav">
                <ul>
                    <li class="<?= ($activeMenu === 'dashboard') ? 'active' : '' ?>">
                        <a href="<?= URL ?>AdminDashboard/index" title="Tableau de bord" <?= ($activeMenu === 'dashboard') ? 'aria-current="page"' : '' ?>>
                            <i class="fa-solid fa-gauge-high" aria-hidden="true"></i> 
                            <span class="nav-text">Tableau de bord</span>
                        </a>
                    </li>
                    
                    <li class="<?= ($activeMenu === 'category') ? 'active' : '' ?>">
                        <a href="<?= URL ?>AdminCategory/index" title="Catégories" <?= ($activeMenu === 'category') ? 'aria-current="page"' : '' ?>>
                            <i class="fa-solid fa-folder-tree" aria-hidden="true"></i> 
                            <span class="nav-text">Catégories</span>
                        </a>
                    </li>
                    
                    <li class="<?= ($activeMenu === 'product') ? 'active' : '' ?>">
                        <a href="<?= URL ?>AdminProduct/index" title="Produits" <?= ($activeMenu === 'product') ? 'aria-current="page"' : '' ?>>
                            <i class="fa-solid fa-box-open" aria-hidden="true"></i> 
                            <span class="nav-text">Produits</span>
                        </a>
                    </li>
                    
                    <li class="<?= ($activeMenu === 'order') ? 'active' : '' ?>">
                        <a href="<?= URL ?>AdminOrder/index" title="Commandes" <?= ($activeMenu === 'order') ? 'aria-current="page"' : '' ?>>
                            <i class="fa-solid fa-shopping-cart" aria-hidden="true"></i> 
                            <span class="nav-text">Commandes</span>
                        </a>
                    </li>

                    <li class="<?= ($activeMenu === 'comment') ? 'active' : '' ?>">
                        <a href="<?= URL ?>AdminComment/index" title="Commentaires" <?= ($activeMenu === 'comment') ? 'aria-current="page"' : '' ?>>
                            <i class="fa-solid fa-comments" aria-hidden="true"></i> 
                            <span class="nav-text">Commentaires</span>
                        </a>
                    </li>

                    <li class="<?= ($activeMenu === 'question') ? 'active' : '' ?>">
                        <a href="<?= URL ?>AdminQuestion/index" title="Questions & Réponses" <?= ($activeMenu === 'question') ? 'aria-current="page"' : '' ?>>
                            <i class="fa-solid fa-circle-question" aria-hidden="true"></i> 
                            <span class="nav-text">Questions / Réponses</span>
                        </a>
                    </li>

                    <li class="<?= ($activeMenu === 'news') ? 'active' : '' ?>">
                        <a href="<?= URL ?>AdminNews/index" title="Actualités" <?= ($activeMenu === 'news') ? 'aria-current="page"' : '' ?>>
                            <i class="fa-solid fa-newspaper" aria-hidden="true"></i> 
                            <span class="nav-text">Actualités</span>
                        </a>
                    </li>

                    <li class="<?= ($activeMenu === 'slider') ? 'active' : '' ?>">
                        <a href="<?= URL ?>AdminSlider/index" title="Diaporama" <?= ($activeMenu === 'slider') ? 'aria-current="page"' : '' ?>>
                            <i class="fa-solid fa-images" aria-hidden="true"></i> 
                            <span class="nav-text">Diaporama</span>
                        </a>
                    </li>

                    <li class="<?= ($activeMenu === 'stat') ? 'active' : '' ?>">
                        <a href="<?= URL ?>AdminStat/index" title="Rapports & Stats" <?= ($activeMenu === 'stat') ? 'aria-current="page"' : '' ?>>
                            <i class="fa-solid fa-chart-line" aria-hidden="true"></i> 
                            <span class="nav-text">Statistiques</span>
                        </a>
                    </li>
                    
                    <?php 
                    // Vérification via la variable globale
                    if (isset($userLevel) && (int)$userLevel === 1): 
                    ?>
                    <li class="<?= ($activeMenu === 'user') ? 'active' : '' ?>">
                        <a href="<?= URL ?>AdminUser/index" title="Utilisateurs" <?= ($activeMenu === 'user') ? 'aria-current="page"' : '' ?>>
                            <i class="fa-solid fa-users" aria-hidden="true"></i> 
                            <span class="nav-text">Utilisateurs</span>
                        </a>
                    </li>
                    <?php endif; ?>
                    
                    <li class="<?= ($activeMenu === 'setting') ? 'active' : '' ?>">
                        <a href="<?= URL ?>AdminSetting/index" title="Paramètres" <?= ($activeMenu === 'setting') ? 'aria-current="page"' : '' ?>>
                            <i class="fa-solid fa-gears" aria-hidden="true"></i> 
                            <span class="nav-text">Paramètres</span>
                        </a>
                    </li>
                </ul>
            </nav>
            
            <div class="sidebar-footer">
                <a href="<?= URL ?>Index/index" class="btn-footer btn-home" title="Retour à l'accueil du site">
                    <i class="fa-solid fa-house" aria-hidden="true"></i> 
                    <span class="nav-text">Retour au site</span>
                </a>
                <a href="<?= URL ?>AdminLogin/logout" class="btn-footer btn-logout" title="Déconnexion de la session d'administration">
                    <i class="fa-solid fa-right-from-bracket" aria-hidden="true"></i> 
                    <span class="nav-text">Déconnexion</span>
                </a>
            </div>
        </div>
    </aside>

    <main class="admin-main-content">