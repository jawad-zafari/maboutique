"use strict";

function initAdminSidebar() {
    // Évite d'initialiser deux fois les événements (Garde de sécurité)
    if (window.adminSidebarInitialized) return; 
    window.adminSidebarInitialized = true; 

    const sidebar = document.getElementById("adminSidebar");
    const toggleBtn = document.getElementById("sidebarToggleBtn");
    const overlay = document.getElementById("mobileOverlay");
    
    if (!sidebar || !toggleBtn) return;

    // Indiquer aux lecteurs d'écran quel élément est contrôlé par ce bouton
    toggleBtn.setAttribute('aria-controls', 'adminSidebar');

    const isMobile = window.innerWidth < 992;
    const savedState = localStorage.getItem("adminSidebarState");
    
    