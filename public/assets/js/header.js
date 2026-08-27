
document.addEventListener("DOMContentLoaded", () => {

    // Récupération dynamique de l'URL de base pour éviter les erreurs de routage (404)
    const baseTag = document.querySelector('base');
    const baseUrl = baseTag ? baseTag.getAttribute('href') : '/';

    // GESTION DU MENU DE NAVIGATION SUR MOBILE 
    const btnToggleNav = document.getElementById('btnToggleNav');
    const mainNavigation = document.getElementById('mainNavigation');

    if (btnToggleNav && mainNavigation) {
        btnToggleNav.addEventListener('click', function() {
            mainNavigation.classList.toggle('active');
            
            const icon = this.querySelector('i');
            if (icon) {
                if (mainNavigation.classList.contains('active')) {
                    icon.classList.remove('fa-bars');
                    icon.classList.add('fa-xmark');
                } else {
                    icon.classList.remove('fa-xmark');
                    icon.classList.add('fa-bars');
                }
            }
        });
    }

    