
document.addEventListener("DOMContentLoaded", function() {

    //  Récupération sécurisée de l'URL de base
    const baseTag = document.querySelector('base');
    const baseUrl = baseTag ? baseTag.getAttribute('href') : '/';

    // FONCTION UTILITAIRE : Récupération dynamique du jeton CSRF global
    function getGlobalCsrfToken() {
        const wrapper = document.querySelector('[data-csrf]');
        if (wrapper) return wrapper.getAttribute('data-csrf');
        
        const input = document.querySelector('input[name="csrf_token"]');
        if (input) return input.value;
        
        return '';
    }

    //  Gestion du menu principal (Header avec animations douces)
    const menuItems = document.querySelectorAll('.menu-level-1 > li');
    let menuTimers = {};

    menuItems.forEach((item, index) => {
        if (!item.hasAttribute('data-time')) {
            item.setAttribute('data-time', 'menu_' + index);
        }
        
        const timerId = item.getAttribute('data-time');

        item.addEventListener('mouseenter', function() {
            clearTimeout(menuTimers[timerId]);
            menuTimers[timerId] = setTimeout(() => {
                this.classList.add('active-menu');
            }, 300);
        });

        item.addEventListener('mouseleave', function() {
            clearTimeout(menuTimers[timerId]);
            menuTimers[timerId] = setTimeout(() => {
                this.classList.remove('active-menu');
            }, 400); 
        });
    });

    // Gestion globale des boutons de retour (Remplacement du onclick natif)
    const backButtons = document.querySelectorAll('.js-back-button');
    backButtons.forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            window.history.back();
        });
    });

    

});