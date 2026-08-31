
document.addEventListener("DOMContentLoaded", () => {
    
    const cartOverlay = document.getElementById('cartOverlay');
    const cartSidebar = document.getElementById('cartSidebar');
    const closeCartBtn = document.getElementById('closeCartBtn');
    const headerCartBtns = document.querySelectorAll('.cart-btn'); 

    const baseTag = document.querySelector('base');
    const baseUrl = baseTag ? baseTag.getAttribute('href') : '/';

    // RÉCUPÉRATION SÉCURISÉE DU JETON CSRF
    function getCsrfToken() {
        const mainCart = document.getElementById('mainCart');
        if (mainCart && mainCart.hasAttribute('data-csrf')) {
            return mainCart.getAttribute('data-csrf');
        }
        const csrfInput = document.querySelector('input[name="csrf_token"]');
        return csrfInput ? csrfInput.value : '';
    }

    // GESTION DU PANIER LATÉRAL (Offcanvas)
    function openCart() {
        if(cartSidebar && cartOverlay) {
            cartSidebar.classList.add('active');
            cartOverlay.classList.add('active');
        }
    }

    function closeCart() {
        if(cartSidebar && cartOverlay) {
            cartSidebar.classList.remove('active');
            cartOverlay.classList.remove('active');
        }
    }

    headerCartBtns.forEach(btn => {
        btn.addEventListener('click', (e) => {
            e.preventDefault(); 
            openCart();
        });
    });

    if(closeCartBtn) closeCartBtn.addEventListener('click', closeCart);
    if(cartOverlay) cartOverlay.addEventListener('click', closeCart);

    // SYSTÈME DE NOTIFICATION TOAST (Sécurisé Anti-XSS)
    function showToastNotification(message, type = 'success') {
        let toast = document.getElementById('cartToastNotification');
        if (!toast) {
            toast = document.createElement('div');
            toast.id = 'cartToastNotification';
            document.body.appendChild(toast);
        }
        
        toast.className = 'toast-notification';
        toast.innerHTML = ''; // Nettoyage
        
        const icon = document.createElement('i');
        if (type === 'danger') {
            toast.classList.add('toast-danger');
            icon.className = 'fa-solid fa-trash-can';
        } else if (type === 'error') {
            toast.classList.add('toast-danger'); 
            icon.className = 'fa-solid fa-triangle-exclamation';
        } else {
            toast.classList.add('toast-success');
            icon.className = 'fa-solid fa-circle-check';
        }
        
        // SÉCURITÉ : Injection sécurisée du texte
        const span = document.createElement('span');
        span.textContent = " " + message;
        
        toast.appendChild(icon);
        toast.appendChild(span);
        toast.classList.add('show');
        
        setTimeout(() => { toast.classList.remove('show'); }, 3000);
    }

    // SOUMISSION GLOBALE DES FORMULAIRES D'AJOUT AU PANIER
    document.addEventListener('submit', async (e) => {
        const form = e.target.closest('.add-to-cart-form');
        if (form) {
            e.preventDefault();
            e.stopPropagation();
            e.stopImmediatePropagation();

            let actionUrl = form.getAttribute('action');
            if(!actionUrl.startsWith('http') && !actionUrl.startsWith(baseUrl)) {
                 actionUrl = baseUrl + actionUrl;
            }

            const formData = new FormData(form);

            if (!formData.has('csrf_token')) {
                formData.append('csrf_token', getCsrfToken());
            }

            try {
                const response = await fetch(actionUrl, { method: 'POST', body: formData });
                const result = await response.json();
                
                if (result.status === 'error') {
                    showToastNotification(result.message, 'error');
                    return;
                }
                
                rebuildCartDOM(result[0], result[1]);
                showToastNotification("Produit ajouté au panier", 'success'); 
                
            } catch (error) {
                console.error("Erreur lors de l'ajout au panier :", error);
                showToastNotification("Erreur de connexion", 'error');
            }
        }
    }, true);

    // GESTION DES QUANTITÉS ET SUPPRESSION (Délégation d'événements)
    document.body.addEventListener('click', (e) => {
        
        const btnMinus = e.target.closest('.btn-qty.minus');
        if (btnMinus) {
            const cartRow = btnMinus.getAttribute('data-row');
            const container = btnMinus.closest('.quantity-selector-modern') || btnMinus.closest('.qty-wrapper');
            if (container) {
                const input = container.querySelector('.input-qty');
                let currentVal = parseInt(input.value, 10);
                if (currentVal > 1) {
                    currentVal--;
                    input.value = currentVal;
                    updateCartItem(currentVal, cartRow);
                }
            }
            return;
        }

        const btnPlus = e.target.closest('.btn-qty.plus');
        if (btnPlus) {
            const cartRow = btnPlus.getAttribute('data-row');
            const container = btnPlus.closest('.quantity-selector-modern') || btnPlus.closest('.qty-wrapper');
            if (container) {
                const input = container.querySelector('.input-qty');
                let currentVal = parseInt(input.value, 10);
                // Limite théorique pour éviter les abus (Anti-Spam)
                if (currentVal < 99) {
                    currentVal++;
                    input.value = currentVal;
                    updateCartItem(currentVal, cartRow);
                }
            }
            return;
        }

        const btnRemove = e.target.closest('.btn-remove-item');
        if (btnRemove) {
            const cartRow = btnRemove.getAttribute('data-row');
            deleteCartItem(cartRow);
        }
    });

    
});