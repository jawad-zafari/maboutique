
document.addEventListener("DOMContentLoaded", () => {
    
    const formLogin = document.getElementById('formLogin');
    const errorContainer = document.getElementById('jsLoginErrorMessage');
    
    if (formLogin && errorContainer) {
        formLogin.addEventListener('submit', (event) => {
            
            const emailInput = document.getElementById('email');
            const passwordInput = document.getElementById('password');
            
            let isValid = true;
            let errorMessages = [];

            // 1. Réinitialisation des états (Utilisation de classes CSS)
            if (emailInput) emailInput.classList.remove('input-error');
            if (passwordInput) passwordInput.classList.remove('input-error');
            
            // SÉCURITÉ : Vider le conteneur en toute sécurité (au lieu de innerHTML)
            errorContainer.textContent = "";
            errorContainer.classList.remove('show-error');
            errorContainer.classList.add('is-hidden');

            // 2. Validation de l'E-mail
            const emailValue = emailInput ? emailInput.value.trim() : '';
            if (emailValue === "") {
                isValid = false;
                errorMessages.push("L'adresse e-mail est requise.");
                if (emailInput) emailInput.classList.add('input-error');
            } else {
                // Expression régulière standard pour valider l'e-mail
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!emailRegex.test(emailValue)) {
                    isValid = false;
                    errorMessages.push("Le format de l'adresse e-mail n'est pas valide.");
                    if (emailInput) emailInput.classList.add('input-error');
                }
            }

            // 3. Validation du Mot de passe
            const passwordValue = passwordInput ? passwordInput.value.trim() : '';
            if (passwordValue === "") {
                isValid = false;
                errorMessages.push("Le mot de passe est requis.");
                if (passwordInput) passwordInput.classList.add('input-error');
            }

            // 4. Affichage des erreurs et blocage de l'envoi
            if (!isValid) {
                event.preventDefault(); 
                
                // SÉCURITÉ (Anti-XSS) : Création sécurisée des éléments DOM
                const icon = document.createElement('i');
                icon.className = "fa-solid fa-circle-exclamation";
                icon.setAttribute('aria-hidden', 'true');
                errorContainer.appendChild(icon);

                const textSpan = document.createElement('span');
                // SÉCURITÉ : Utilisation de textContent pour éviter l'injection de code
                textSpan.textContent = " " + errorMessages.join(" ");
                errorContainer.appendChild(textSpan);
                
                // Activation visuelle
                errorContainer.classList.remove('is-hidden');
                errorContainer.classList.add('show-error');
            }
        });
    }
});