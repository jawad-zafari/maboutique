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
    
    // La sidebar
    if (savedState === "open" && !isMobile) {
        sidebar.classList.add("open");
        sidebar.setAttribute('aria-hidden', 'false');
        toggleBtn.setAttribute('aria-expanded', 'true');
    } else {
        sidebar.classList.remove("open");
        sidebar.setAttribute('aria-hidden', 'true');
        toggleBtn.setAttribute('aria-expanded', 'false');
        
        // Forcer la réinitialisation du localStorage si on est sur mobile
        if (isMobile) {
            localStorage.setItem("adminSidebarState", "closed");
        }
    }

    // Événement du bouton (Toggle)
    toggleBtn.addEventListener('click', () => {
        const isOpen = sidebar.classList.toggle("open");
        
        if (isOpen) {
            localStorage.setItem("adminSidebarState", "open");
            sidebar.setAttribute('aria-hidden', 'false');
            toggleBtn.setAttribute('aria-expanded', 'true');
            if (window.innerWidth < 992 && overlay) {
                overlay.classList.add("active");
            }
        } else {
            localStorage.setItem("adminSidebarState", "closed");
            sidebar.setAttribute('aria-hidden', 'true');
            toggleBtn.setAttribute('aria-expanded', 'false');
            if (overlay) {
                overlay.classList.remove("active");
            }
        }
    });

    