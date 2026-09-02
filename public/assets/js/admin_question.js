"use strict";

document.addEventListener("DOMContentLoaded", () => {

    // Détection dynamique de l'URL de base
    const baseTag = document.querySelector('base');
    const baseUrl = baseTag ? baseTag.getAttribute('href') : '/';

    // Raccourcis vers les utilitaires globaux sécurisés (Définis dans admin.js)
    const showToast = window.showGlobalAdminToast || alert;
    const showConfirm = window.showGlobalAdminConfirm || ((msg, cb) => { if (confirm(msg)) cb(); });

    // SOUMISSION DES ACTIONS DE GROUPE (MODÉRATION)
    const btnApplyAction = document.getElementById('btnApplyAction');
    const form = document.getElementById('formQuestionsManage');
    const actionSelect = document.getElementById('actionSelect');

    if (btnApplyAction && form && actionSelect) {
        btnApplyAction.addEventListener('click', (event) => {
            event.preventDefault();
            
            const action = actionSelect.value;
            const checkedBoxes = form.querySelectorAll('.row-checkbox:checked');
            
            if (checkedBoxes.length === 0) {
                showToast("Veuillez sélectionner au moins une question pour appliquer l'action.", "danger");
                return;
            }

            // Routage sécurisé vers le contrôleur
            if (action === '1') {
                form.action = baseUrl + 'AdminQuestion/confirm';
                form.submit();
            } else if (action === '2') {
                form.action = baseUrl + 'AdminQuestion/unconfirm';
                form.submit();
            } else if (action === '3') {
                // Utilisation du modal customisé (Anti-XSS et Accessible)
                showConfirm("Voulez-vous vraiment supprimer définitivement ces questions ? Cette action est irréversible.", () => {
                    form.action = baseUrl + 'AdminQuestion/delete';
                    form.submit();
                });
            }
        });
    }

   
    

});