<?php
require_once __DIR__ . '/../config.php';
if (session_status() === PHP_SESSION_NONE) session_start();

// Exibe mensagens
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
    <title>Recuperar Senha - Petch</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?= ASSETS_PATH ?>/styleRecuperarSenha.css">
</head>
<body>
    <div class="container mt-5">
        <?php if (!isset($_GET['token'])): ?>
            <!-- Formulário de solicitação -->
            <form method="POST" action="<?= CONTROLLERS_PATH ?>/PasswordResetController.php">
                <input type="hidden" name="acao" value="solicitar_reset">
                <div class="mb-3">
                    <label class="form-label">Digite seu e-mail cadastrado:</label>
                    <input type="email" name="email" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-primary">Enviar Link</button>
            </form>
        <?php else: ?>
            <!-- Formulário de nova senha -->
            <form method="POST" action="<?= CONTROLLERS_PATH ?>/PasswordResetController.php">
                <input type="hidden" name="acao" value="redefinir_senha">
                <input type="hidden" name="token" value="<?= htmlspecialchars($_GET['token']) ?>">
                <div class="mb-3">
                    <label class="form-label">Nova Senha:</label>
                    <input type="password" name="senha" class="form-control" required minlength="6">
                </div>
                <div class="mb-3">
                    <label class="form-label">Confirme a Nova Senha:</label>
                    <input type="password" name="confirmacao_senha" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-primary">Redefinir Senha</button>
            </form>
        <?php endif; ?>
    </div>
</body>
</html>