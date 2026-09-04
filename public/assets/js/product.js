document.addEventListener("DOMContentLoaded", () => {

    if (window.productScriptEventsBound) return;
    window.productScriptEventsBound = true;

    const baseTag = document.querySelector('base');
    const baseUrl = baseTag ? baseTag.getAttribute('href') : '/';

    const productWrapper = document.getElementById('mainProductWrapper');
    const csrfToken = productWrapper ? productWrapper.getAttribute('data-csrf') : '';

    function showProductToast(message, type = 'success') {
        let toast = document.getElementById('productToastNotification');
        
        if (!toast) {
            toast = document.createElement('div');
            toast.id = 'productToastNotification';
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
        toast.textContent = ''; 
        
        const icon = document.createElement('i');
        icon.className = (type === 'danger') ? 'fa-solid fa-circle-exclamation' : 'fa-solid fa-circle-check';
        icon.style.marginRight = '10px';
        
        const textNode = document.createTextNode(message);
        toast.appendChild(icon);
        toast.appendChild(textNode);
        
        toast.style.display = 'block';
        setTimeout(() => { toast.style.opacity = '1'; }, 10);
        
        setTimeout(() => {
            toast.style.opacity = '0';
            setTimeout(() => { toast.style.display = 'none'; }, 300);
        }, 3000);
    }

    // Gestion de la galerie d'images
    const mainImageNode = document.getElementById('mainProductImageNode');
    const thumbnails = document.querySelectorAll('.thumb-item-box');

    if (mainImageNode && thumbnails.length > 0) {
        thumbnails.forEach(thumb => {
            thumb.addEventListener('click', function() {
                const newSrc = this.getAttribute('data-src');
                if (newSrc) {
                    mainImageNode.src = newSrc;
                    thumbnails.forEach(t => t.classList.remove('active'));
                    this.classList.add('active');
                }
            });
        });
    }

    // Gestion du modal de zoom
    const zoomModal = document.getElementById('imageZoomModal');
    const zoomedImage = document.getElementById('zoomedImage');
    const btnTriggerImageZoom = document.getElementById('btnTriggerImageZoom');
    const closeZoomModal = document.getElementById('closeZoomModal');

    if (zoomModal && zoomedImage && mainImageNode) {
        const openZoom = () => {
            zoomedImage.src = mainImageNode.src;
            zoomModal.classList.add('active');
            zoomModal.style.display = 'flex';
        };

        if (btnTriggerImageZoom) btnTriggerImageZoom.addEventListener('click', openZoom);
        mainImageNode.addEventListener('click', openZoom);

        const closeZoom = () => { 
            zoomModal.classList.remove('active');
            zoomModal.style.display = 'none'; 
        };
        
        if (closeZoomModal) closeZoomModal.addEventListener('click', closeZoom);
        zoomModal.addEventListener('click', (e) => {
            if (e.target === zoomModal) closeZoom();
        });
    }

    // Gestion des onglets
    const tabButtons = document.querySelectorAll('.btn-tab');
    const tabPanes = document.querySelectorAll('.tab-pane');

    if (tabButtons.length > 0) {
        tabButtons.forEach(btn => {
            btn.addEventListener('click', function() {
                const targetId = this.getAttribute('data-target');
                tabButtons.forEach(b => {
                    b.classList.remove('active');
                    b.setAttribute('aria-selected', 'false');
                });
                tabPanes.forEach(p => p.classList.remove('active'));
                
                this.classList.add('active');
                this.setAttribute('aria-selected', 'true');
                
                const targetPane = document.getElementById(targetId);
                if (targetPane) targetPane.classList.add('active');
            });
        });
    }

    // Gestion de l'ajout au panier
    const btnAddToCart = document.getElementById('btnAddToCart');

    if (btnAddToCart) {
        btnAddToCart.addEventListener('click', async function(e) {
            e.preventDefault();
            const productId = this.getAttribute('data-id');
            if (!productId) return;

            const colorSelect = document.getElementById('productColorSelect');
            const guaranteeSelect = document.getElementById('productGuaranteeSelect');

            const colorId = colorSelect ? colorSelect.value : '0';
            const guaranteeId = guaranteeSelect ? guaranteeSelect.value : '0';

            const originalIcon = this.innerHTML;
            this.innerHTML = '<i class="fa-solid fa-circle-notch fa-spin"></i> Traitement...';
            this.disabled = true;

            try {
                const formData = new URLSearchParams();
                formData.append('quantity', '1');
                formData.append('colorId', colorId);
                formData.append('guaranteeId', guaranteeId);
                formData.append('csrf_token', csrfToken);

                // Injection du jeton CSRF pour prouver que la requête est légitime
                const response = await fetch(`${baseUrl}Cart/addToCart/${productId}`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: formData.toString()
                });

                let result;
                try {
                    result = await response.json();
                } catch (jsonError) {
                    throw new Error("Format de réponse inattendu du serveur.");
                }

                if (response.ok && result.status !== 'error') {
                    showProductToast("Le produit a été ajouté à votre panier avec succès !");
                    
                    const badge = document.getElementById('navCartCounterBadge');
                    if (badge && result.totalItems !== undefined) {
                        badge.textContent = result.totalItems;
                        badge.style.display = 'inline-flex';
                        badge.style.transform = "scale(1.4)";
                        setTimeout(() => { badge.style.transform = "scale(1)"; }, 300);
                    }
                } else {
                    showProductToast(result.message || "Action non autorisée.", "danger");
                }
            } catch (error) {
                console.error("Erreur d'ajout au panier :", error);
                showProductToast("Erreur de communication avec le serveur.", "danger");
            } finally {
                this.innerHTML = originalIcon;
                this.disabled = false;
            }
        });
    }

    // Gestion de la soumission de questions
    const btnSubmitQuestion = document.getElementById('btnSubmitQuestion');
    const textareaQuestion = document.getElementById('questionText');

    if (btnSubmitQuestion && textareaQuestion) {
        btnSubmitQuestion.addEventListener('click', async function(e) {
            e.preventDefault();
            const productId = this.getAttribute('data-id');
            const questionText = textareaQuestion.value.trim();

            if (!questionText) {
                showProductToast("Veuillez saisir votre question avant de soumettre.", "danger");
                textareaQuestion.focus();
                return;
            }

            if (!productId) return;

            try {
                btnSubmitQuestion.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Envoi...';
                btnSubmitQuestion.disabled = true;

                const formData = new URLSearchParams();
                formData.append('question', questionText);
                formData.append('csrf_token', csrfToken);

                const response = await fetch(`${baseUrl}Product/addQuestionAjax/${productId}`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: formData.toString()
                });

                let result;
                try {
                    result = await response.json();
                } catch (jsonError) {
                    throw new Error("Erreur de format de réponse du serveur.");
                }

                if (response.ok && result.status === 'success') {
                    textareaQuestion.value = '';
                    showProductToast(result.message);
                } else {
                    showProductToast(result.message || "Erreur lors de l'envoi de la requête.", "danger");
                }
            } catch (error) {
                console.error("Erreur Q&A:", error);
                showProductToast(error.message || "Erreur de réseau.", "danger");
            } finally {
                btnSubmitQuestion.innerHTML = '<i class="fa-solid fa-paper-plane" aria-hidden="true"></i> Soumettre la question';
                btnSubmitQuestion.disabled = false;
            }
        });
    }

});