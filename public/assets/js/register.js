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

            // 1. Réinitialisation des styles d'erreur sur les champs de saisie
            [lastNameInput, mobileInput, emailInput, passwordInput, passwordConfirmInput].forEach(input => {
                if (input) {
                    input.classList.remove('is-invalid');
                }
            });
            
           
    }

});