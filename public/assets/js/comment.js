
document.addEventListener("DOMContentLoaded", () => {
    
    // Sélectionner tous les inputs de type range
    const rangeInputs = document.querySelectorAll('.native-range');

    rangeInputs.forEach(input => {
        // Sélectionner le badge d'affichage correspondant à ce curseur
        const badge = input.nextElementSibling;

        // Écouter l'événement 'input' (mise à jour en temps réel lors du glissement)
        input.addEventListener('input', function() {
            // Utilisation de textContent (Anti-XSS)
            badge.textContent = this.value;
            
            // Mise à jour pour les lecteurs d'écran
            this.setAttribute('aria-valuenow', this.value);
            
            // Changement de couleur selon la note attribuée
            if(this.value >= 4) {
                badge.style.backgroundColor = '#36be2b';
            } else if (this.value == 3) {
                badge.style.backgroundColor = '#f39c12'; 
            } else {
                badge.style.backgroundColor = '#e74c3c'; 
            }
        });
        
        // Initialiser l'état visuel au chargement de la page
        input.dispatchEvent(new Event('input'));
    });

});