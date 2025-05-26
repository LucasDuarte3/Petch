function mostrarView(view) {
    const views = ['dashboard', 'usuarios', 'animais', 'adocoes'];
    views.forEach(v => {
        const div = document.getElementById(v + '-view');
        if (div) {
            div.classList.remove('active');
        }
    });
    const ativa = document.getElementById(view + '-view');
    if (ativa) {
        ativa.classList.add('active');
    }
}

// Exemplo de inicialização de gráficos com Chart.js
document.addEventListener('DOMContentLoaded', function() {
    const adoptionCtx = document.getElementById('adoptionStatsChart')?.getContext('2d');
    const userGrowthCtx = document.getElementById('userGrowthChart')?.getContext('2d');
    const animalTypesCtx = document.getElementById('animalTypesChart')?.getContext('2d');

    if (adoptionCtx) {
        new Chart(adoptionCtx, {
            type: 'bar',
            data: {
                labels: ['Aprovadas', 'Pendentes', 'Recusadas'],
                datasets: [{
                    label: 'Adoções',
                    data: [12, 7, 3],
                    backgroundColor: ['#28a745', '#ffc107', '#dc3545']
                }]
            }
        });
    }

    if (userGrowthCtx) {
        new Chart(userGrowthCtx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun'],
                datasets: [{
                    label: 'Novos Usuários',
                    data: [5, 10, 15, 20, 25, 30],
                    borderColor: '#007bff',
                    fill: false
                }]
            }
        });
    }

    if (animalTypesCtx) {
        new Chart(animalTypesCtx, {
            type: 'pie',
            data: {
                labels: ['Cães', 'Gatos', 'Outros'],
                datasets: [{
                    data: [60, 30, 10],
                    backgroundColor: ['#007bff', '#ffc107', '#28a745']
                }]
            }
        });
    }
});
