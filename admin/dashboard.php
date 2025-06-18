<?php
require_once __DIR__ . '/../config.php';
require_once ROOT_PATH . '/admin/models/Admin.php';
require_once ROOT_PATH . '/admin/controller/AdminController.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (isset($_SESSION['erro'])) {
    echo '<div class="toast-container"><div class="toast toast-error">' . 
         htmlspecialchars($_SESSION['erro']) . '</div></div>';
    unset($_SESSION['erro']);
}
if (isset($_SESSION['sucesso'])) {
    echo '<div class="toast-container"><div class="toast toast-success">' . 
         htmlspecialchars($_SESSION['sucesso']) . '</div></div>';
    unset($_SESSION['sucesso']);
}

// Verifica se é admin
// if (empty($_SESSION['usuario']) || $_SESSION['usuario']['tipo'] !== 'admin') {
//     header("Location: " . PUBLIC_PATH . "/login.php");
//     exit;
// }

$adminModel = new Admin($pdo);// Instanciamos o Admin model pra usar as funções de contagem, listagem, etc
$totalUsuarios = $adminModel->countUsers();
$totalAnimais = $adminModel->countAnimals();
$totalAdocoes = $adminModel->countAdoptions();
$solicitacoes = $adminModel->listAdoptions(['status' => 'pendente']);

$stmt = $pdo->query("SELECT status, COUNT(*) as total FROM solicitacoes_adocao GROUP BY status");// Preparamos dados de status das adoções, pra mostrar nos gráficos do dashboard
$adocoesStatus = [];
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $adocoesStatus[$row['status']] = $row['total'];
}
$adocoesAprovadas = $adocoesStatus['aprovado'] ?? 0;
$adocoesPendentes = $adocoesStatus['pendente'] ?? 0;
$adocoesRecusadas = $adocoesStatus['negado'] ?? 0;

$stmt = $pdo->query("SELECT especie, COUNT(*) as total FROM animais GROUP BY especie"); 
// Isso é só pra separar cachorro, gato e outros pros gráficos
$tiposAnimais = [];
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $tiposAnimais[$row['especie']] = $row['total'];
}
$caes = $tiposAnimais['cachorro'] ?? 0;
$gatos = $tiposAnimais['gato'] ?? 0;
$outros = array_sum($tiposAnimais) - $caes - $gatos;

// Listagens que vão preencher as tabelas das views (usuários, animais, adoções, anúncios pendentes)

$usuarios = $adminModel->listUsers();
$animais = $adminModel->listAnimals();
$adocoes = $adminModel->listAdoptions();
$animaisPendentes = $adminModel->listPendingAnimals();// Aqui está a lista de animais que ainda estão aguardando aprovação do admin
$solicitacoes = $adminModel->listarSolicitacoesAdocao(); // Aqui estão as solicitações de adoção pendentes, que o admin pode aprovar ou recusar

// Novidades principais que a gente fez, pra lembrar:
// - Agora, quando alguém cadastra um animal, ele não aparece direto no index, só depois do admin aprovar.
// - Admin vê a lista dos “anúncios pendentes” e pode aprovar (coloca status = ‘disponível’) ou recusar (deleta).
// - Tudo que admin faz (aprovar/rejeitar) aparece na tela aquela barrinha verde/vermelha lá em cima (mensagem de sucesso/erro).
// - Painel de admin agora mostra também gráficos e relatórios em tempo real, puxando esses dados acima.


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
            <img id="LogoAdmin" src="<?= IMG_PATH ?>/AvatarF.png" alt="Logo">
            <span>Administrador</span>
        </div>
        <nav>
            <ul>
                <li><a href="#" onclick="mostrarView('dashboard')"><i class="bi bi-speedometer2"></i> Dashboard</a></li>
                <li><a href="#" onclick="mostrarView('usuarios')">Usuários</a></li>
                <li><a href="#" onclick="mostrarView('animais')">Animais</a></li>
                <li><a href="#" onclick="mostrarView('animais-pendentes')">Anúncios Pendentes</a></li>
                <li><a href="#" onclick="mostrarView('adocoes')">Adoções</a></li>
                 <li><a href="#" onclick="mostrarView('form-adocao')">Solicitações de Formulário</a></li>
            </ul>
        </nav>
    </div>

    <div class="main-content">
        <div id="dashboard-view" class="view active">
            <header>
                <h1>Painel Administrativo</h1>
                <div class="user-info">
                    <span>Bem-vindo, Lucas</span>
                    <img src="<?= IMG_PATH ?>/Avatar.png" alt="User">
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
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>Email</th>
                    <th>Telefone</th>
                    <th>Tipo</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($usuarios as $usuario): ?>
                <tr>
                    <td><?= htmlspecialchars($usuario['id']) ?></td>
                    <td><?= htmlspecialchars($usuario['nome']) ?></td>
                    <td><?= htmlspecialchars($usuario['email']) ?></td>
                    <td><?= htmlspecialchars($usuario['telefone']) ?></td>
                    <td><?= htmlspecialchars($usuario['tipo']) ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>


        <div id="animais-view" class="view">
    <h2>Gestão de Animais</h2>
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>Espécie</th>
                    <th>Raça</th>
                    <th>Idade</th>
                    <th>Porte</th>
                    <th>Status</th>
                    <th>Dono</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($animais as $animal): ?>
                <tr>
                    <td><?= htmlspecialchars($animal['id']) ?></td>
                    <td><?= htmlspecialchars($animal['nome']) ?></td>
                    <td><?= htmlspecialchars($animal['especie']) ?></td>
                    <td><?= htmlspecialchars($animal['raca']) ?></td>
                    <td><?= htmlspecialchars($animal['idade']) ?></td>
                    <td><?= htmlspecialchars($animal['porte']) ?></td>
                    <td><?= htmlspecialchars($animal['status']) ?></td>
                    <td><?= htmlspecialchars($animal['dono_nome']) ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<div id="animais-pendentes-view" class="view">
    <h2>Anúncios Pendentes de Aprovação</h2>
    <table class="table">
        <thead>
            <tr>
                <th>ID</th><th>Nome</th><th>Espécie</th><th>Raça</th><th>Porte</th><th>Dono</th><th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($animaisPendentes as $animal): ?>
            <tr>
                <td><?= $animal['id'] ?></td>
                <td><?= htmlspecialchars($animal['nome']) ?></td>
                <td><?= htmlspecialchars($animal['especie']) ?></td>
                <td><?= htmlspecialchars($animal['raca']) ?></td>
                <td><?= htmlspecialchars($animal['porte']) ?></td>
                <td><?= htmlspecialchars($animal['dono_nome']) ?></td>
                <td>
                    <form action="<?= ADMIN_PATH ?>/controller/AdminController.php" method="post" style="display:inline;">
    <input type="hidden" name="acao" value="aprovar_animal">
    <input type="hidden" name="animal_id" value="<?= $animal['id'] ?>">
    <input type="hidden" name="redirect" value="dashboard">
    <button type="submit" class="btn btn-success btn-sm">Aprovar</button>
</form>

<form action="<?= ADMIN_PATH ?>/controller/AdminController.php" method="post" style="display:inline;">
    <input type="hidden" name="acao" value="rejeitar_animal">
    <input type="hidden" name="animal_id" value="<?= $animal['id'] ?>">
    <input type="hidden" name="redirect" value="dashboard">
    <button type="submit" class="btn btn-danger btn-sm">Recusar</button>
</form>

                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>


        <div id="adocoes-view" class="view">
    <h2>Gestão de Adoções</h2>
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Usuário</th>
                    <th>Animal</th>
                    <th>Status</th>
                    <th>Data da Solicitação</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($adocoes as $adocao): ?>
                <tr>
                    <td><?= htmlspecialchars($adocao['id']) ?></td>
                    <td><?= htmlspecialchars($adocao['usuario_nome']) ?></td>
                    <td><?= htmlspecialchars($adocao['animal_nome']) ?></td>
                    <td><?= htmlspecialchars($adocao['status']) ?></td>
                    <td><?= date('d/m/Y', strtotime($adocao['data_solicitacao'])) ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<div id="form-adocao-view" class="view">
  <h2>Solicitações de Adoção Recebidas</h2>
  <div class="table-responsive">
    <table class="table table-bordered table-hover">
      <thead class="table-primary">
        <tr>
          <th>ID</th>
          <th>Usuário</th>
          <th>E-mail</th>
          <th>Motivo da Adoção</th>
          <th>Tela Proteção</th>
          <th>Condomínio Aceita</th>
          <th>Espaço</th>
          <th>Financeiro</th>
          <th>Experiência</th>
          <th>Outros Animais</th>
          <th>Compromisso</th>
          <th>Data</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($solicitacoes as $solicitacao): ?>
          <tr>
            <td><?= htmlspecialchars($solicitacao['id']) ?></td>
            <td><?= htmlspecialchars($solicitacao['nome_usuario']) ?></td>
            <td><?= htmlspecialchars($solicitacao['email_usuario']) ?></td>
            <td><?= nl2br(htmlspecialchars($solicitacao['motivo_adocao'])) ?></td>
            <td><?= htmlspecialchars($solicitacao['possui_tela_protecao']) ?></td>
            <td><?= htmlspecialchars($solicitacao['condominio_aceita']) ?></td>
            <td><?= htmlspecialchars($solicitacao['espaco_para_animal']) ?></td>
            <td><?= htmlspecialchars($solicitacao['condicoes_financeiras']) ?></td>
            <td><?= htmlspecialchars($solicitacao['experiencia_animais']) ?></td>
            <td><?= htmlspecialchars($solicitacao['outros_animais']) ?></td>
            <td><?= htmlspecialchars($solicitacao['compromisso']) ?></td>
            <td><?= date('d/m/Y H:i', strtotime($solicitacao['criado_em'])) ?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
    <?php if (empty($solicitacoes)): ?>
      <p class="text-muted">Nenhuma solicitação recebida ainda.</p>
    <?php endif; ?>
  </div>
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
<script>
    // Remove os toasts automaticamente após a animação
document.addEventListener('DOMContentLoaded', function() {
    const toasts = document.querySelectorAll('.toast');
    
    toasts.forEach(toast => {
        // Remove o toast após 3 segundos (tempo da animação)
        setTimeout(() => {
            toast.remove();
            // Remove o container se não houver mais toasts
            const container = document.querySelector('.toast-container');
            if (container && container.children.length === 0) {
                container.remove();
            }
        }, 3000);
    });
});
</script>
<script>
function mostrarView(nome) {
    document.querySelectorAll('.view').forEach(function(view) {
        view.classList.remove('active');
    });
    var v = document.getElementById(nome + '-view');
    if (v) v.classList.add('active');
}
</script>

</body>
</html>
