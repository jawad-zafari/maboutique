
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

    //  LOGIQUE DU DEFILEMENT DU CARROUSEL (DESKTOP) 
    const scrollContainer = document.getElementById('navScrollContainer');
    const btnNext = document.getElementById('btnNavNext');
    const btnPrev = document.getElementById('btnNavPrev');

    if (scrollContainer && btnNext && btnPrev) {
        const scrollStep = 250;

        btnNext.addEventListener('click', () => {
            scrollContainer.scrollLeft += scrollStep;
        });

        btnPrev.addEventListener('click', () => {
            scrollContainer.scrollLeft -= scrollStep;
        });
    }

    // GESTION DE LA RECHERCHE EN DIRECT (AUTOCOMPLETE) SÉCURISÉE
    const headerKeyword = document.getElementById('headerKeyword');
    const headerAutoSuggest = document.getElementById('headerAutoSuggest');
    let headerTypingTimer;

    if (headerKeyword && headerAutoSuggest) {
        
        headerKeyword.addEventListener('input', function() {
            clearTimeout(headerTypingTimer);
            const keyword = this.value.trim();
            
            if (keyword.length >= 2) {
                // Attendre 400ms avant d'envoyer la requête
                headerTypingTimer = setTimeout(() => fetchHeaderSuggestions(keyword), 400);
            } else {
                headerAutoSuggest.style.display = 'none';
                headerAutoSuggest.innerHTML = '';
            }
        });

       