<?php
require_once __DIR__ . '/../config.php'; // Importa routes.php

// Inicia a sessão apenas se ainda não estiver ativa
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
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link href="<?= ASSETS_PATH ?>/AdminLogin.css" rel="stylesheet">
</head>
<body>
    <?php require ROOT_PATH . '/app/views/header.php'; ?>
    <div class="login-container">
        <div class="login-box">
            <div class="avatar-container">
                <img src="<?= IMG_PATH ?>/AvatarF.png" alt="Avatar" class="avatar">
            </div>
            <h2>Login Administrativo</h2>
            <form action="<?= ADMIN_PATH ?>/dashboard.php" method="POST">
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" required>
                </div>
                <div class="form-group">
                    <label for="senha">Senha:</label>
                    <input type="password" id="senha" name="senha" required>
                </div>
                <button type="submit" class="btn-login">Entrar</button>
                <a class="link-login" href="">Esqueceu sua senha?</a>
            </form>
        </div>
    </div>
    <div><?php require ROOT_PATH . '/app/views/footer.php'; ?></div>

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
</body>
</html>