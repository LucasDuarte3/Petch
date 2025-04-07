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
        <h1 class="text-center mb-4">Cadastre seu animal</h1>
        <form action="<?= CONTROLLERS_PATH ?>/AnimalController.php" method="POST">
            <input type="hidden" name="acao" value="cadastrar_animal">
            
            <div class="mb-3">
                <label class="form-label">Nome Completo*:</label>
                <input type="text" name="nome" class="form-control" required>
            </div>
            
            <div class="mb-3">
                <label class="form-label">Espécie*:</label>
                <input type="text" name="especie" class="form-control" required>
            </div>
            
            <div class="mb-3">
                <label class="form-label">Raça:</label>
                <input type="text" name="raca" class="form-control">
            </div>
            
            <div class="mb-3">
                <label class="form-label">Idade:</label>
                <input type="number" name="idade" class="form-control">
            </div>
            
            <div class="mb-3">
                <label class="form-label">Porte*:</label>
                <input type="text" name="porte" class="form-control" required>
            </div>
            
            <div class="mb-3">
                <label class="form-label">Descricao:</label>
                <input type="text" name="descricao" class="form-control">
            </div>
            
            <div class="mb-3">
                <label class="form-label">Histórico Médico:</label>
                <input type="text" name="historico_medico" class="form-control">
            </div>
            
            <div class="mb-3">
                <label class="form-label">Status*:</label>
                <select name="status" class="form-select" aria-label="Default select example" required>
                    <option value = "">-</option>
                    <option value="disponivel">Disponível para adoção</option>
                    <option value="em_processo">Em processo de adoção</option>
                    <option value="adotado">Adotado</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Caminho do arquivo da foto:</label>
                <input type="text" name="caminho_foto" class="form-control">
            </div>

            <div class="mb-3">
                <label class="form-label">Localidade:</label>
                <input type="text" name="localidade" class="form-control">
            </div>

            <div class="d-grid gap-2">
                <button type="submit" class="btn-custom">Cadastrar</button>
            </div>

        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>