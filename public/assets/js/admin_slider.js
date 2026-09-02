"use strict";

document.addEventListener("DOMContentLoaded", () => {

    // Garde de sécurité pour éviter les multiples attachements d'événements
    if (window.adminSliderEventsBound) return;
    window.adminSliderEventsBound = true;

    // Raccourcis vers les utilitaires globaux sécurisés
    const showToast = window.showGlobalAdminToast || alert;
    const showConfirm = window.showGlobalAdminConfirm || ((msg, cb) => { if(confirm(msg)) cb(); });

   

});