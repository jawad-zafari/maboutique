
document.addEventListener("DOMContentLoaded", () => {

    // Empêcher les exécutions multiples du script
    if (window.searchScriptEventsBound) return;
    window.searchScriptEventsBound = true;

    let currentPage = 1;
    
    const searchForm = document.getElementById('searchForm');
    const productsContainer = document.getElementById('productsContainer');
    const paginationContainer = document.getElementById('paginationContainer');
    
    const baseTag = document.querySelector('base');
    const baseUrl = baseTag ? baseTag.getAttribute('href') : '/';
    
    initializeListeners();
    executeSearch(1);

    // SYSTÈME DE NOTIFICATION TOAST (ANTI-XSS)
    function showSearchToast(message, type = 'success') {
        let toast = document.getElementById('searchToastNotification');
        if (!toast) {
            toast = document.createElement('div');
            toast.id = 'searchToastNotification';
            document.body.appendChild(toast);
        }
        
        toast.className = `toast-notification toast-${type}`;
        toast.textContent = ''; // Nettoyage sécurisé
        
        const icon = document.createElement('i');
        icon.className = type === 'danger' ? 'fa-solid fa-circle-exclamation' : 'fa-solid fa-cart-check';
        icon.setAttribute('aria-hidden', 'true');
        
        // SÉCURITÉ : Injection sécurisée du texte
        const textNode = document.createTextNode(" " + message);
        toast.appendChild(icon);
        toast.appendChild(textNode);
        
        toast.classList.add('show');
        setTimeout(() => { toast.classList.remove('show'); }, 3000);
    }

    // INITIALISATION DES ÉCOUTEURS
    function initializeListeners() {
        if (!searchForm) return;
        const formElements = searchForm.querySelectorAll('select, input[type="checkbox"]');
        formElements.forEach(element => {
            element.addEventListener('change', () => executeSearch(1));
        });
    }

    
});