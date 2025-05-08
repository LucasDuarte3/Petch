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
 
  <h1>Olá, <?= htmlspecialchars($usuario['nome']) ?>!</h1>
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
           <input type="text" name="raca" required>
          <!-- Inserir dinamicamente -->
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

      <label>Histórico médico:</label>
<table>
  <tr>
    <td>
      <label>
        <input type="checkbox" name="historico_medico[]" value="Castrado"> Castrado
      </label>
    </td>
    <td>
      <label>
        <input type="checkbox" name="historico_medico[]" value="Vacinado"> Vacinado
      </label>
    </td>
    <td>
      <label>
        <input type="checkbox" name="historico_medico[]" value="Vermifugado"> Vermifugado
      </label>
    </td>
    <td>
      <label>
        <input type="checkbox" id="doencasCheckbox" name="historico_medico[]" value="Doenças crônicas"> Doenças crônicas
      </label>
    </td>
  </tr>
  <tr id="linhaDoencas" style="display: none;">
    <td colspan="4">
      <label>Descreva as doenças crônicas:</label><br>
      <textarea name="descricao_doencas" rows="3" style="width: 100%;" placeholder="Ex: diabetes, doença cardíaca, etc."></textarea>
    </td>
  </tr>
</table>

<script>
  const doencasCheckbox = document.getElementById('doencasCheckbox');
  const linhaDoencas = document.getElementById('linhaDoencas');

  doencasCheckbox.addEventListener('change', function () {
    linhaDoencas.style.display = this.checked ? 'table-row' : 'none';
  });
</script>
  </tr>
  </table>
         

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
