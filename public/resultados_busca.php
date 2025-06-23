<?php
require_once __DIR__ . '/../config.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['resultados_busca'])) {
    header("Location: " . PUBLIC_PATH . "/consulta_animal.php");
    exit;
}

$resultados = $_SESSION['resultados_busca'];
unset($_SESSION['resultados_busca']);
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resultados da Busca - Petch</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?= ASSETS_PATH ?>/filtro.css">
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center mb-4">Resultados da Busca</h1>
        
        <?php if (empty($resultados)): ?>
            <div class="alert alert-info">Nenhum animal encontrado com os critérios informados.</div>
        <?php else: ?>
            <div class="row">
                <?php foreach ($resultados as $animal): ?>
                    <div class="col-md-4 mb-4">
                        <div class="card">
                            <?php if (!empty($animal['foto_blob'])): ?>
                                <img src="data:image/jpeg;base64,<?= base64_encode($animal['foto_blob']) ?>" class="card-img-top" alt="<?= htmlspecialchars($animal['nome']) ?>">
                            <?php else: ?>
                                <img src="<?= ASSETS_PATH ?>/imagens/sem-foto.jpg" class="card-img-top" alt="Sem foto">
                            <?php endif; ?>
                            <div class="card-body">
                                <h5 class="card-title"><?= htmlspecialchars($animal['nome']) ?></h5>
                                <p class="card-text">
                                    <strong>Espécie:</strong> <?= htmlspecialchars($animal['especie']) ?><br>
                                    <strong>Raça:</strong> <?= htmlspecialchars($animal['raca'] ?? 'Não informada') ?><br>
                                    <strong>Idade:</strong> <?= htmlspecialchars($animal['idade'] ?? 'Não informada') ?> anos<br>
                                    <strong>Porte:</strong> <?= htmlspecialchars($animal['porte']) ?>
                                </p>
                                <a href="<?= PUBLIC_PATH ?>/FormAdocao.php" class="btn btn-primary">Quero Adotar</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        
        <div class="text-center mt-4">
            <a href="<?= PUBLIC_PATH ?>/consulta_animal.php" class="btn btn-secondary">Nova busca</a>
        </div>
    </div>
</body>
</html>