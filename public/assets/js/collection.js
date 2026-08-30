
document.addEventListener("DOMContentLoaded", () => {
    
    // DÉTECTION DYNAMIQUE DE L'URL DE BASE ET DU JETON CSRF
    const baseTag = document.querySelector('base');
    const baseUrl = baseTag ? baseTag.getAttribute('href') : '/';

    const mainWrapper = document.getElementById('collectionMainWrapper');
    const csrfToken = mainWrapper ? mainWrapper.getAttribute('data-csrf') : '';

    // GESTION DES FILTRES ET TRIS (AUTO-SUBMIT & PAGINATION)
    const filterForm = document.getElementById('collectionFilterForm');
    
    if (filterForm) {
        const formElements = filterForm.querySelectorAll('select, input[type="checkbox"]');
        
        formElements.forEach(element => {
            element.addEventListener('change', () => {
                // Effet visuel de chargement fluide sur la grille
                const grid = document.querySelector('.products-grid-layout');
                if (grid) {
                    grid.style.opacity = '0.4';
                    grid.style.pointerEvents = 'none';
                    grid.style.transition = 'opacity 0.3s ease';
                }

                const formData = new FormData(filterForm);
                const params = new URLSearchParams(formData);
                
                const currentUrl = new URL(window.location.href);
                currentUrl.search = params.toString();
                window.location.href = currentUrl.toString();
            });
        });

        // Pagination intelligente (qui conserve les filtres actifs)
        const paginationLinks = document.querySelectorAll('.pagination-wrapper .page-link');
        if (paginationLinks.length > 0) {
            const currentParams = new URLSearchParams(new FormData(filterForm)).toString();
            
            paginationLinks.forEach(link => {
                link.addEventListener('click', (e) => {
                    e.preventDefault(); 
                    const targetUrl = link.getAttribute('href');
                    
                    if (currentParams) {
                        const separator = targetUrl.includes('?') ? '&' : '?';
                        window.location.href = targetUrl + separator + currentParams;
                    } else {
                        window.location.href = targetUrl;
                    }
                });
            });
        }
    }

   
    

});