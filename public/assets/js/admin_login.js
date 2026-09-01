document.addEventListener("DOMContentLoaded", () => {
    
    const loginForm = document.getElementById('adminLoginForm');
    const emailInput = document.getElementById('email');
    const passwordInput = document.getElementById('password');
    const errorContainer = document.getElementById('jsLoginErrorMessage');

    if (loginForm && errorContainer) {
        loginForm.addEventListener('submit', (event) => {
            let isValid = true;
            let erreurs = [];

            // Réinitialisation des styles d'erreurs
            emailInput.style.borderColor = "";
            passwordInput.style.borderColor = "";
            errorContainer.style.display = "none";
            errorContainer.innerHTML = ""; // Nettoyage sécurisé du conteneur

            const emailValue = emailInput.value.trim();
            const passwordValue = passwordInput.value.trim();

            // Validation stricte de l'email via Regex (Format RFC 5322 simplifié)
            const emailRegex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;

            
    }
});