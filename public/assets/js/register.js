document.addEventListener("DOMContentLoaded", () => {
    
    const formRegister = document.getElementById('formRegister');
    const errorContainer = document.getElementById('jsRegisterErrorMessage');
    
    if (formRegister && errorContainer) {
        formRegister.addEventListener('submit', (event) => {
            
            const lastNameInput = document.getElementById('lastName');
            const mobileInput = document.getElementById('mobile');
            const emailInput = document.getElementById('email');
            const passwordInput = document.getElementById('password');
            const passwordConfirmInput = document.getElementById('passwordConfirm');
            const rulesCheckbox = document.getElementById('rules');
            
            let isValid = true;
            let errors = []; 

            //  Réinitialisation des styles d'erreur sur les champs de saisie
            [lastNameInput, mobileInput, emailInput, passwordInput, passwordConfirmInput].forEach(input => {
                if (input) {
                    input.classList.remove('is-invalid');
                }
            });
            
            // Masquer et vider le conteneur de messages d'erreur
            errorContainer.classList.add('is-hidden');
            // SÉCURITÉ : Utilisation de textContent au lieu de innerHTML pour vider l'élément
            errorContainer.textContent = ''; 

            // Validation du Nom Complet
            if (!lastNameInput || lastNameInput.value.trim() === '') {
                isValid = false;
                errors.push("Le nom complet est obligatoire.");
                if (lastNameInput) lastNameInput.classList.add('is-invalid');
            }

            // Validation du Numéro de Mobile (Expression régulière)
            if (!mobileInput || mobileInput.value.trim() === '') {
                isValid = false;
                errors.push("Le numéro de mobile est obligatoire.");
                if (mobileInput) mobileInput.classList.add('is-invalid');
            } else if (!/^[0-9]{10,14}$/.test(mobileInput.value.trim())) {
                isValid = false;
                errors.push("Le format du numéro de mobile est invalide (uniquement des chiffres, entre 10 et 14).");
                if (mobileInput) mobileInput.classList.add('is-invalid');
            }

           
        });
    }

});