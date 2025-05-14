<?php
// Página de detalhes de um animal específico
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../config/database.php';

// Captura o ID via GET e valida
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id <= 0) {
    header('Location: index.php');
    exit;
}

try {
    // Busca o registro pelo ID
    $stmt = $pdo->prepare(
        "SELECT id, nome, especie, raca, idade, porte,
                historico_medico, doencas_cronicas, comportamento,
                foto_blob
         FROM animais
         WHERE id = :id"
    );
    $stmt->execute([':id' => $id]);
    $animal = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$animal) {
        // Animal não encontrado
        header('HTTP/1.0 404 Not Found');
        echo '<h1>Animal não encontrado</h1>';
        exit;
    }
} catch (PDOException $e) {
    die('Erro ao carregar detalhes: ' . htmlspecialchars($e->getMessage()));
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Detalhes de <?= htmlspecialchars($animal['nome']) ?></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
  <div><?php require ROOT_PATH . '/app/views/header.php'; ?></div>
  <main class="container py-5">
    <a href="<?=BASE_PATH?>/index.php" class="btn btn-secondary mb-4">&larr; Voltar</a>
    <div class="card mx-auto" style="max-width: 600px;">
      <?php if (!empty($animal['foto_blob'])): ?>
        <?php $base64 = base64_encode($animal['foto_blob']); ?>
        <img src="data:image/jpeg;base64,<?= $base64 ?>" class="card-img-top" alt="Foto de <?= htmlspecialchars($animal['nome']) ?>">
      <?php endif; ?>
      <div class="card-body">
        <h2 class="card-title"><?= htmlspecialchars($animal['nome']) ?></h2>
        <p class="card-text"><strong>Espécie:</strong> <?= htmlspecialchars($animal['especie']) ?></p>
        <p class="card-text"><strong>Raça:</strong> <?= htmlspecialchars($animal['raca']) ?></p>
        <p class="card-text"><strong>Idade:</strong> <?= htmlspecialchars($animal['idade']) ?> anos</p>
        <p class="card-text"><strong>Porte:</strong> <?= htmlspecialchars($animal['porte']) ?></p>
        <?php if (!empty($animal['historico_medico'])): ?>
          <p class="card-text"><strong>Histórico Médico:</strong><br><?= nl2br(htmlspecialchars($animal['historico_medico'])) ?></p>
        <?php endif; ?>
        <?php if (!empty($animal['doencas_cronicas'])): ?>
          <p class="card-text"><strong>Doenças Crônicas:</strong><br><?= nl2br(htmlspecialchars($animal['doencas_cronicas'])) ?></p>
        <?php endif; ?>
        <?php if (!empty($animal['comportamento'])): ?>
          <p class="card-text"><strong>Descrição:</strong><br><?= nl2br(htmlspecialchars($animal['comportamento'])) ?></p>
        <?php endif; ?>
      </div>
    </div>
  </main>
   <?php include ROOT_PATH . '/app/views/footer.php'; ?>
</body>
</html>
