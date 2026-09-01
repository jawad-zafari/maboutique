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

            // Validation de l'email
            if (emailValue === "") {
                isValid = false;
                erreurs.push("L'adresse e-mail est requise.");
                emailInput.style.borderColor = "#dc2626"; // Indication visuelle d'erreur (Rouge)
            } else if (!emailRegex.test(emailValue)) {
                isValid = false;
                erreurs.push("Le format de l'adresse e-mail est invalide.");
                emailInput.style.borderColor = "#dc2626";
            }

            // Validation du mot de passe
            if (passwordValue === "") {
                isValid = false;
                erreurs.push("Le mot de passe est requis.");
                passwordInput.style.borderColor = "#dc2626";
            }

           
        });
    }
});