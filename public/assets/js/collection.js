
document.addEventListener("DOMContentLoaded", () => {
    
    // DÉTECTION DYNAMIQUE DE L'URL DE BASE ET DU JETON CSRF
    const baseTag = document.querySelector('base');
    const baseUrl = baseTag ? baseTag.getAttribute('href') : '/';

    const mainWrapper = document.getElementById('collectionMainWrapper');
    const csrfToken = mainWrapper ? mainWrapper.getAttribute('data-csrf') : '';

    // GESTION DES FILTRES ET TRIS (AUTO-SUBMIT & PAGINATION)
    const filterForm = document.getElementById('collectionFilterForm');
    
   
});