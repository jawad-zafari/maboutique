"use strict";

document.addEventListener("DOMContentLoaded", () => {

    // 1. SYSTÈME DYNAMIQUE DE NOTIFICATIONS (TOAST) AVEC ANTI-XSS
    function showAdminToast(message, type = 'danger') {
        let toast = document.getElementById('adminToastNotification');
        if (!toast) {
            toast = document.createElement('div');
            toast.id = 'adminToastNotification';
            toast.style.position = 'fixed';
            toast.style.bottom = '20px';
            toast.style.right = '20px';
            toast.style.padding = '15px 25px';
            toast.style.borderRadius = '6px';
            toast.style.color = '#fff';
            toast.style.fontWeight = 'bold';
            toast.style.zIndex = '9999';
            toast.style.boxShadow = '0 4px 6px rgba(0,0,0,0.1)';
            toast.style.transition = 'opacity 0.3s ease-in-out';
            document.body.appendChild(toast);
        }

        toast.style.backgroundColor = (type === 'danger') ? '#e03131' : '#2b8a3e';
        toast.innerHTML = ''; // Nettoyage sécurisé
        
       

});