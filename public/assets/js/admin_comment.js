"use strict";

document.addEventListener("DOMContentLoaded", () => {
    
    const btnApplyAction = document.getElementById('btnApplyAction');
    const formComments = document.getElementById('formCommentsManage');
    const actionSelect = document.getElementById('actionSelect');
    
    const baseTag = document.querySelector('base');
    const baseUrl = baseTag ? baseTag.getAttribute('href') : '/';

    const showToast = window.showGlobalAdminToast || alert;
    const showConfirm = window.showGlobalAdminConfirm || ((msg, cb) => { if (confirm(msg)) cb(); });

    // SOUMISSION DES ACTIONS DE GROUPE (MODÉRATION)
    if (btnApplyAction && formComments && actionSelect) {
        btnApplyAction.addEventListener('click', () => {
            const actionSelected = actionSelect.value;
            let actionUrl = '';
            let requiresConfirmation = false;
            let confirmMessage = '';
            
            if (actionSelected === '1') {
                actionUrl = 'AdminComment/confirm';
            } else if (actionSelected === '2') {
                actionUrl = 'AdminComment/unconfirm';
            } else if (actionSelected === '3') {
                actionUrl = 'AdminComment/delete';
                requiresConfirmation = true;
                confirmMessage = "Voulez-vous vraiment supprimer définitivement ces commentaires de la base de données ?";
            }

            const checkedBoxes = formComments.querySelectorAll('.row-checkbox:checked');
            
            if (checkedBoxes.length === 0) {
                showToast("Veuillez sélectionner au moins un commentaire pour appliquer l'action.", "danger");
                return;
            }

            const submitForm = () => {
                formComments.action = baseUrl + actionUrl;
                formComments.submit();
            };

            if (requiresConfirmation) {
                showConfirm(confirmMessage, submitForm);
            } else {
                submitForm();
            }
        });
    }

   
    }

});