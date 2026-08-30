
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

    // 4. ACTIVATION D'UN CODE DE RÉDUCTION
    const btnActivateVoucher = document.getElementById('btnActivateVoucher');
    const voucherInput = document.getElementById('voucherCode');

    if (btnActivateVoucher && voucherInput) {
        btnActivateVoucher.addEventListener('click', async () => {
            const codeValue = voucherInput.value.trim();
            if (codeValue === "") {
                showAccountToast("Veuillez saisir un code de réduction valide.");
                return;
            }
            try {
                const params = new URLSearchParams();
                params.append('code', codeValue);
                params.append('csrf_token', csrfToken); // Sécurité CSRF

                const response = await fetch(`${baseUrl}Account/activateVoucher`, {
                    method: 'POST',
                    body: params
                });
                
                if (response.ok) {
                    sessionStorage.setItem('activeDashboardTab', 'tabVouchers');
                    window.location.reload(); 
                } else {
                    showAccountToast("Le code saisi est invalide ou expiré.");
                }
            } catch (error) { 
                console.error(error); 
                showAccountToast("Erreur de connexion au serveur.");
            }
        });
    }

    // 5. GESTION DES DÉTAILS DE LA COMMANDE (MODALE AJAX)
    const btnViewOrderList = document.querySelectorAll('.btn-view-order');
    const orderModal = document.getElementById('orderDetailsModal');
    const btnCloseOrderModal = document.getElementById('btnCloseOrderModal');
    
    const orderLoader = document.getElementById('orderDetailsLoader');
    const orderContent = document.getElementById('orderDetailsContent');

    if (btnViewOrderList.length > 0 && orderModal) {
        btnViewOrderList.forEach(btn => {
            btn.addEventListener('click', async function() {
                const orderId = this.getAttribute('data-id');
                
                orderModal.classList.add('active');
                orderLoader.style.display = 'block';
                orderContent.style.display = 'none';
                
                document.getElementById('modalOrderRef').textContent = '#' + orderId;

                try {
                    const response = await fetch(`${baseUrl}Account/getOrderDetails/${orderId}`);
                    const data = await response.json();

                    if (data.status === 'success') {
                        const order = data.order;
                        const products = data.products;

                        document.getElementById('modalOrderDate').textContent = order.created_date;
                        
                        // SÉCURITÉ : Forçage du typage entier
                        const isPaid = parseInt(order.is_paid, 10);
                        document.getElementById('modalOrderStatus').innerHTML = (isPaid === 1) ? '<span class="status-badge-paid">Payée</span>' : '<span class="status-badge-pending">En attente</span>';
                        
                        // Prévention XSS
                        document.getElementById('modalOrderAddress').textContent = order.address_data || 'Adresse non spécifiée';
                        
                        document.getElementById('modalOrderShipping').textContent = parseFloat(order.shipping_price) > 0 ? new Intl.NumberFormat('fr-FR').format(order.shipping_price) + ' €' : 'Gratuit';
                        document.getElementById('modalOrderTotal').textContent = new Intl.NumberFormat('fr-FR', { minimumFractionDigits: 2 }).format(order.total_amount) + ' €';

                        const productsContainer = document.getElementById('modalOrderProducts');
                        productsContainer.innerHTML = '';

                        if (products && products.length > 0) {
                            products.forEach(p => {
                                const qty = parseInt(p.quantity || 1, 10);
                                const price = parseFloat(p.price || 0);
                                const totalPrice = qty * price;
                                const imgSrc = `${baseUrl}public/images/products/${p.id}/product_220.jpg`;
                                
                                // Construction du DOM sécurisée contre les failles XSS (Remplacement des balises)
                                const safeTitle = p.title.replace(/</g, "&lt;").replace(/>/g, "&gt;");
                                
                                const html = `
                                <div class="modal-product-item">
                                    <div class="product-img">
                                        <img src="${imgSrc}" alt="" onerror="this.src='https://placehold.co/60x60/f8f9fa/adb5bd?text=Image'">
                                    </div>
                                    <div class="product-details">
                                        <div class="product-title">${safeTitle}</div>
                                        <div class="product-meta">Quantité : ${qty}</div>
                                    </div>
                                    <div class="product-price">${new Intl.NumberFormat('fr-FR', { minimumFractionDigits: 2 }).format(totalPrice)} €</div>
                                </div>`;
                                productsContainer.insertAdjacentHTML('beforeend', html);
                            });
                        } else {
                            productsContainer.innerHTML = '<p class="text-muted-color">Détails des articles indisponibles.</p>';
                        }

                        orderLoader.style.display = 'none';
                        orderContent.style.display = 'block';

                    } else {
                        showAccountToast(data.message);
                        orderModal.classList.remove('active');
                    }
                } catch (error) {
                    console.error("Erreur Fetch Order Details", error);
                    showAccountToast("Une erreur s'est produite lors de la récupération des données.");
                    orderModal.classList.remove('active');
                }
            });
        });
    }

    if (btnCloseOrderModal) {
        btnCloseOrderModal.addEventListener('click', () => { orderModal.classList.remove('active'); });
    }

    // 6. AJOUT AU PANIER DEPUIS LA PAGE FAVORIS (Requête AJAX Sécurisée)
    document.addEventListener('click', async (e) => {
        const btnAdd = e.target.closest('.btn-quick-add');
        if (btnAdd) {
            e.preventDefault();
            const productId = btnAdd.getAttribute('data-id');
            if (!productId) return;

            const originalIcon = btnAdd.innerHTML;
            btnAdd.innerHTML = '<i class="fa-solid fa-circle-notch fa-spin"></i>';
            btnAdd.disabled = true;

            try {
                // SÉCURITÉ : Recherche d'un jeton CSRF global
                let csrfForCart = csrfToken;
                if(!csrfForCart) {
                    const csrfInput = document.querySelector('input[name="csrf_token"]');
                    if(csrfInput) csrfForCart = csrfInput.value;
                }

                const formData = new URLSearchParams();
                formData.append('quantity', '1');
                formData.append('colorId', '0');
                formData.append('guaranteeId', '0');
                formData.append('csrf_token', csrfForCart);
                
                const response = await fetch(`${baseUrl}Cart/addToCart/${productId}`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: formData.toString()
                });

                const responseData = await response.json();

                if (response.ok && !responseData.error) {
                    const cartItems = responseData[0] || [];
                    
                    let totalCount = 0;
                    if(Array.isArray(cartItems)) {
                        cartItems.forEach(item => totalCount += parseInt(item.quantity || 1, 10));
                    } else if (responseData.totalItems) {
                        totalCount = parseInt(responseData.totalItems, 10);
                    }
                    
                    const badge = document.getElementById('navCartCounterBadge');
                    if (badge) {
                        badge.innerText = totalCount;
                        badge.style.display = 'inline-flex';
                        badge.style.transform = "scale(1.5)";
                        setTimeout(() => { badge.style.transform = "scale(1)"; }, 300);
                    }
                    
                    showAccountToast("Produit ajouté au panier avec succès !", "success");
                } else {
                    showAccountToast(responseData.message || "Erreur lors de l'ajout.", "danger");
                }
            } catch (error) {
                console.error("Erreur d'ajout :", error);
                showAccountToast("Erreur de connexion au serveur.", "danger");
            } finally {
                btnAdd.innerHTML = originalIcon;
                btnAdd.disabled = false;
            }
        }
    });

});