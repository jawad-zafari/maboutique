
document.addEventListener("DOMContentLoaded", () => {

    // Récupération dynamique de l'URL de base pour éviter les erreurs de routage (404)
    const baseTag = document.querySelector('base');
    const baseUrl = baseTag ? baseTag.getAttribute('href') : '/';

    // GESTION DU MENU DE NAVIGATION SUR MOBILE 
    const btnToggleNav = document.getElementById('btnToggleNav');
    const mainNavigation = document.getElementById('mainNavigation');

   

});