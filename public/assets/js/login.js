
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

           
        });
    }
});