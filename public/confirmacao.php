<?php
require_once __DIR__ . '/../config.php';
// Inicia a sessão apenas se ainda não estiver ativa
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Só aceita requisições POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ' . PUBLIC_PATH . '/cadastro_animal.php');
    exit;
}

// Captura campos do form original
$nome        = trim($_POST['nome_animal'] ?? '');
$especie     = trim($_POST['especie'] ?? '');
$raca        = trim($_POST['raca'] ?? '');
$idade       = trim($_POST['idade'] ?? '');
$porte       = trim($_POST['porte'] ?? '');
$descricao   = trim($_POST['descricao'] ?? '');
// Histórico médico: checkboxes e descrição de doenças crônicas
$diseaseTypes  = $_POST['historico_medico'] ?? [];
$diseaseDesc   = trim($_POST['descricao_doencas'] ?? '');

// Monta string para envio ao controller (combina todos)
$historyItems = [];
foreach ($diseaseTypes as $type) {
    if ($type === 'Doenças crônicas') {
        if ($diseaseDesc !== '') {
            $historyItems[] = $diseaseDesc;
        }
    } else {
        $historyItems[] = $type;
    }
}
$historico_medico = implode(', ', $historyItems);

// Trata upload da foto e guarda em public/tmp
$tmpDir = __DIR__ . '/tmp/';
if (!is_dir($tmpDir)) {
    mkdir($tmpDir, 0777, true);
}
if (!isset($_FILES['foto']) || $_FILES['foto']['error'] !== UPLOAD_ERR_OK) {
    $_SESSION['erro'] = 'Falha no upload da foto.';
    header('Location: ' . PUBLIC_PATH . '/cadastro_animal.php');
    exit;
}
$tmpName  = uniqid('foto_', true) . '_' . basename($_FILES['foto']['name']);
$savePath = $tmpDir . $tmpName;
if (!move_uploaded_file($_FILES['foto']['tmp_name'], $savePath)) {
    $_SESSION['erro'] = 'Não foi possível salvar a foto.';
    header('Location: ' . PUBLIC_PATH . '/cadastro_animal.php');
    exit;
}

// Armazena na sessão para confirmação e envio ao controller
$_SESSION['animal_confirmation'] = [
    'nome'             => $nome,
    'especie'          => $especie,
    'raca'             => $raca,
    'idade'            => $idade,
    'porte'            => $porte,
    'historico_medico' => $historico_medico,
    'descricao'        => $descricao,
    'foto_name'        => $tmpName,
    'foto_path'        => $savePath,
];

// Prepara arrays para exibição separada
$generalHistoryItems = [];
foreach ($diseaseTypes as $type) {
    if ($type !== 'Doenças crônicas') {
        $generalHistoryItems[] = $type;
    }
}
$chronicDiseaseDesc = $diseaseDesc;
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Confirmação de Dados</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
  <main class="container py-4">
    <h1>Confira seus dados antes de publicar</h1>
    <table class="table table-bordered">
      <tr><th>Nome</th>      <td><?= htmlspecialchars($nome) ?></td></tr>
      <tr><th>Espécie</th>   <td><?= htmlspecialchars($especie) ?></td></tr>
      <?php if ($raca !== ''): ?>
      <tr><th>Raça</th>      <td><?= htmlspecialchars($raca) ?></td></tr>
      <?php endif; ?>
      <?php if ($idade !== ''): ?>
      <tr><th>Idade</th>     <td><?= htmlspecialchars($idade) ?></td></tr>
      <?php endif; ?>
      <tr><th>Porte</th>     <td><?= htmlspecialchars($porte) ?></td></tr>
      <?php if (!empty($generalHistoryItems)): ?>
      <tr><th>Histórico Médico</th>
        <td><?= nl2br(htmlspecialchars(implode(', ', $generalHistoryItems))) ?></td>
      </tr>
      <?php endif; ?>
      <?php if ($chronicDiseaseDesc !== ''): ?>
      <tr><th>Doenças Crônicas</th>
        <td><?= nl2br(htmlspecialchars($chronicDiseaseDesc)) ?></td>
      </tr>
      <?php endif; ?>
      <tr><th>Descrição</th> <td><?= nl2br(htmlspecialchars($descricao)) ?></td></tr>
      <tr><th>Foto</th>
        <td>
          <img src="<?= PUBLIC_PATH ?>/tmp/<?= htmlspecialchars($tmpName) ?>" alt="Foto do animal" style="max-width:200px;">
        </td>
      </tr>
    </table>

    <form method="POST" action="<?= PUBLIC_PATH ?>/divulgar-animal.php" enctype="multipart/form-data">
      <input type="hidden" name="acao" value="cadastrar_animal">
      <?php
      $nameMap = [
        'nome'             => 'nome',
        'especie'          => 'especie',
        'raca'             => 'raca',
        'idade'            => 'idade',
        'porte'            => 'porte',
        'historico_medico' => 'historico_medico',
        'descricao'        => 'descricao',
      ];
      foreach ($_SESSION['animal_confirmation'] as $key => $val) {
          if (in_array($key, ['foto_name', 'foto_path'])) continue;
          $inputName = $nameMap[$key] ?? $key;
      ?>
        <input type="hidden" name="<?= $inputName ?>" value="<?= htmlspecialchars($val) ?>">
      <?php } ?>
      <input type="hidden" name="caminho_foto" value="<?= htmlspecialchars($_SESSION['animal_confirmation']['foto_path']) ?>">

      <button type="submit" class="btn btn-success">Confirmar e Publicar</button>
      <a href="<?= PUBLIC_PATH ?>/cadastro_animal.php" class="btn btn-secondary">Voltar e Editar</a>
    </form>
  </main>
  
</body>
</html>
