"use strict";

document.addEventListener("DOMContentLoaded", () => {
    
    const btnApplyAction = document.getElementById('btnApplyAction');
    const formComments = document.getElementById('formCommentsManage');
    const actionSelect = document.getElementById('actionSelect');
    
    const baseTag = document.querySelector('base');
    const baseUrl = baseTag ? baseTag.getAttribute('href') : '/';

    const showToast = window.showGlobalAdminToast || alert;
    const showConfirm = window.showGlobalAdminConfirm || ((msg, cb) => { if (confirm(msg)) cb(); });

    
    }

});