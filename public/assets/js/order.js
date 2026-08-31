
document.addEventListener("DOMContentLoaded", () => {

    const baseTag = document.querySelector('base');
    const baseUrl = baseTag ? baseTag.getAttribute('href') : '/';
    
    // Fonction sécurisée pour récupérer le jeton CSRF
    function getCsrfToken() {
        const wrapper = document.querySelector('[data-csrf]');
        if (wrapper) return wrapper.getAttribute('data-csrf');
        const input = document.querySelector('input[name="csrf_token"]');
        if (input) return input.value;
        return '';
    }

    // SYSTÈME DE TOAST (SÉCURISÉ ANTI-XSS)
    function showOrderToast(message, type = 'success') {
        let toast = document.getElementById('orderToastNotification');
        if (!toast) {
            toast = document.createElement('div');
            toast.id = 'orderToastNotification';
            // Styles gérés via CSS de préférence, mais injectés ici pour la portabilité
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
            toast.style.transition = 'opacity 0.3s ease-in-out, transform 0.3s ease-in-out';
            document.body.appendChild(toast);
        }

        toast.style.backgroundColor = (type === 'danger') ? '#e03131' : '#2b8a3e';
        toast.innerHTML = ''; // Nettoyage sécurisé
        
        const icon = document.createElement('i');
        icon.className = (type === 'danger') ? 'fa-solid fa-circle-exclamation' : 'fa-solid fa-circle-check';
        icon.style.marginRight = '10px';
        
        // SÉCURITÉ CRITIQUE : Utilisation de createTextNode pour empêcher l'exécution de scripts
        const textNode = document.createTextNode(message);
        
        toast.appendChild(icon);
        toast.appendChild(textNode);

        toast.style.opacity = '1';
        toast.style.display = 'block';

        setTimeout(() => {
            toast.style.opacity = '0';
            setTimeout(() => { toast.style.display = 'none'; }, 300);
        }, 4000);
    }

    // GESTION DES CARTES DE SÉLECTION (Adresse & Transport)
    function attachCardClickEvent(card) {
        card.addEventListener('click', function(e) {
            // Ignorer le clic si l'utilisateur interagit avec un bouton ou un lien
            if(e.target.closest('button') || e.target.closest('a')) return;

            const isAddress = this.classList.contains('js-address-card');
            const groupSelector = isAddress ? '.js-address-card' : '.js-shipping-card';
            
            document.querySelectorAll(groupSelector).forEach(c => {
                c.classList.remove('active');
                const radio = c.querySelector('input[type="radio"]');
                if (radio) radio.checked = false;
            });
            
            this.classList.add('active');
            const targetRadio = this.querySelector('input[type="radio"]');
            if (targetRadio) targetRadio.checked = true;
        });
    }

    document.querySelectorAll('.js-address-card, .js-shipping-card').forEach(card => {
        attachCardClickEvent(card);
    });

    // GESTION DU MODE DE PAIEMENT (Bascule Dynamique)
    const paymentCards = document.querySelectorAll('.js-payment-card');
    const cardDetailsBox = document.getElementById('cardPaymentDetails');
    const bankDetailsBox = document.getElementById('bankTransferDetails');

    if (paymentCards.length > 0) {
        paymentCards.forEach(card => {
            card.addEventListener('click', function() {
                paymentCards.forEach(c => {
                    c.classList.remove('active');
                    const radio = c.querySelector('input[type="radio"]');
                    if (radio) radio.checked = false;
                });

                this.classList.add('active');
                const selectedRadio = this.querySelector('input[type="radio"]');
                if (selectedRadio) selectedRadio.checked = true;

                const method = this.getAttribute('data-method');

                if (method === '1') {
                    if (cardDetailsBox) cardDetailsBox.classList.remove('display-none-box');
                    if (bankDetailsBox) bankDetailsBox.classList.add('display-none-box');
                } else if (method === '2') {
                    if (cardDetailsBox) cardDetailsBox.classList.add('display-none-box');
                    if (bankDetailsBox) bankDetailsBox.classList.remove('display-none-box');
                }
            });
        });
    }

    

});