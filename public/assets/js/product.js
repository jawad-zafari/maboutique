
document.addEventListener("DOMContentLoaded", () => {

    // Empêche les exécutions multiples du script lors de rechargements partiels
    if (window.productScriptEventsBound) return;
    window.productScriptEventsBound = true;

    // Détermination dynamique de l'URL de base pour garantir le bon fonctionnement du routage
    const baseTag = document.querySelector('base');
    const baseUrl = baseTag ? baseTag.getAttribute('href') : '/';

    // SÉCURITÉ : Récupération du jeton CSRF injecté de manière sécurisée dans la vue
    const productWrapper = document.getElementById('mainProductWrapper');
    const csrfToken = productWrapper ? productWrapper.getAttribute('data-csrf') : '';

    