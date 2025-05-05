<?php
require_once __DIR__ . '/../config.php'; // Importa routes.php

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
    <title>Login - Petch</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?= ASSETS_PATH ?>/styleLogin.css">
</head>
<body>
    <div class="image-login">    
        <img src="<?=BASE_PATH?>/images/imgLogin.jpg"alt="" srcset="">
    </div>
    <div class="auth-container fade-in">
        <div class="login-container">
            <h2 class="text-center mb-4">Acesse sua conta</h2>
            
            <form action="<?= CONTROLLERS_PATH ?>/AuthController.php" method="POST">
                <input type="hidden" name="acao" value="login">
                
                <div class="mb-3">
                    <label for="email" class="form-label">Email:</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                </div>
                
                <div class="mb-3">
                    <label for="senha" class="form-label">Senha:</label>
                    <input type="password" class="form-control" id="senha" name="senha" required minlength="6">
                </div>
                
                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-primary">Entrar</button>
                </div>
                
                <div class="mt-3 text-center">
                    <a href="<?= PUBLIC_PATH ?>/cadastro.php">Não tem conta? <span class="fw-bold">Cadastre-se</span></a>
                </div>
                <!-- Falta fazer a pagina de recuperação da senha -->
                <div class="d-grid gap-2 mt-2">
                <button type="button" class="btn btn-secondary" onclick="window.location.href='<?= PUBLIC_PATH ?>/RedefinirSenha.php'">Esqueci minha senha</button> 
                </div>
            </form>
        </div>
    </div>
    <div><?php include ROOT_PATH . '/app/views/footer.php'; ?></div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>