"use strict";



document.addEventListener("DOMContentLoaded", function() {
});

// SYSTÈME GLOBAL DE NOTIFICATIONS (TOAST)
window.showGlobalAdminToast = function(message, type = 'danger') {
    let toast = document.getElementById('globalAdminToast');
    if (!toast) {
        toast = document.createElement('div');
        toast.id = 'globalAdminToast';
        toast.style.position = 'fixed';
        toast.style.bottom = '20px';
        toast.style.left = '20px'; 
        toast.style.padding = '15px 25px';
        toast.style.borderRadius = '8px';
        toast.style.color = '#fff';
        toast.style.fontWeight = 'bold';
        toast.style.zIndex = '10000';
        toast.style.boxShadow = '0 10px 15px -3px rgba(0,0,0,0.1)';
        toast.style.transition = 'opacity 0.3s ease-in-out';
        document.body.appendChild(toast);
    }

    toast.style.backgroundColor = (type === 'danger') ? '#dc2626' : '#16a34a';
    toast.innerHTML = ''; 
    
    const icon = document.createElement('i');
    icon.className = (type === 'danger') ? 'fa-solid fa-triangle-exclamation' : 'fa-solid fa-circle-check';
    icon.style.marginRight = '10px';
    
    toast.appendChild(icon);
    toast.appendChild(document.createTextNode(message)); 
    
    toast.style.opacity = '1';
    toast.style.display = 'block';

    setTimeout(() => { 
        toast.style.opacity = '0'; 
        setTimeout(() => { toast.style.display = 'none'; }, 300);
    }, 3500);
};

