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

   

});