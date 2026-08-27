
document.addEventListener("DOMContentLoaded", () => {
    
    // Récupération de l'URL de base pour garantir un routing correct
    const baseTag = document.querySelector('base');
    const baseUrl = baseTag ? baseTag.getAttribute('href') : '/';

    // Récupération du jeton CSRF injecté dans la vue
    const homeWrapper = document.getElementById('homeMainWrapper');
    const csrfToken = homeWrapper ? homeWrapper.getAttribute('data-csrf') : '';

    // 1. GESTION DU SLIDER PRINCIPAL
    const sliderTrack = document.getElementById('sliderTrack');
    const slides = document.querySelectorAll('.slide');
    const btnNext = document.getElementById('btnNext');
    const btnPrev = document.getElementById('btnPrev');
    const dotsContainer = document.getElementById('sliderDots');

   
});