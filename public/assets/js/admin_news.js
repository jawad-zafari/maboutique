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
            
            // Récupération du formulaire parent pour la suppression
            const parentForm = this.closest('.form-delete-news');
            
            if (parentForm) {
                // Appel du modal global
                showConfirm(
                    "Êtes-vous sûr de vouloir supprimer définitivement cette actualité ? Cette action effacera également l'image associée sur le serveur.",
                    () => {
                        parentForm.submit(); // Soumission du formulaire après confirmation
                    }
                );
            }
        });
    });

});