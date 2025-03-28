<?php
session_start();
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
    <title>Cadastro - Petch</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="/frontend/assets/css/styles.css">
</head>
<body>
<!-- formulario de cadastro -->
    <div class="auth-container fade-in">
        <h1 class="text-center mb-4">Crie sua conta</h1>
        <form action="/backend/controllers/UserController.php" method="POST">
            <input type="hidden" name="acao" value="cadastrar">
            
            <div class="mb-3">
                <label class="form-label">Digite seu Nome Completo:</label>
                <input type="text" name="nome" class="form-control" required>
            </div>
            
            <div class="mb-3">
                <label class="form-label">Digite seu E-mail:</label>
                <input type="email" name="email" class="form-control" required>
            </div>
            
            <div class="mb-3">
                <label class="form-label">Digite sua Senha:</label>
                <input type="password" name="senha" class="form-control" required minlength="6">
            </div>
            
            <div class="d-grid gap-2">
                <button type="submit" class="btn-custom">Cadastrar</button>
            </div>

            <div class="mt-3 text-center">
                <a href="/frontend/views/login.php" class="text-decoration-none link-custom">
                    Já tem conta? <span class="fw-bold">Faça login</span>
                </a>
            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>