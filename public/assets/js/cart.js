
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

    
});