
document.addEventListener("DOMContentLoaded", () => {
    
    // Récupération sécurisée de l'URL de base et du jeton CSRF
    const baseTag = document.querySelector('base');
    const baseUrl = baseTag ? baseTag.getAttribute('href') : '/';

    const dashboardWrapper = document.getElementById('mainAccountDashboard');
    const csrfToken = dashboardWrapper ? dashboardWrapper.getAttribute('data-csrf') : '';

    // SYSTÈME DE NOTIFICATIONS (Toast)
    function showAccountToast(message, type = 'danger') {
        let toast = document.getElementById('accountToastNotification');
        if (!toast) {
            toast = document.createElement('div');
            toast.id = 'accountToastNotification';
            toast.className = 'toast-modern-notification';
            document.body.appendChild(toast);
        }

        toast.style.backgroundColor = (type === 'danger') ? '#e03131' : '#2b8a3e';
        toast.innerHTML = ''; 
        
        const icon = document.createElement('i');
        icon.className = (type === 'danger') ? 'fa-solid fa-circle-exclamation' : 'fa-solid fa-circle-check';
        icon.style.marginRight = '10px';
        
        // SÉCURITÉ : Anti-XSS via createTextNode au lieu de innerHTML
        const textNode = document.createTextNode(message); 

        toast.appendChild(icon);
        toast.appendChild(textNode);

        toast.classList.add('show');

        setTimeout(() => {
            toast.classList.remove('show');
        }, 3500);
    }

    // 1. GESTION DES ONGLETS DU DASHBOARD (Tabs)
    const navItems = document.querySelectorAll('.account-nav-list .nav-item[data-target]');
    const tabContents = document.querySelectorAll('.account-tab-content');

    function switchTab(targetId) {
        if (!targetId) return;
        navItems.forEach(nav => nav.classList.remove('active'));
        tabContents.forEach(content => content.classList.remove('active'));

        const activeNav = document.querySelector(`[data-target="${targetId}"]`);
        const targetContent = document.getElementById(targetId);

        if (activeNav && targetContent) {
            activeNav.classList.add('active');
            targetContent.classList.add('active');
            sessionStorage.setItem('activeDashboardTab', targetId);
        }
    }

    if (navItems.length > 0 && tabContents.length > 0) {
        navItems.forEach(item => {
            item.addEventListener('click', function() {
                // Masquer les alertes serveur existantes lors du changement d'onglet
                document.querySelectorAll('.alert-sticky').forEach(alert => {
                    alert.style.display = 'none';
                });
                switchTab(this.getAttribute('data-target'));
            });
        });
    }

    // Gestion du routage après rechargement (PRG Pattern)
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.has('success') || urlParams.has('error')) {
        switchTab('tabInfo');
        const cleanUrl = window.location.protocol + "//" + window.location.host + window.location.pathname;
        window.history.replaceState({ path: cleanUrl }, '', cleanUrl);
    } else {
        const savedTab = sessionStorage.getItem('activeDashboardTab');
        if (savedTab) switchTab(savedTab);
    }

    const btnViewAllOrders = document.getElementById('btnViewAllOrdersShortcut');
    if (btnViewAllOrders) {
        btnViewAllOrders.addEventListener('click', () => switchTab('tabOrders'));
    }

    // 2. AFFICHER/MASQUER LE MOT DE PASSE
    const togglePasswords = document.querySelectorAll('.toggle-password');
    togglePasswords.forEach(btn => {
        btn.addEventListener('click', function() {
            const input = this.previousElementSibling;
            const icon = this.querySelector('i');
            if(input.type === 'password') {
                input.type = 'text';
                icon.classList.replace('fa-eye', 'fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.replace('fa-eye-slash', 'fa-eye');
            }
        });
    });

    // 3. GESTION DES MODALES (Déconnexion & Suppression)
    const btnOpenDeleteModal = document.getElementById('btnOpenDeleteModal');
    const deleteModal = document.getElementById('deleteAccountModal');
    const btnCancelDelete = document.getElementById('btnCancelDelete');

    const btnOpenLogoutModal = document.getElementById('btnOpenLogoutModal');
    const logoutModal = document.getElementById('logoutModal');
    const btnCancelLogout = document.getElementById('btnCancelLogout');

    if (btnOpenDeleteModal && deleteModal) {
        btnOpenDeleteModal.addEventListener('click', () => { deleteModal.classList.add('active'); });
        if(btnCancelDelete) btnCancelDelete.addEventListener('click', () => { deleteModal.classList.remove('active'); });
    }

    if (btnOpenLogoutModal && logoutModal) {
        btnOpenLogoutModal.addEventListener('click', () => { logoutModal.classList.add('active'); });
        if(btnCancelLogout) btnCancelLogout.addEventListener('click', () => { logoutModal.classList.remove('active'); });
    }

   
});