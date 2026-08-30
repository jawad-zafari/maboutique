
document.addEventListener("DOMContentLoaded", () => {
    
    // Récupération sécurisée de l'URL de base et du jeton CSRF
    const baseTag = document.querySelector('base');
    const baseUrl = baseTag ? baseTag.getAttribute('href') : '/';

    const dashboardWrapper = document.getElementById('mainAccountDashboard');
    const csrfToken = dashboardWrapper ? dashboardWrapper.getAttribute('data-csrf') : '';

    // SYSTÈME DE NOTIFICATIONS (Toast)
    function showAccountToast(message, type = 'danger') {
        let toast = document.getElementById('accountToastNotification');
        if (!toast) {
            toast = document.createElement('div');
            toast.id = 'accountToastNotification';
            toast.className = 'toast-modern-notification';
            document.body.appendChild(toast);
        }

        toast.style.backgroundColor = (type === 'danger') ? '#e03131' : '#2b8a3e';
        toast.innerHTML = ''; 
        
        const icon = document.createElement('i');
        icon.className = (type === 'danger') ? 'fa-solid fa-circle-exclamation' : 'fa-solid fa-circle-check';
        icon.style.marginRight = '10px';
        
        // SÉCURITÉ : Anti-XSS via createTextNode au lieu de innerHTML
        const textNode = document.createTextNode(message); 

        toast.appendChild(icon);
        toast.appendChild(textNode);

        toast.classList.add('show');

        setTimeout(() => {
            toast.classList.remove('show');
        }, 3500);
    }

   

});