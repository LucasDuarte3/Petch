<?php
require_once __DIR__ . '/../config.php'; // Importa routes.php

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
    <title>Cadastro - Petch</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="<?= ASSETS_PATH ?>/style.css">
</head>
<body>
<!-- formulario de cadastro -->
    <div class="auth-container fade-in">
        <h1 class="text-center mb-4">Crie sua conta</h1>
        <form action="<?= CONTROLLERS_PATH ?>/UserController.php" method="POST">
            <input type="hidden" name="acao" value="cadastrar">
            
            <!-- Campos obrigatórios -->
            <div class="mb-3">
                <label class="form-label">Nome Completo:</label>
                <input type="text" name="nome" class="form-control" required>
            </div>
            
            <div class="mb-3">
                <label class="form-label">Email:</label>
                <input type="email" name="email" class="form-control" required>
            </div>
            
            <div class="mb-3">
                <label class="form-label">Senha:</label>
                <input type="password" name="senha" class="form-control" required minlength="6">
            </div>
            
            <!-- CPF/CNPJ obrigatório -->
            <div class="mb-3">
                <label class="form-label">CPF ou CNPJ:</label>
                <input type="text" name="cpf_cnpj" class="form-control" 
                    placeholder="Somente números" required
                    pattern="\d{11,14}" 
                    title="Digite 11 dígitos para CPF ou 14 para CNPJ">
                <small class="text-muted">Ex: 12345678901 (CPF) ou 12345678000199 (CNPJ)</small>
            </div>
            
            <!-- Campos opcionais -->
            <div class="mb-3">
                <label class="form-label">Telefone:</label>
                <input type="text" name="telefone" class="form-control">
            </div>
            
            <div class="mb-3">
                <label class="form-label">Endereço:</label>
                <input type="text" name="endereco" class="form-control">
            </div>
            
            <div class="d-grid gap-2">
                <button type="submit" class="btn-custom">Cadastrar</button>
            </div>

            <div class="mt-3 text-center">
                <a href="<?= PUBLIC_PATH ?>/login.php" class="text-decoration-none link-custom">
                    Já tem conta? <span class="fw-bold">Faça login!</span>
                </a>
            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>