function mostrarView(view) {
    const views = ['dashboard', 'usuarios', 'animais', 'adocoes', 'animais-pendentes'];
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
                    data: [
                        dadosAdocoes.aprovadas,
                        dadosAdocoes.pendentes,
                        dadosAdocoes.recusadas
                    ],
                    backgroundColor: ['#28a745', '#ffc107', '#dc3545']
                }]
            }
        });
    }

    // ... (mantém o gráfico de usuários como está)

    if (animalTypesCtx) {
        new Chart(animalTypesCtx, {
            type: 'pie',
            data: {
                labels: ['Cães', 'Gatos', 'Outros'],
                datasets: [{
                    data: [
                        tiposAnimais.caes,
                        tiposAnimais.gatos,
                        tiposAnimais.outros
                    ],
                    backgroundColor: ['#007bff', '#ffc107', '#28a745']
                }]
            }
        });
    }
});
