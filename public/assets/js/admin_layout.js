"use strict";

function initAdminSidebar() {
    // Évite d'initialiser deux fois les événements (Garde de sécurité)
    if (window.adminSidebarInitialized) return; 
    window.adminSidebarInitialized = true; 

    const sidebar = document.getElementById("adminSidebar");
    const toggleBtn = document.getElementById("sidebarToggleBtn");
    const overlay = document.getElementById("mobileOverlay");
    
    if (!sidebar || !toggleBtn) return;

    