"use strict";

document.addEventListener("DOMContentLoaded", () => {

    const showToast = window.showGlobalAdminToast || alert;
    const showConfirm = window.showGlobalAdminConfirm || ((msg, cb) => { if (confirm(msg)) cb(); });

    // SOUMISSION DES ACTIONS EN MASSE SUR LES COMMANDES
    const btnBulkUpdateStatus = document.getElementById('btnBulkUpdateStatus');
    const btnDeleteSelectedOrders = document.getElementById('btnDeleteSelectedOrders');
    const formOrdersSelection = document.getElementById('formOrdersSelection');

    if (btnBulkUpdateStatus && formOrdersSelection) {
        btnBulkUpdateStatus.addEventListener('click', (event) => {
            const checkedBoxes = formOrdersSelection.querySelectorAll('.row-checkbox:checked');
            const bulkSelect = document.getElementById('bulkStatusSelect');

            if (checkedBoxes.length === 0) {
                event.preventDefault();
                showToast("Veuillez sélectionner au moins une commande dans la liste.", "danger");
                return;
            }

            if (!bulkSelect || bulkSelect.value === "") {
                event.preventDefault();
                showToast("Veuillez choisir un nouveau statut à appliquer.", "danger");
                return;
            }
        });
    }

    if (btnDeleteSelectedOrders && formOrdersSelection) {
        btnDeleteSelectedOrders.addEventListener('click', (event) => {
            event.preventDefault();
            const checkedBoxes = formOrdersSelection.querySelectorAll('.row-checkbox:checked');

            if (checkedBoxes.length === 0) {
                showToast("Veuillez sélectionner au moins une commande à supprimer.", "danger");
                return;
            }

            showConfirm(
                "Êtes-vous sûr de vouloir supprimer définitivement les commandes sélectionnées ? Cette action est irréversible.",
                () => {
                    formOrdersSelection.action = btnDeleteSelectedOrders.getAttribute('formaction');
                    formOrdersSelection.submit();
                }
            );
        });
    }

    

});