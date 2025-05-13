<?php
require_once __DIR__ . '/../config.php';
require_once dirname(__DIR__) . '/config/database.php';
require_once dirname(__DIR__) . '/app/models/Animal.php'; // Supondo que você tenha um model Animal.php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Verifica se o ID do animal foi passado
if (!isset($_GET['id'])) {
    echo "Animal não encontrado.";
    exit;
}

// Busca o animal pelo ID
$animalModel = new Animal($pdo);
$animal = $animalModel->getById($_GET['id']);

if (!$animal) {
    echo "Animal não encontrado.";
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <title>Visualizar Animal - Petch</title>
  <link rel="stylesheet" href="<?= ASSETS_PATH ?>/VisualizacaoAnimal.css">
</head>
<body>

<header class="topbar">
  <div class="logo">
    <img src="logo-petch.png" alt="Petch">
  </div>
  <div class="menu">Quem somos</div>
  <div class="user-icon">👤</div>
</header>

<main class="container">
  <h1><?= htmlspecialchars($animal['nome']) ?></h1>
  <p><strong>Espécie:</strong> <?= htmlspecialchars($animal['especie']) ?></p>
  <p><strong>Raça:</strong> <?= htmlspecialchars($animal['raca']) ?></p>
  <p><strong>Idade:</strong> <?= htmlspecialchars($animal['idade']) ?></p>
  <p><strong>Porte:</strong> <?= htmlspecialchars($animal['porte']) ?></p>

  <?php if (!empty($animal['foto'])): ?>
    <img src="<?= '/uploads/' . htmlspecialchars($animal['foto']) ?>" alt="Foto do animal" style="max-width: 300px;">
  <?php endif; ?>

  <h3>Histórico Médico</h3>
  <ul>
    <?php
    $historico = explode(',', $animal['historico_medico']); // ou json_decode, dependendo de como foi salvo
    foreach ($historico as $item) {
        echo '<li>' . htmlspecialchars(trim($item)) . '</li>';
    }
    ?>
  </ul>

  <?php if (in_array('Doenças crônicas', $historico)): ?>
    <p><strong>Descrição das doenças:</strong><br>
      <?= nl2br(htmlspecialchars($animal['descricao_doencas'])) ?>
    </p>
  <?php endif; ?>
</main>

<footer class="footer">
  <img src="logo-petch.png" alt="Petch">
  <p>Todos os direitos reservados</p>
</footer>

<?php include ROOT_PATH . '/app/views/footer.php'; ?>
</body>
</html>
