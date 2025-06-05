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
// Importa o banco e o model de usuários
require_once dirname(__DIR__) . '/config/database.php';
require_once dirname(__DIR__) . '/app/models/User.php';
require_once ROOT_PATH . '/app/controllers/ConsultarAnimalController.php';
require_once ROOT_PATH . '/app/controllers/UserController.php';

// Busca os dados do usuário logado
$userModel = new User($pdo);
$usuario = $userModel->getById($_SESSION['usuario']['id']);
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Petch - Perfil</title>
  <link rel="stylesheet" href="<?= ASSETS_PATH ?>/stylePerfil.css">
  
</head>
<body>

<div><?php require ROOT_PATH . '/app/views/header.php'; ?></div>
  <main class="container">
    <!-- <div class="alert">✔️ Usuário atualizado com sucesso</div>-->

    <div class="breadcrumb"><a href="<?= BASE_PATH ?>/index.php">🏠</a></div>


    <div class="profile">
      <div class="avatar"><img src="<?= IMG_PATH ?>/Avatar.png" alt="User"></div>
      <div class="info">
      <h2>Olá, <?= htmlspecialchars($usuario ['nome']) ?></h2>
        <p>CPF: <?= htmlspecialchars($usuario ['cpf_cnpj']) ?></p>
        <p>E-mail: <?= htmlspecialchars($usuario ['email']) ?></p>
        <!-- Telefone com edição -->
        <form method="POST" action="<?= CONTROLLERS_PATH ?>/UserController.php" class="edit-form">
            <input type="hidden" name="acao" value="atualizar_dados">
            <!-- Telefone com edição -->
            <p>
                Telefone: 
                <span class="editable-text" id="telefone-text"><?= htmlspecialchars($usuario['telefone']) ?></span>
                <input type="text" name="telefone" class="editable-input" id="telefone-input" 
                      value="<?= htmlspecialchars($usuario['telefone']) ?>" style="display:none;">
                <span class="edit-icon" onclick="toggleEdit('telefone')">✏️</span>
            </p>
            <!-- Endereço com edição -->
            <p>
                Endereço: 
                <span class="editable-text" id="endereco-text"><?= htmlspecialchars($usuario['endereco']) ?></span>
                <input type="text" name="endereco" class="editable-input" id="endereco-input" 
                      value="<?= htmlspecialchars($usuario['endereco']) ?>" style="display:none;">
                <span class="edit-icon" onclick="toggleEdit('endereco')">✏️</span>
            </p>
            <button type="submit" class="save-btn" style="display:none;">Salvar</button>
        </form>
      </div>
    </div>


    <div class="metrics">
      <div>
          <strong><?= htmlspecialchars($animaisDivulgados) ?></strong>
          <p>Animais divulgados</p>
      </div>
      <div>
          <strong><?= htmlspecialchars($animaisAdotados) ?></strong>
          <p>Animais adotados</p>
      </div>
    </div>

    <form action="<?= PUBLIC_PATH ?>/cadastro_animal.php" method="get">
    <button type="submit" class="btn">Cadastrar novo animal</button>
    </form>

  <footer>
  <?php include ROOT_PATH . '/app/views/footer.php'; ?>
  </footer>
  
<script>
function toggleEdit(field) {
    const textElement = document.getElementById(`${field}-text`);
    const inputElement = document.getElementById(`${field}-input`);
    const saveButton = document.querySelector('.save-btn');
    
    if (textElement.style.display === 'none') {
        textElement.style.display = 'inline';
        inputElement.style.display = 'none';
    } else {
        textElement.style.display = 'none';
        inputElement.style.display = 'inline';
        inputElement.focus();
    }
    
    // Mostra o botão de salvar quando qualquer campo estiver em edição
    const anyFieldEditing = Array.from(document.querySelectorAll('.editable-input'))
        .some(input => input.style.display === 'inline');
    
    saveButton.style.display = anyFieldEditing ? 'inline' : 'none';
}
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