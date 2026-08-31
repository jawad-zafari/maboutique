
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

    
});