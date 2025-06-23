<?php
require_once __DIR__ . '/../config.php';

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
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exclusão - Petch</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?= ASSETS_PATH ?>/filtro.css">
</head>
<body>
    <div class="auth-container fade-in">
        <h1 class="text-center mb-4">Exclusão de animais</h1>
        <form action="<?= CONTROLLERS_PATH ?>/DeletarAnimalController.php" method="POST">
            <input type="hidden" name="acao" value="buscar">
            
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
                <button type="submit" class="btn-custom btn-danger">Buscar Animal</button>
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