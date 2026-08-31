
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

   

});