"use strict";

document.addEventListener("DOMContentLoaded", () => {

    // Détection dynamique de l'URL de base
    const baseTag = document.querySelector('base');
    const baseUrl = baseTag ? baseTag.getAttribute('href') : '/';

    // Raccourcis vers les utilitaires globaux définis dans admin.js
    const showToast = window.showGlobalAdminToast || alert;
    const showConfirm = window.showGlobalAdminConfirm || ((msg, cb) => { if (confirm(msg)) cb(); });

    // GESTION DES ACTIONS DE GROUPE SUR LES UTILISATEURS
    const btnApplyUserAction = document.getElementById('btnApplyUserAction');
    const formUsersManage = document.getElementById('formUsersManage');
    const actionSelect = document.getElementById('actionSelect');

    if (btnApplyUserAction && formUsersManage && actionSelect) {
        
        btnApplyUserAction.addEventListener('click', (event) => {
            event.preventDefault();

            const checkboxes = formUsersManage.querySelectorAll('.row-checkbox:checked');
            
            // Au moins un utilisateur doit être sélectionné
            if (checkboxes.length === 0) {
                showToast("Veuillez sélectionner au moins un utilisateur pour appliquer une action.", "danger");
                return;
            }

           
    }

});