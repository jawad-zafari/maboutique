
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

    