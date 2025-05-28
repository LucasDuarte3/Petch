<?php
require_once __DIR__ . '/../config.php';
require_once ROOT_PATH . '/admin/models/Admin.php';
require_once ROOT_PATH . '/admin/controller/AdminController.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (isset($_SESSION['erro'])) {
    echo '<div class="alert alert-danger">' . htmlspecialchars($_SESSION['erro']) . '</div>';
    unset($_SESSION['erro']);
}
if (isset($_SESSION['sucesso'])) {
    echo '<div class="alert alert-success">' . htmlspecialchars($_SESSION['sucesso']) . '</div>';
    unset($_SESSION['sucesso']);
}

// Verifica se é admin
// if (empty($_SESSION['usuario']) || $_SESSION['usuario']['tipo'] !== 'admin') {
//     header("Location: " . PUBLIC_PATH . "/login.php");
//     exit;
// }

$adminModel = new Admin($pdo);
$totalUsuarios = $adminModel->countUsers();
$totalAnimais = $adminModel->countAnimals();
$totalAdocoes = $adminModel->countAdoptions();
$solicitacoes = $adminModel->listAdoptions(['status' => 'pendente']);

$stmt = $pdo->query("SELECT status, COUNT(*) as total FROM solicitacoes_adocao GROUP BY status");
$adocoesStatus = [];
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $adocoesStatus[$row['status']] = $row['total'];
}
$adocoesAprovadas = $adocoesStatus['aprovado'] ?? 0;
$adocoesPendentes = $adocoesStatus['pendente'] ?? 0;
$adocoesRecusadas = $adocoesStatus['negado'] ?? 0;

$stmt = $pdo->query("SELECT especie, COUNT(*) as total FROM animais GROUP BY especie");
$tiposAnimais = [];
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $tiposAnimais[$row['especie']] = $row['total'];
}
$caes = $tiposAnimais['cachorro'] ?? 0;
$gatos = $tiposAnimais['gato'] ?? 0;
$outros = array_sum($tiposAnimais) - $caes - $gatos;

?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel Administrativo - Petch</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link href="<?= ASSETS_PATH ?>/dashboard.css" rel="stylesheet">
</head>
<body>
<div class="dashboard-container">
    <div class="sidebar">
        <div class="logo">
            <img src="<?= IMG_PATH ?>/logo.png" alt="Logo">
            <span>Administrador</span>
        </div>
        <nav>
            <ul>
                <li><a href="#" onclick="mostrarView('dashboard')"><i class="bi bi-speedometer2"></i> Dashboard</a></li>
                <li><a href="#" onclick="mostrarView('usuarios')">Usuários</a></li>
                <li><a href="#" onclick="mostrarView('animais')">Animais</a></li>
                <li><a href="#" onclick="mostrarView('adocoes')">Adoções</a></li>
            </ul>
        </nav>
    </div>

    <div class="main-content">
        <div id="dashboard-view" class="view active">
            <header>
                <h1>Painel Administrativo</h1>
                <div class="user-info">
                    <span>Bem-vindo, Lucas</span>
                    <img src="<?= IMG_PATH ?>/#" alt="User">
                </div>
            </header>

            <div class="stats-container">
                <div class="stat-card">
                    <div class="stat-icon bg-primary">
                        <i class="bi bi-people"></i>
                    </div>
                    <div class="stat-info">
                        <h3><?= $totalUsuarios ?></h3>
                        <p>Usuários Cadastrados</p>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon bg-success">
                        <i class="bi bi-heart"></i>
                    </div>
                    <div class="stat-info">
                        <h3><?= $totalAnimais ?></h3>
                        <p>Animais Cadastrados</p>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon bg-warning">
                        <i class="bi bi-file-earmark-check"></i>
                    </div>
                    <div class="stat-info">
                        <h3><?= $totalAdocoes ?></h3>
                        <p>Solicitações de Adoção</p>
                    </div>
                </div>
            </div>

            <div class="search-section">
                <h2><i class="bi bi-search"></i> Busca Avançada</h2>
                <form action="<?= APP_PATH ?>/model/user" method="post">
                    <div class="row">
                        <div class="col-md-6">
                            <label>CPF</label>
                            <input type="text" name="cpf" class="form-control" placeholder="Digite o CPF">
                            <label>Nome do usuário</label>
                            <input type="text" name="nome_usuario" class="form-control" placeholder="Nome completo">
                        </div>
                        <div class="col-md-6">
                            <label>E-mail do usuário</label>
                            <input type="email" name="email" class="form-control" placeholder="E-mail cadastrado">
                            <label>Nome do animal</label>
                            <input type="text" name="nome_animal" class="form-control" placeholder="Nome do animal">
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary mt-3">
                        <i class="bi bi-search"></i> Buscar
                    </button>
                </form>
            </div>

            <div class="requests-section">
                <h2><i class="bi bi-list-check"></i> Solicitações Pendentes</h2>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Usuário</th>
                                <th>Animal</th>
                                <th>Data</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($solicitacoes as $solicitacao): ?>
                            <tr>
                                <td><?= $solicitacao['id'] ?></td>
                                <td><?= $solicitacao['usuario_nome'] ?></td>
                                <td><?= $solicitacao['animal_nome'] ?></td>
                                <td><?= date('d/m/Y', strtotime($solicitacao['data_solicitacao'])) ?></td>
                                <td>
                                    <form action="<?= ADMIN_PATH ?>/admin/controller" method="post" style="display:inline;">
                                        <input type="hidden" name="acao" value="aprovar_adocao">
                                        <input type="hidden" name="adocao_id" value="<?= $solicitacao['id'] ?>">
                                        <input type="hidden" name="animal_id" value="<?= $solicitacao['animal_id'] ?>">
                                        <button type="submit" class="btn btn-sm btn-success">
                                            <i class="bi bi-check"></i> Aprovar
                                        </button>
                                    </form>
                                    <form action="<?= ADMIN_PATH ?>/admin/controller" method="post" style="display:inline;">
                                        <input type="hidden" name="acao" value="recusar_adocao">
                                        <input type="hidden" name="adocao_id" value="<?= $solicitacao['id'] ?>">
                                        <button type="submit" class="btn btn-sm btn-danger">
                                            <i class="bi bi-x"></i> Recusar
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="charts-section">
                <div class="chart-container">
                    <h3><i class="bi bi-bar-chart"></i> Status de Adoções</h3>
                    <canvas id="adoptionStatsChart"></canvas>
                </div>
                <div class="chart-container">
                    <h3><i class="bi bi-graph-up"></i> Crescimento de Usuários</h3>
                    <canvas id="userGrowthChart"></canvas>
                </div>
                <div class="chart-container">
                    <h3><i class="bi bi-pie-chart"></i> Tipos de Animais</h3>
                    <canvas id="animalTypesChart"></canvas>
                </div>
            </div>
        </div>

        <div id="usuarios-view" class="view">
            <h2>Gestão de Usuários</h2>
            <p>Aqui você pode listar, bloquear e excluir usuários.</p>
        </div>

        <div id="animais-view" class="view">
            <h2>Gestão de Animais</h2>
            <p>Aqui você pode listar e excluir animais.</p>
        </div>

        <div id="adocoes-view" class="view">
            <h2>Gestão de Adoções</h2>
            <p>Aqui você pode visualizar, aprovar ou recusar adoções.</p>
        </div>
    </div>
</div>

<script>
    // Variáveis globais vindas do PHP para o JS
    const dadosAdocoes = {
        aprovadas: <?= $adocoesAprovadas ?>,
        pendentes: <?= $adocoesPendentes ?>,
        recusadas: <?= $adocoesRecusadas ?>
    };
    const tiposAnimais = {
        caes: <?= $caes ?>,
        gatos: <?= $gatos ?>,
        outros: <?= $outros ?>
    };
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="<?= JS_PATH ?>/dashboard.js"></script>
</body>
</html>
