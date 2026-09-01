
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

        // Initialisation de Highcharts avec des options d'accessibilité et un design adapté
        Highcharts.chart('salesChartContainer', {
            chart: {
                type: 'line',
                style: {
                    fontFamily: 'inherit'
                }
            },
            title: {
                text: 'Statistiques des ventes (7 derniers jours)',
                x: -20
            },
            subtitle: {
                text: "Aperçu global de l'activité de la boutique",
                x: -20
            },
            xAxis: {
                categories: categories,
                title: { text: 'Dates' }
            },
            yAxis: {
                title: {
                    text: 'Nombre de commandes'
                },
                plotLines: [{
                    value: 0,
                    width: 1,
                    color: '#dc2626'
                }],
                allowDecimals: false // Empêche l'affichage des décimales (ex: 0.5 n'a pas de sens pour une commande)
            },
            tooltip: {
                valueSuffix: ' commande(s)'
            },
            legend: {
                layout: 'vertical',
                align: 'right',
                verticalAlign: 'middle',
                borderWidth: 0
            },
            series: [{
                name: 'Ventes confirmées',
                data: seriesData,
                color: '#0ea5e9', // Couleur principale
                marker: {
                    enabled: true,
                    radius: 5
                }
            }],
            credits: {
                enabled: false // Rendu plus professionnel sans le logo Highcharts
            }
        });
    }
});