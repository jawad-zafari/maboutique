
document.addEventListener("DOMContentLoaded", () => {
    
    const baseTag = document.querySelector('base');
    const baseUrl = baseTag ? baseTag.getAttribute('href') : '/';

    // Récupération sécurisée du jeton CSRF
    function getCsrfToken() {
        const wrapper = document.querySelector('[data-csrf]');
        if (wrapper) return wrapper.getAttribute('data-csrf');
        const input = document.querySelector('input[name="csrf_token"]');
        if (input) return input.value;
        return '';
    }

    // Système de notification Toast (Sécurisé Anti-XSS via textContent)
    function showPaymentToast(message, type = 'danger') {
        let toast = document.getElementById('paymentToastNotification');
        if (!toast) {
            toast = document.createElement('div');
            toast.id = 'paymentToastNotification';
            toast.style.position = 'fixed';
            toast.style.top = '20px';
            toast.style.left = '50%';
            toast.style.transform = 'translateX(-50%)';
            toast.style.padding = '15px 30px';
            toast.style.borderRadius = '8px';
            toast.style.color = '#fff';
            toast.style.fontWeight = 'bold';
            toast.style.zIndex = '10000';
            toast.style.boxShadow = '0 10px 25px rgba(0,0,0,0.15)';
            toast.style.transition = 'opacity 0.3s ease-in-out';
            document.body.appendChild(toast);
        }

        toast.style.backgroundColor = (type === 'danger') ? '#e03131' : '#2b8a3e';
        // SÉCURITÉ : textContent empêche l'exécution de balises HTML malveillantes
        toast.textContent = message;
        toast.style.opacity = '1';
        toast.style.display = 'block';

        setTimeout(() => {
            toast.style.opacity = '0';
            setTimeout(() => { toast.style.display = 'none'; }, 300);
        }, 3500);
    }

    // LOGIQUE DE SIMULATION DU PAIEMENT EN LIGNE (MOCK GATEWAY)
    const mockLoader = document.getElementById('mockPaymentLoader');
    
    if (mockLoader) {
        const orderId = mockLoader.getAttribute('data-order-id');
        const csrfToken = getCsrfToken();

        // Simulation d'une attente réseau (3 secondes) pour la présentation DWWM
        setTimeout(async () => {
            try {
                const formData = new FormData();
                formData.append('csrf_token', csrfToken);

                const response = await fetch(`${baseUrl}Checkout/processMockPaymentAjax/${orderId}`, {
                    method: 'POST',
                    body: formData
                });
                
                const result = await response.json();

                if (result.status === 'success') {
                    // Paiement réussi : rechargement pour afficher la facture
                    window.location.reload();
                } else {
                    // Échec du paiement (ex: fonds insuffisants) : redirection sécurisée
                    const errorMessage = encodeURIComponent(result.message || 'Erreur lors du paiement.');
                    window.location.href = `${baseUrl}Checkout/showError?error=${errorMessage}&orderId=${orderId}`;
                }

            } catch (error) {
                console.error("Erreur serveur lors de la simulation:", error);
                window.location.href = `${baseUrl}Checkout/showError?error=Erreur_serveur_inattendue&orderId=${orderId}`;
            }
        }, 3000); // 3000ms = 3 secondes
    }

    
});