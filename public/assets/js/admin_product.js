"use strict";

document.addEventListener("DOMContentLoaded", () => {
    
    // Empêche les liaisons d'événements multiples
    if (window.adminProductEventsBound) return;
    window.adminProductEventsBound = true;

    // Utilisation des fonctions globales de admin.js avec fallback de sécurité
    const showToast = window.showGlobalAdminToast || alert;
    const showConfirm = window.showGlobalAdminConfirm || ((msg, cb) => { if(confirm(msg)) cb(); });

    // SYSTÈME DYNAMIQUE DE SUPPRESSION (PRODUITS, GALERIE, CRITIQUES)
    const deleteActions = [
        { btnId: 'btnDeleteProducts', formId: 'formProductsSelection' },
        { btnId: 'btnDeleteGallery', formId: 'formGallerySelection' },
        { btnId: 'btnDeleteReview', formId: 'formReviewsSelection' }
    ];

    deleteActions.forEach(action => {
        const btn = document.getElementById(action.btnId);
        const form = document.getElementById(action.formId);

        if (btn && form) {
            btn.addEventListener('click', (event) => {
                event.preventDefault();
                const checkedBoxes = form.querySelectorAll('.row-checkbox:checked');
                
                if (checkedBoxes.length === 0) {
                    showToast("Veuillez sélectionner au moins un élément pour cette action.", "danger");
                    return;
                }

                showConfirm("Êtes-vous sûr de vouloir supprimer définitivement la sélection ?", () => {
                    form.submit();
                });
            });
        }
    });

    

});