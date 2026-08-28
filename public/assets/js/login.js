
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

            
        });
    }
});