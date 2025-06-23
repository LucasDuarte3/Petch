<?php
require_once __DIR__ . '/../config.php'; // Importa routes.php

// Inicia a sessão apenas se ainda não estiver ativa
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// Exibir ao usuario mensagens de erro/sucesso
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
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consulta - Petch</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="<?= ASSETS_PATH ?>/filtro.css">
</head>
<body>
    <div><?php require ROOT_PATH . '/app/views/header.php'; ?></div>
    <div class="auth-container fade-in">
        <h1 class="text-center mb-4">Consulta de animais</h1>
        <form action="<?= CONTROLLERS_PATH ?>/ConsultarAnimalController.php" method="POST">
            <input type="hidden" name="acao" value="consultar">
            
            <div class="mb-3">
                <label class="form-label">Espécie:</label>
                <select name="especie" class="form-control">
                    <option value="">Selecione...</option>
                    <option value="cachorro">Cachorro</option>
                    <option value="gato">Gato</option>
                    <option value="passaro">Pássaro</option>
                    <option value="outro">Outro</option>
                </select>
            </div>
            
            <div class="mb-3">
                <label class="form-label">Raça:</label>
                <input type="text" name="raca" class="form-control">
            </div>
            
            <div class="mb-3">
                <label class="form-label">Idade (anos):</label>
                <input type="number" name="idade" min="0" max="30" class="form-control">
            </div>
            
            <div class="mb-3">
                <label class="form-label">Porte:</label>
                <select name="porte" class="form-control">
                    <option value="">Selecione...</option>
                    <option value="pequeno">Pequeno</option>
                    <option value="medio">Médio</option>
                    <option value="grande">Grande</option>
                </select>
            </div>
            
            <div class="d-grid gap-2">
                <button type="submit" class="btn-custom">Consultar</button>
            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js">
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