"use strict";

document.addEventListener("DOMContentLoaded", () => {
    
    function setupColorPicker(inputId) {
        const inputElement = document.getElementById(inputId);
        if (inputElement) {
            inputElement.addEventListener('click', () => {
                if (inputElement.jscolor) {
                    inputElement.jscolor.show();
                }
            });
        }
    }

    setupColorPicker('bodyColor');
    setupColorPicker('menuColor');

    // NETTOYAGE DE L'URL ET ANIMATION DE SUCCÈS
    if (window.history.replaceState && window.location.search !== '') {
        const urlParams = new URLSearchParams(window.location.search);
        
        if (urlParams.has('success')) {
            const cleanUrl = window.location.protocol + "//" + window.location.host + window.location.pathname;
            window.history.replaceState({ path: cleanUrl }, '', cleanUrl);
            
            const alertBox = document.querySelector('.alert-sticky.success');
            if (alertBox) {
                setTimeout(() => {
                    alertBox.style.transition = 'opacity 0.5s ease';
                    alertBox.style.opacity = '0';
                    setTimeout(() => alertBox.remove(), 500);
                }, 4000);
            }
        }
    }
});