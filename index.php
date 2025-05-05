<?php
require_once __DIR__ . '/config.php'; // Importa routes.php

// Inicia a sessão apenas se ainda não estiver ativa
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
                <a href="<?= BASE_PATH ?>/public/login.php" class="btn btn-primary btn-lg">Adotar um Pet</a>
                <a href="<?= BASE_PATH ?>/public/login.php" class="btn btn-primary btn-lg">Doar um Pet</a>
            </div>
        </div>
    </div>

    <div class="pets-grid">
        <div class="pet-card">
            <div class="pet-name">Theo</div>
            <div class="pet-location">São Paulo</div>
        </div>

        <div class="pet-card">
            <div class="pet-name">Marley</div>
            <div class="pet-location">Brasília</div>
        </div>

        <div class="pet-card">
            <div class="pet-name">Lítica</div>
            <div class="pet-location">Rio de Janeiro</div>
        </div>

        <div class="pet-card">
            <div class="pet-name">Nina</div>
            <div class="pet-location">São Paulo</div>
        </div>

        <div class="pet-card">
            <div class="pet-name">Pipoca</div>
            <div class="pet-location">Brasília</div>
        </div>

        <div class="pet-card">
            <div class="pet-name">Matheo</div>
            <div class="pet-location">Goiânia</div>
        </div>

        <div class="pet-card">
            <div class="pet-name">Cintia</div>
            <div class="pet-location">Belo Horizonte</div>
        </div>

        <div class="pet-card">
            <div class="pet-name">Nina</div>
            <div class="pet-location">São Paulo</div>
        </div>
    </div>

    <div><?php include 'app/views/footer.php'; ?></div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>

</html>