document.addEventListener('DOMContentLoaded', function() {
    // Elementos onde os gráficos serão renderizados
    const adoptionStatsCtx = document.getElementById('adoptionStatsChart');
    const userGrowthCtx = document.getElementById('userGrowthChart'); // no meu html isso ta sendo usado?
    const animalTypesCtx = document.getElementById('animalTypesChart');

    // Dados fictícios (substitua pelos dados reais da sua API)
    const adoptionStatsData = {
        labels: ['Aprovadas', 'Pendentes', 'Recusadas', 'Canceladas'],
        datasets: [{
            label: 'Status de Adoções',
            data: [15, 8, 3, 2],
            backgroundColor: [
                '#4e73df', // Azul (aprovadas)
                '#f6c23e', // Amarelo (pendentes)
                '#e74a3b', // Vermelho (recusadas)
                '#858796'  // Cinza (canceladas)
            ],
            borderColor: [
                '#2e59d9',
                '#dda20a',
                '#be2617',
                '#6c757d'
            ],
            borderWidth: 1
        }]
    };

    const userGrowthData = {
        labels: ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun'],
        datasets: [{
            label: 'Novos Usuários',
            data: [12, 19, 8, 15, 10, 7],
            backgroundColor: '#1cc88a',
            borderColor: '#17a673',
            borderWidth: 1
        }]
    };

    const animalTypesData = {
        labels: ['Cachorros', 'Gatos', 'Pássaros', 'Outros'],
        datasets: [{
            label: 'Animais Cadastrados',
            data: [45, 30, 15, 10],
            backgroundColor: [
                '#36b9cc',
                '#1cc88a',
                '#f6c23e',
                '#e74a3b'
            ],
            borderWidth: 1
        }]
    };

    // Opções comuns para os gráficos
    const chartOptions = {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'bottom',
                labels: {
                    padding: 20,
                    usePointStyle: true,
                    pointStyle: 'circle'
                }
            },
            tooltip: {
                backgroundColor: '#fff',
                titleColor: '#5a5c69',
                bodyColor: '#858796',
                borderColor: '#dddfeb',
                borderWidth: 1,
                padding: 15,
                displayColors: true,
                intersect: false,
                mode: 'index',
                callbacks: {
                    label: function(context) {
                        return `${context.dataset.label}: ${context.raw}`;
                    }
                }
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    color: '#858796'
                },
                grid: {
                    color: 'rgba(0, 0, 0, 0.05)',
                    drawBorder: false
                }
            },
            x: {
                ticks: {
                    color: '#858796'
                },
                grid: {
                    display: false,
                    drawBorder: false
                }
            }
        }
    };

    // Criar os gráficos
    if (adoptionStatsCtx) {
        new Chart(adoptionStatsCtx, {
            type: 'bar',
            data: adoptionStatsData,
            options: chartOptions
        });
    }

    if (userGrowthCtx) {
        new Chart(userGrowthCtx, {
            type: 'bar',
            data: userGrowthData,
            options: chartOptions
        });
    }

    if (animalTypesCtx) {
        new Chart(animalTypesCtx, {
            type: 'bar',
            data: animalTypesData,
            options: {
                ...chartOptions,
                indexAxis: 'y' // Gráfico de barras horizontais
            }
        });
    }

    // Atualizar os gráficos com dados reais (exemplo com fetch)
    function loadRealData() {
        fetch(`${BASE_PATH}/admin/relatorios/data`)
            .then(response => response.json())
            .then(data => {
                // Aqui você atualizaria os gráficos com dados reais
                console.log('Dados recebidos:', data);
                // adoptionStatsChart.data.datasets[0].data = [data.aprovadas, data.pendentes, data.recusadas];
                // adoptionStatsChart.update();
            })
            .catch(error => console.error('Erro ao carregar dados:', error));
    }

    // Carregar dados reais após 3 segundos (simulação)
    setTimeout(loadRealData, 3000);
});