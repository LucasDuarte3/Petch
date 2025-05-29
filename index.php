<?php
// Página inicial: lista de animais cadastrados
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/config/database.php';

try {
    // Busca todos os animais ordenados por mais recentes
    $stmt = $pdo->query("SELECT id, nome, especie, raca, idade, porte, descricao, foto_blob 
                     FROM animais 
                     WHERE status = 'disponível'
                     ORDER BY id DESC");
    $animais = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die('Erro ao carregar animais: ' . htmlspecialchars($e->getMessage()));
}
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Peth - Adote um Animal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="<?= ASSETS_PATH ?>/styleIndex.css">
</head>

<body>
    <div><?php include 'app/views/header.php'; ?></div>

    <div class="hero-section">
        <img src="<?= IMG_PATH ?>/imagemFundoInicial.jpg" alt="Banner principal" class="hero-image">
        <div class="hero-content">
            <h1>Encontre seu novo melhor amigo</h1>
            <p class="hero-subtitle">Adote um animal de estimação e transforme duas vidas: a dele e a sua.</p>
            <div class="hero-buttons">
  <?php if (isset($_SESSION['usuario'])): ?>
    <!-- Usuário logado -->
    <a href="<?= BASE_PATH ?>/public/formadocao.php" class="btn btn-primary btn-lg">
      Adotar um Pet
    </a>
    <a href="<?= BASE_PATH ?>/public/cadastro_animal.php" class="btn btn-primary btn-lg">
      Doar um Pet
    </a>
  <?php else: ?>
    <!-- Usuário não logado -->
    <a href="<?= BASE_PATH ?>/public/login.php" class="btn btn-primary btn-lg">
      Adotar um Pet
    </a>
    <a href="<?= BASE_PATH ?>/public/login.php" class="btn btn-primary btn-lg">
      Doar um Pet
    </a>
  <?php endif; ?>
</div>
        </div>
    </div>

    <div class="pets-cards">
          <main class="container py-5">
    <h1 class="mb-4">Animais para Adoção</h1>
    <?php if (empty($animais)): ?>
      <p class="text-muted">Nenhum animal cadastrado no momento.</p>
    <?php else: ?>
      <div class="row">
    <?php foreach ($animais as $animal): ?>
          <div class="col-md-4 mb-4">
            <div class="card h-100">
              <?php if (!empty($animal['foto_blob'])): ?>
                <?php $base64 = base64_encode($animal['foto_blob']); ?>
                <img
                  src="data:image/jpeg;base64,<?= $base64 ?>"
                  class="card-img-top"
                  alt="Foto de <?= htmlspecialchars($animal['nome']) ?>"
                >
              <?php endif; ?>
              <div class="card-body">
                <h5 class="card-title"><?= htmlspecialchars($animal['nome']); ?></h5>
                <p class="card-text"><?= nl2br(htmlspecialchars($animal['descricao'])); ?></p>
                <ul class="list-unstyled">
                  <li><strong>Espécie:</strong> <?= htmlspecialchars($animal['especie']); ?></li>
                  <li><strong>Raça:</strong> <?= htmlspecialchars($animal['raca']); ?></li>
                  <li><strong>Idade:</strong> <?= htmlspecialchars($animal['idade']); ?></li>
                  <li><strong>Porte:</strong> <?= htmlspecialchars($animal['porte']); ?></li>
                        </ul>
                        <a href="<?= PUBLIC_PATH ?>/detalhes_animal.php?id=<?= $animal['id'] ?>" class="btn btn-primary">Ver Detalhes</a>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>

    <div><?php include 'app/views/footer.php'; ?></div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>

</html>