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
// Importa o banco e o model de usuários
require_once dirname(__DIR__) . '/config/database.php';
require_once dirname(__DIR__) . '/app/models/User.php';

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

  <header>
    <h1>Petch</h1>
    <div><img src="https://via.placeholder.com/40" alt="avatar" style="border-radius:50%;"></div>
  </header>

  <main class="container">
    <!-- <div class="alert">✔️ Usuário atualizado com sucesso</div>-->

    <div class="breadcrumb">Home / <?= htmlspecialchars($usuario ['nome']) ?></div>


    <div class="profile">
      <div class="avatar"></div>
      <div class="info">
      <h2><?= htmlspecialchars($usuario ['nome']) ?></h2>
        <p>CPF: <?= htmlspecialchars($usuario ['cpf_cnpj']) ?></p>
        <p>E-mail: <?= htmlspecialchars($usuario ['email']) ?></p>
        <p>Telefone: <?= htmlspecialchars($usuario ['telefone']) ?> <span class="edit">✏️</span></p>
        <p>Endereço: <?= htmlspecialchars($usuario ['endereco']) ?> <span class="edit">✏️</span></p>
      </div>
    </div>


    <div class="metrics">
      <div>
        <strong>0</strong>
        <p>Animais divulgados</p>
      </div>
      <div>
        <strong>0</strong>
        <p>Animais adotados</p>
      </div>
    </div>

    <form action="<?= PUBLIC_PATH ?>/cadastro_animal.php" method="get">
    <button type="submit" class="btn">Cadastrar novo animal</button>
    </form>

  <footer>
  <?php include ROOT_PATH . '/app/views/footer.php'; ?>
  </footer>
  

</body>
</html>