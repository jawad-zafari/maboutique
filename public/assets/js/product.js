
document.addEventListener("DOMContentLoaded", () => {

    // Empêche les exécutions multiples du script lors de rechargements partiels
    if (window.productScriptEventsBound) return;
    window.productScriptEventsBound = true;

    // Détermination dynamique de l'URL de base pour garantir le bon fonctionnement du routage
    const baseTag = document.querySelector('base');
    const baseUrl = baseTag ? baseTag.getAttribute('href') : '/';

    // SÉCURITÉ : Récupération du jeton CSRF injecté de manière sécurisée dans la vue
    const productWrapper = document.getElementById('mainProductWrapper');
    const csrfToken = productWrapper ? productWrapper.getAttribute('data-csrf') : '';

    // SYSTÈME DE NOTIFICATION TOAST (SÉCURITÉ ANTI-XSS CRITIQUE)

    function showProductToast(message, type = 'success') {
        let toast = document.getElementById('productToastNotification');
        
        // Création dynamique du composant s'il n'existe pas
        if (!toast) {
            toast = document.createElement('div');
            toast.id = 'productToastNotification';
            
            // Configuration des styles de base 
            toast.style.position = 'fixed';
            toast.style.bottom = '20px';
            toast.style.right = '20px';
            toast.style.padding = '15px 25px';
            toast.style.borderRadius = '8px';
            toast.style.color = '#fff';
            toast.style.fontWeight = 'bold';
            toast.style.zIndex = '9999';
            toast.style.boxShadow = '0 10px 15px -3px rgba(0,0,0,0.1)';
            toast.style.transition = 'opacity 0.3s ease-in-out';
            document.body.appendChild(toast);
        }
        
        toast.style.backgroundColor = (type === 'danger') ? '#e03131' : '#2b8a3e';
        
        // Nettoyage propre et sécurisé
        toast.textContent = ''; 
        
        const icon = document.createElement('i');
        icon.className = (type === 'danger') ? 'fa-solid fa-circle-exclamation' : 'fa-solid fa-circle-check';
        icon.style.marginRight = '10px';
        
        // Utilisation de createTextNode pour prévenir toute injection XSS 
        const textNode = document.createTextNode(message);

        toast.appendChild(icon);
        toast.appendChild(textNode);
        
        toast.style.display = 'block';
        // Petit délai pour permettre à la transition CSS de s'appliquer
        setTimeout(() => { toast.style.opacity = '1'; }, 10);
        
        // Disparition automatique après 3 secondes
        setTimeout(() => {
            toast.style.opacity = '0';
            setTimeout(() => { toast.style.display = 'none'; }, 300);
        }, 3000);
    }

   
});