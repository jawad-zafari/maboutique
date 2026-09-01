
document.addEventListener("DOMContentLoaded", () => {
    
    const chartContainer = document.getElementById('salesChartContainer');
    
    if (chartContainer) {
        // Récupération sécurisée des données depuis les attributs HTML
        const rawKeys = chartContainer.getAttribute('data-keys');
        const rawValues = chartContainer.getAttribute('data-values');
        
        let categories = [];
        let seriesData = [];

        try {
            categories = JSON.parse(rawKeys);
            // Conversion des valeurs stringifiées en nombres entiers pour Highcharts
            seriesData = JSON.parse(rawValues).map(Number);
        } catch (error) {
            console.error("Erreur lors de l'analyse des données de statistiques :", error);
            return; // Arrêt de l'exécution en cas d'erreur de parsing
        }

       
    }
});