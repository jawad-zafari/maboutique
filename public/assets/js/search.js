
document.addEventListener("DOMContentLoaded", () => {

    // Empêcher les exécutions multiples du script
    if (window.searchScriptEventsBound) return;
    window.searchScriptEventsBound = true;

    let currentPage = 1;
    
    const searchForm = document.getElementById('searchForm');
    const productsContainer = document.getElementById('productsContainer');
    const paginationContainer = document.getElementById('paginationContainer');
    
    const baseTag = document.querySelector('base');
    const baseUrl = baseTag ? baseTag.getAttribute('href') : '/';
    
    initializeListeners();
    executeSearch(1);

    // SYSTÈME DE NOTIFICATION TOAST (ANTI-XSS)
    function showSearchToast(message, type = 'success') {
        let toast = document.getElementById('searchToastNotification');
        if (!toast) {
            toast = document.createElement('div');
            toast.id = 'searchToastNotification';
            document.body.appendChild(toast);
        }
        
        toast.className = `toast-notification toast-${type}`;
        toast.textContent = ''; // Nettoyage sécurisé
        
        const icon = document.createElement('i');
        icon.className = type === 'danger' ? 'fa-solid fa-circle-exclamation' : 'fa-solid fa-cart-check';
        icon.setAttribute('aria-hidden', 'true');
        
        // SÉCURITÉ : Injection sécurisée du texte
        const textNode = document.createTextNode(" " + message);
        toast.appendChild(icon);
        toast.appendChild(textNode);
        
        toast.classList.add('show');
        setTimeout(() => { toast.classList.remove('show'); }, 3000);
    }

    // INITIALISATION DES ÉCOUTEURS
    function initializeListeners() {
        if (!searchForm) return;
        const formElements = searchForm.querySelectorAll('select, input[type="checkbox"]');
        formElements.forEach(element => {
            element.addEventListener('change', () => executeSearch(1));
        });
    }

    // MOTEUR DE RECHERCHE AJAX
    async function executeSearch(page) {
        if (!searchForm || !productsContainer) return;

        currentPage = page;
        const formData = new FormData(searchForm);
        formData.append('current_page', currentPage);

        productsContainer.innerHTML = `
            <li class="loading-state" role="status">
                <i class="fa-solid fa-circle-notch fa-spin fa-2x loading-icon" aria-hidden="true"></i>
                <p>Recherche en cours...</p>
            </li>
        `;

        try {
            const response = await fetch(`${baseUrl}Search/doSearch`, {
                method: 'POST',
                body: formData
            });

            let data;
            try {
                data = await response.json();
            } catch (jsonError) {
                throw new Error("Format de réponse inattendu du serveur.");
            }

            if (response.ok && !data.error) {
                const products = data[0] || [];
                const totalPages = data[1] || 1;
                
                renderProducts(products);
                renderPagination(totalPages);
            } else {
                productsContainer.innerHTML = `<li class="empty-state">${data.message || data.error || 'Erreur.'}</li>`;
            }
        } catch (error) {
            console.error("Erreur de recherche :", error);
            productsContainer.innerHTML = '<li class="error-state">Erreur de connexion au serveur.</li>';
        }
    }

    // RENDU DES PRODUITS (DOM BUILDING POUR SÉCURITÉ XSS)
    function renderProducts(products) {
        productsContainer.textContent = ''; 

        if (products.length === 0) {
            const li = document.createElement('li');
            li.className = 'empty-state';
            li.textContent = "Aucun produit ne correspond à vos critères.";
            productsContainer.appendChild(li);
            return;
        }

        products.forEach(product => {
            const priceValue = parseFloat(product.price || 0);
            const discountValue = parseFloat(product.discount_percent || 0);
            const priceTotal = parseFloat(product.price_total || priceValue);
            
            const formattedPrice = new Intl.NumberFormat('fr-FR', { minimumFractionDigits: 2 }).format(priceTotal);
            const formattedOldPrice = new Intl.NumberFormat('fr-FR', { minimumFractionDigits: 2 }).format(priceValue);
            
            const li = document.createElement('li');
            li.className = 'product-card hover-glow';
            li.setAttribute('role', 'listitem');

            const btnFav = document.createElement('button');
            btnFav.type = 'button';
            btnFav.className = 'btn-favorite-toggle';
            btnFav.setAttribute('data-id', product.id);
            btnFav.setAttribute('aria-label', 'Ajouter aux favoris');
            btnFav.innerHTML = '<i class="fa-regular fa-heart" aria-hidden="true"></i>';

            let badge = null;
            if (discountValue > 0) {
                badge = document.createElement('div');
                badge.className = 'badge-item badge-discount';
                badge.textContent = `-${discountValue}%`;
            } else if (product.is_special_offer == 1) {
                badge = document.createElement('div');
                badge.className = 'badge-item badge-new';
                badge.textContent = 'Nouveau';
            }

            const linkImg = document.createElement('a');
            linkImg.href = `${baseUrl}Product/index/${product.id}`;
            linkImg.className = 'card-link-wrapper';

            const imgWrapper = document.createElement('div');
            imgWrapper.className = 'image-wrapper';

            const img = document.createElement('img');
            img.src = `${baseUrl}public/images/products/${product.id}/product_220.jpg`;
            img.className = 'product-img';
            img.alt = product.title || 'Image du produit'; 
            img.onerror = function() { this.src = 'https://placehold.co/220x220/f1f3f5/3b5bdb?text=Produit'; };

            imgWrapper.appendChild(img);
            linkImg.appendChild(imgWrapper);

            const cardContent = document.createElement('div');
            cardContent.className = 'card-content';

            const linkTitle = document.createElement('a');
            linkTitle.href = `${baseUrl}Product/index/${product.id}`;
            linkTitle.className = 'product-title-link';
            
            const titleH4 = document.createElement('h4');
            titleH4.className = 'product-title';
            titleH4.textContent = product.title || 'Produit inconnu'; 

            linkTitle.appendChild(titleH4);

            const priceRow = document.createElement('div');
            priceRow.className = 'price-cart-row';

            const priceContainer = document.createElement('div');
            priceContainer.className = 'product-price-container';

            if (discountValue > 0) {
                const delPrice = document.createElement('del');
                delPrice.className = 'price-old';
                delPrice.textContent = `${formattedOldPrice} €`;
                
                const spanPrice = document.createElement('span');
                spanPrice.className = 'product-price price-danger';
                spanPrice.textContent = `${formattedPrice} €`;
                
                priceContainer.appendChild(delPrice);
                priceContainer.appendChild(spanPrice);
            } else {
                const spanPrice = document.createElement('span');
                spanPrice.className = 'product-price price-primary';
                spanPrice.textContent = `${formattedPrice} €`;
                priceContainer.appendChild(spanPrice);
            }

            const btnCart = document.createElement('button');
            btnCart.type = 'button';
            btnCart.className = 'btn-quick-add';
            btnCart.setAttribute('data-id', product.id);
            btnCart.setAttribute('aria-label', 'Ajouter au panier');
            btnCart.innerHTML = '<i class="fa-solid fa-cart-plus" aria-hidden="true"></i>';

            priceRow.appendChild(priceContainer);
            priceRow.appendChild(btnCart);

            cardContent.appendChild(linkTitle);
            cardContent.appendChild(priceRow);

            li.appendChild(btnFav);
            if (badge) li.appendChild(badge);
            li.appendChild(linkImg);
            li.appendChild(cardContent);

            productsContainer.appendChild(li);
        });
    }

    