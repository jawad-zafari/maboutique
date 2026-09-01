"use strict";

document.addEventListener("DOMContentLoaded", () => {
    
    // Empêche les liaisons d'événements multiples
    if (window.adminProductEventsBound) return;
    window.adminProductEventsBound = true;

    // Utilisation des fonctions globales de admin.js avec fallback de sécurité
    const showToast = window.showGlobalAdminToast || alert;
    const showConfirm = window.showGlobalAdminConfirm || ((msg, cb) => { if(confirm(msg)) cb(); });

});