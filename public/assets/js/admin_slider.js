"use strict";

document.addEventListener("DOMContentLoaded", () => {

    // Garde de sécurité pour éviter les multiples attachements d'événements
    if (window.adminSliderEventsBound) return;
    window.adminSliderEventsBound = true;

    // Raccourcis vers les utilitaires globaux sécurisés
    const showToast = window.showGlobalAdminToast || alert;
    const showConfirm = window.showGlobalAdminConfirm || ((msg, cb) => { if(confirm(msg)) cb(); });

    // SUPPRESSION DES SLIDES AVEC DIALOGUE DE CONFIRMATION
    const btnDeleteSlider = document.getElementById('btnDeleteSlider');
    const formSlidersSelection = document.getElementById('formSlidersSelection');

    if (btnDeleteSlider && formSlidersSelection) {
        btnDeleteSlider.addEventListener('click', (event) => {
            event.preventDefault();
            const checkedBoxes = formSlidersSelection.querySelectorAll('.row-checkbox:checked');

            if (checkedBoxes.length === 0) {
                showToast("Veuillez sélectionner au moins un slide à supprimer.", "danger");
                return;
            }

            showConfirm(
                "Êtes-vous sûr de vouloir supprimer définitivement les slides sélectionnés ? Les fichiers images correspondants seront supprimés du serveur.",
                () => {
                    formSlidersSelection.submit();
                }
            );
        });
    }

    // SÉLECTIONNER / DÉSÉLECTIONNER TOUT
    const selectAllCheckboxes = document.getElementById("selectAllCheckboxes");
    if (selectAllCheckboxes) {
        selectAllCheckboxes.addEventListener("change", (event) => {
            const isChecked = event.target.checked;
            const rowCheckboxes = document.querySelectorAll(".row-checkbox");
            rowCheckboxes.forEach(cb => cb.checked = isChecked);
        });
    }

   

});