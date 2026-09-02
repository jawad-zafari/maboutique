"use strict";

document.addEventListener("DOMContentLoaded", () => {
    
    // VALIDATION DU FORMULAIRE DE STATISTIQUES
    const formStatistics = document.getElementById('formStatistics');

    if (formStatistics) {
        formStatistics.addEventListener('submit', (event) => {
            
            const year1Element = document.querySelector('select[name="year1"]');
            const year2Element = document.querySelector('select[name="year2"]');
            
            if (year1Element && year2Element) {
                const year1 = parseInt(year1Element.value, 10);
                const year2 = parseInt(year2Element.value, 10);

                // Validation de la cohérence des années
                if (year2 < year1) {
                    event.preventDefault(); 
                    
                    if (typeof window.showGlobalAdminToast === 'function') {
                        window.showGlobalAdminToast("Erreur : L'année de fin ne peut pas être antérieure à l'année de début.", "danger");
                    } else {
                        alert("L'année de fin ne peut pas être antérieure à l'année de début.");
                    }
                }
            }
        });
    }

    // Gestion des boutons "Retour" pour revenir à la page précédente
    const backButtons = document.querySelectorAll('.js-back-button');
    
    backButtons.forEach(btn => {
        btn.addEventListener('click', function(event) {
            event.preventDefault();
            window.history.back();
        });
    });

});