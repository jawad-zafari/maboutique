"use strict";

document.addEventListener("DOMContentLoaded", () => {

    const showToast = window.showGlobalAdminToast || alert;
    const showConfirm = window.showGlobalAdminConfirm || ((msg, cb) => { if (confirm(msg)) cb(); });

    // SOUMISSION DES ACTIONS EN MASSE SUR LES COMMANDES
    const btnBulkUpdateStatus = document.getElementById('btnBulkUpdateStatus');
    const btnDeleteSelectedOrders = document.getElementById('btnDeleteSelectedOrders');
    const formOrdersSelection = document.getElementById('formOrdersSelection');

    

});