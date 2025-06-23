<?php
require_once __DIR__ . '/../config.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['animais_para_deletar'])) {
    header("Location: " . PUBLIC_PATH . "/deletar_animal.php");
    exit;
}

$animais = $_SESSION['animais_para_deletar'];
unset($_SESSION['animais_para_deletar']);
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmar Exclusão - Petch</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?= ASSETS_PATH ?>/style.css">
    <style>
        .delete-card {
            border-left: 4px solid #dc3545;
            transition: all 0.3s;
        }
        .delete-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(220, 53, 69, 0.1);
        }
        .btn-confirm-delete {
            background-color: #dc3545;
            color: white;
            font-weight: bold;
            transition: all 0.3s;
        }
        .btn-confirm-delete:hover {
            background-color: #bb2d3b;
            transform: scale(1.05);
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center mb-4">Confirmar Exclusão</h1>
        
        <div class="alert alert-warning mb-4">
            <strong>Atenção!</strong> Você está prestes a excluir um animal permanentemente. Esta ação não pode ser desfeita.
        </div>

        <?php if (empty($animais)): ?>
            <div class="alert alert-info">Nenhum animal encontrado com os critérios informados.</div>
        <?php else: ?>
            <form action="<?= CONTROLLERS_PATH ?>/DeletarAnimalController.php" method="POST">
                <input type="hidden" name="acao" value="confirmar_deletar">
                
                <div class="row">
                    <?php foreach ($animais as $animal): ?>
                        <div class="col-md-6 mb-4">
                            <div class="card delete-card h-100">
                                <div class="card-header bg-danger text-white">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="id_animal" 
                                               id="animal_<?= $animal['id'] ?>" value="<?= $animal['id'] ?>" required>
                                        <label class="form-check-label" for="animal_<?= $animal['id'] ?>">
                                            Selecionar para exclusão
                                        </label>
                                    </div>
                                </div>
                                
                                <?php if (!empty($animal['foto_blob'])): ?>
                                    <img src="data:image/jpeg;base64,<?= base64_encode($animal['foto_blob']) ?>" 
                                         class="card-img-top" alt="<?= htmlspecialchars($animal['nome']) ?>">
                                <?php else: ?>
                                    <img src="<?= ASSETS_PATH ?>/imagens/sem-foto.jpg" 
                                         class="card-img-top" alt="Sem foto">
                                <?php endif; ?>
                                
                                <div class="card-body">
                                    <h5 class="card-title"><?= htmlspecialchars($animal['nome']) ?></h5>
                                    <p class="card-text">
                                        <strong>Espécie:</strong> <?= htmlspecialchars($animal['especie']) ?><br>
                                        <strong>Raça:</strong> <?= htmlspecialchars($animal['raca'] ?? 'Não informada') ?><br>
                                        <strong>Idade:</strong> <?= htmlspecialchars($animal['idade'] ?? 'Não informada') ?> anos<br>
                                        <strong>Porte:</strong> <?= htmlspecialchars($animal['porte']) ?><br>
                                        <strong>Status:</strong> <?= htmlspecialchars($animal['status']) ?>
                                    </p>
                                    <?php if (!empty($animal['descricao'])): ?>
                                        <div class="mb-3">
                                            <strong>Descrição:</strong>
                                            <p><?= htmlspecialchars($animal['descricao']) ?></p>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                
                <div class="text-center mt-4 mb-5">
                    <button type="submit" class="btn btn-confirm-delete btn-lg px-5">
                        <i class="fas fa-trash-alt"></i> CONFIRMAR EXCLUSÃO
                    </button>
                    <a href="<?= PUBLIC_PATH ?>/deletar_animal.php" class="btn btn-secondary btn-lg px-5">
                        <i class="fas fa-times"></i> CANCELAR
                    </a>
                </div>
            </form>
        <?php endif; ?>
    </div>

    <!-- Font Awesome para ícones -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/js/all.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>