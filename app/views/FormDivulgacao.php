<?php
require_once __DIR__ . '/../../config.php'; // Importa routes.php
// Inicia a sessão apenas se ainda não estiver ativa
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// Exibir ao usuario mensagens de erro/sucesso
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
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <title>Divulgação do Animal - Petch</title>
  <link rel="stylesheet" href="<?= ASSETS_PATH ?>/FormDivulgacao.css">
</head>
<body>

  <!-- Topo -->
  <header class="topbar">
    <div class="logo">
      <img src="logo-petch.png" alt="Petch">
    </div>
    <div class="menu">Quem somos</div>
    <div class="user-icon">👤</div>
  </header>

  <!-- Formulário -->
  <main class="container">
    <h1>Olá, usuário!</h1>
    <p class="subtitle">Responsável pelo animal</p>

    <p><strong style="color: #0047a0;">Faça uma divulgação do animal aqui:</strong></p>

    <form method="POST" action="/divulgar-animal" enctype="multipart/form-data">
      <label>Nome do animal:*<br>
        <input type="text" name="nome_animal" placeholder="Nome do animal" required>
      </label>

      <label>Espécie:*<br>
        <select name="especie" required>
          <option value="">Selecione a espécie</option>
          <option value="Cachorro">Cachorro</option>
          <option value="Gato">Gato</option>
        </select>
      </label>

      <label>Raça:*<br>
        <select name="raca" required>
          <option value="">Selecione a raça</option>
          <!-- Inserir opções dinamicamente, se necessário -->
        </select>
      </label>

      <label>Idade:*<br>
        <input type="text" name="idade" placeholder="Idade" required>
      </label>

      <label>Porte:*<br>
        <select name="porte" required>
          <option value="">Selecione o porte</option>
          <option value="Pequeno">Pequeno</option>
          <option value="Médio">Médio</option>
          <option value="Grande">Grande</option>
        </select>
      </label>

      <label>Foto:*<br>
        <input type="file" name="foto" required>
      </label>

      <label>Histórico médico:<br>
        <select name="historico_medico">
          <option value="">O animal é castrado?</option>
          <option value="Sim">Sim</option>
          <option value="Não">Não</option>
        </select>
      </label>

      <label>Descrição de comportamento / necessidades especiais e outras informações importantes:*<br>
        <textarea name="descricao" placeholder="Comportamento / necessidades especiais / histórico médico." required></textarea>
      </label>

      <label>Status:*<br>
        <select name="status" required>
          <option value="">Selecione o status</option>
          <option value="Disponível">Disponível</option>
          <option value="Em adoção">Em processo de adoção</option>
          <option value="Adotado">Adotado</option>
        </select>
      </label>

      <button type="submit">Publicar</button>
    </form>
  </main>

  <!-- Rodapé -->
  <footer class="footer">
    <img src="logo-petch.png" alt="Petch">
    <p>Todos os direitos reservados</p>
  </footer>

</body>
</html>
