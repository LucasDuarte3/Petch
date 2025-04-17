<?php
require_once __DIR__ . '/../../config.php'; // Importa routes.php
// Inicia a sessão apenas se ainda não estiver ativa
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// Exibir ao usuario mensagens de erro/sucesso
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
    <link rel="stylesheet" href="<?= ASSETS_PATH ?>/styleHeader.css">
</head>
<body>
<nav>

    <a href="#">Contato</a>
    <a href="#">Sobre Nós</a>
    <a href="#">Animais</a>
    <a href="<?=PUBLIC_PATH?>/login.php">Doar Animal</a>
    <a href="/index.php">Inicio</a>
    
</nav>
</div>
</body>
</html>