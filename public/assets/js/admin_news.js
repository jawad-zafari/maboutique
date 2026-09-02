"use strict";

document.addEventListener("DOMContentLoaded", () => {

    // Récupération des fonctions de notification et de confirmation globales
    const showConfirm = window.showGlobalAdminConfirm || function(msg, callback) {
        if (confirm(msg)) {
            callback();
        }
    };

    
    

});