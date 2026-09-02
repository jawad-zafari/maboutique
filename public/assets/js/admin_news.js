"use strict";

document.addEventListener("DOMContentLoaded", () => {

    // Récupération des fonctions de notification et de confirmation globales
    const showConfirm = window.showGlobalAdminConfirm || function(msg, callback) {
        if (confirm(msg)) {
            callback();
        }
    };

    // GESTION DE LA SUPPRESSION D'UNE ACTUALITÉ AVEC CONFIRMATION
    const deleteTriggers = document.querySelectorAll('.btn-delete-trigger');

    deleteTriggers.forEach(button => {
        button.addEventListener('click', function(event) {
            event.preventDefault();
            
           
    });

});