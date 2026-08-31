
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

    // FORMATAGE DES CHAMPS DE CARTE BANCAIRE
    const cardNumberInput = document.getElementById('cardNumber');
    if (cardNumberInput) {
        cardNumberInput.addEventListener('input', (e) => {
            let value = e.target.value.replace(/\D/g, ''); // Garder uniquement les chiffres
            value = value.substring(0, 16);
            let formatted = value.match(/.{1,4}/g)?.join(' ') || value;
            e.target.value = formatted;
        });
    }

    const cardExpiryInput = document.getElementById('cardExpiry');
    if (cardExpiryInput) {
        cardExpiryInput.addEventListener('input', (e) => {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length >= 2) {
                value = value.substring(0, 2) + '/' + value.substring(2, 4);
            }
            e.target.value = value.substring(0, 5);
        });
    }

    // AJOUT D'ADRESSE INLINE EN AJAX
    const btnToggleAddressForm = document.getElementById('btnToggleAddressForm');
    const inlineAddressFormContainer = document.getElementById('inlineAddressFormContainer');
    const btnCancelAddressInline = document.getElementById('btnCancelAddressInline');
    const formAddAddress = document.getElementById('formAddAddress');

    if (btnToggleAddressForm && inlineAddressFormContainer) {
        btnToggleAddressForm.addEventListener('click', () => {
            const isHidden = inlineAddressFormContainer.classList.contains('display-none-box');
            
            if (isHidden) {
                inlineAddressFormContainer.classList.remove('display-none-box');
                btnToggleAddressForm.setAttribute('aria-expanded', 'true');
                btnToggleAddressForm.innerHTML = '<i class="fa-solid fa-minus" aria-hidden="true"></i> Masquer le formulaire';
                
                const firstInput = inlineAddressFormContainer.querySelector('input');
                if (firstInput) firstInput.focus();
            } else {
                inlineAddressFormContainer.classList.add('display-none-box');
                btnToggleAddressForm.setAttribute('aria-expanded', 'false');
                btnToggleAddressForm.innerHTML = '<i class="fa-solid fa-plus" aria-hidden="true"></i> Ajouter une adresse';
            }
        });
    }

    if (btnCancelAddressInline && inlineAddressFormContainer && btnToggleAddressForm) {
        btnCancelAddressInline.addEventListener('click', () => {
            inlineAddressFormContainer.classList.add('display-none-box');
            btnToggleAddressForm.setAttribute('aria-expanded', 'false');
            btnToggleAddressForm.innerHTML = '<i class="fa-solid fa-plus" aria-hidden="true"></i> Ajouter une adresse';
            if (formAddAddress) formAddAddress.reset();
        });
    }

    if (formAddAddress) {
        formAddAddress.addEventListener('submit', async (e) => {
            e.preventDefault();

            const submitBtn = formAddAddress.querySelector('button[type="submit"]');
            const originalBtnText = submitBtn.innerHTML;
            
            submitBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Enregistrement...';
            submitBtn.disabled = true;

            const formData = new FormData(formAddAddress);
            // SÉCURITÉ : Ajout du jeton CSRF
            formData.append('csrf_token', getCsrfToken());

            try {
                const response = await fetch(`${baseUrl}Order/addAddressAjax`, {
                    method: 'POST',
                    body: formData
                });

                const result = await response.json();

                if (result.status === 'success' && result.address) {
                    showOrderToast(result.message, "success");

                    const addr = result.address;
                    const addressGrid = document.querySelector('.address-cards-grid');
                    const emptyNotice = document.getElementById('emptyAddressNotice');
                    if (emptyNotice) emptyNotice.remove();

                    // Désactiver les autres cartes
                    document.querySelectorAll('.js-address-card').forEach(c => {
                        c.classList.remove('active');
                        const r = c.querySelector('input[type="radio"]');
                        if (r) r.checked = false;
                    });

                    // Création sécurisée de la nouvelle carte d'adresse
                    const newCard = document.createElement('div');
                    newCard.className = 'modern-selection-card js-address-card active';
                    newCard.setAttribute('data-id', addr.id);

                    const radioBox = document.createElement('div');
                    radioBox.className = 'card-radio-select';

                    const radioInput = document.createElement('input');
                    radioInput.type = 'radio';
                    radioInput.name = 'selected_address';
                    radioInput.id = `addr_${addr.id}`;
                    radioInput.value = addr.id;
                    radioInput.checked = true;

                    const label = document.createElement('label');
                    label.setAttribute('for', `addr_${addr.id}`);
                    const strong = document.createElement('strong');
                    strong.textContent = addr.last_name || '';
                    label.appendChild(strong);

                    radioBox.appendChild(radioInput);
                    radioBox.appendChild(label);

                    const pSummary = document.createElement('p');
                    pSummary.className = 'address-text-summary';
                    pSummary.textContent = addr.address || '';

                    const spanCity = document.createElement('span');
                    spanCity.className = 'address-city-zip';
                    spanCity.textContent = `${addr.city_name || addr.city || ''} (${addr.postal_code || ''})`;

                    newCard.appendChild(radioBox);
                    newCard.appendChild(pSummary);
                    newCard.appendChild(spanCity);

                    attachCardClickEvent(newCard);

                    if (addressGrid) {
                        addressGrid.prepend(newCard);
                    }

                    inlineAddressFormContainer.classList.add('display-none-box');
                    btnToggleAddressForm.setAttribute('aria-expanded', 'false');
                    btnToggleAddressForm.innerHTML = '<i class="fa-solid fa-plus" aria-hidden="true"></i> Ajouter une adresse';
                    formAddAddress.reset();

                } else {
                    showOrderToast(result.message || "Erreur lors de l'enregistrement.", "danger");
                }
            } catch (error) {
                console.error("Erreur AJAX Adresse:", error);
                showOrderToast("Erreur de connexion au serveur.", "danger");
            } finally {
                submitBtn.innerHTML = originalBtnText;
                submitBtn.disabled = false;
            }
        });
    }

    // VALIDATION DE L'ÉTAPE ADRESSE ET CONTINUATION VERS LE PAIEMENT
    const btnContinueToSummary = document.getElementById('btnContinueToSummary');
    const jsErrorMessage = document.getElementById('jsErrorMessage');

    if (btnContinueToSummary) {
        btnContinueToSummary.addEventListener('click', async (e) => {
            e.preventDefault();
            
            const activeAddress = document.querySelector('.js-address-card.active');
            const activeShipping = document.querySelector('.js-shipping-card.active');

            if (!activeAddress || !activeShipping) {
                if (jsErrorMessage) {
                    jsErrorMessage.innerHTML = '<i class="fa-solid fa-triangle-exclamation" aria-hidden="true"></i> Veuillez sélectionner une adresse et un mode de livraison.';
                    jsErrorMessage.classList.remove('display-none-box');
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                } else {
                    showOrderToast("Veuillez sélectionner une adresse et un mode de livraison.", "danger");
                }
                return;
            }
            
            if (jsErrorMessage) jsErrorMessage.classList.add('display-none-box');
            
            const addressId = activeAddress.querySelector('input[type="radio"]').value;
            const shippingId = activeShipping.querySelector('input[type="radio"]').value;

            const formData = new URLSearchParams();
            formData.append('addressId', addressId);
            formData.append('shippingId', shippingId);
            formData.append('csrf_token', getCsrfToken());

            btnContinueToSummary.innerHTML = '<i class="fa-solid fa-circle-notch fa-spin" aria-hidden="true"></i> Traitement...';
            btnContinueToSummary.disabled = true;

            try {
                const response = await fetch(`${baseUrl}Order/saveAddressSession`, {
                    method: 'POST',
                    body: formData
                });
                
                const result = await response.json();
                
                if (result.status === 'success') {
                    window.location.href = `${baseUrl}Order/payment`;
                } else {
                    showOrderToast(result.message || "Erreur de validation.", "danger");
                    btnContinueToSummary.innerHTML = 'Continuer <i class="fa-solid fa-arrow-right" aria-hidden="true"></i>';
                    btnContinueToSummary.disabled = false;
                }
            } catch (err) {
                console.error("Erreur Checkout:", err);
                showOrderToast("Erreur de connexion au serveur.", "danger");
                btnContinueToSummary.innerHTML = 'Continuer <i class="fa-solid fa-arrow-right" aria-hidden="true"></i>';
                btnContinueToSummary.disabled = false;
            }
        });
    }

    
    

});