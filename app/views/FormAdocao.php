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
  <title>Formulário de Adoção - Petch</title>
  <link rel="stylesheet" href="<?= ASSETS_PATH ?>/FormAdocao.css">
</head>
<body>

  <header class="topbar">
    <div class="logo">❤️ Petch</div>
    <div class="menu">Quem somos</div>
    <div class="user-icon">👤</div>
  </header>

  <main class="container">
    <h1>Olá, usuário!</h1>
    <p class="location">cidade</p>

    <p class="info">
      Preencha o formulário abaixo para dar início à sua solicitação*<br>
      Fique de olho no e-mail e acompanhe o status da sua solicitação*
    </p>
    
    <form method="POST" action="<?= CONTROLLERS_PATH ?>#"> <!-- colocar direcionamento --> 

      <label>Nome do animal:*
        <input type="text" name="nome_animal" placeholder="Nome do animal" required>
      </label>

      <label>Espécie:*
        <select name="especie" required>
          <option value="">Selecione a espécie</option>
          <option value="Cachorro">Cachorro</option>
          <option value="Gato">Gato</option>
        </select>
      </label>

      <label>Raça:*
        <select name="raca" required>
          <option value="">Selecione a raça</option>
          <option value="SRD">SRD</option>
          <option value="Outro">Outro</option>
        </select>
      </label>

      <label>Nome:*
        <input type="text" name="nome" required>
      </label>

      <label>E-mail:*
        <input type="email" name="email" required>
      </label>

      <label>Telefone:*
        <input type="text" name="telefone" required>
      </label>

      <label>Endereço:*
        <input type="text" name="endereco" required>
      </label>

      <label>Casa ou apartamento?*
        <select name="tipo_moradia" required>
          <option value="">Selecione se mora em casa ou apartamento</option>
          <option value="Casa">Casa</option>
          <option value="Apartamento">Apartamento</option>
        </select>
      </label>

      <p class="info">
        As próximas perguntas são essenciais para confirmação da sua solicitação de adoção. Em caso de recusa ou aceite da solicitação você receberá um e-mail. Fique atento aos próximos passos do status da sua solicitação e acompanhe também pelo seu e-mail.
      </p>

      <label>Em caso de morar em apartamento, todas as janelas possuem tela de proteção?
        <select name="tela_protecao" required>
          <option value="">Selecione</option>
          <option value="Sim">Sim</option>
          <option value="Não">Não</option>
        </select>
      </label>

      <label>Se mora em condomínio ou aluguel, é permitido ter pets (mesmo de médio ou grande porte)?
        <select name="condominio_permite" required>
          <option value="">Selecione</option>
          <option value="Sim">Sim</option>
          <option value="Não">Não</option>
        </select>
      </label>

      <label>Seu lar tem espaço suficiente para o animal (porte do animal)?
        <select name="espaco_suficiente" required>
          <option value="">Selecione</option>
          <option value="Sim">Sim</option>
          <option value="Não">Não</option>
        </select>
      </label>

      <label>Você possui condições de arcar com segurança, alimentação e cuidados médicos?
        <select name="condicoes_financeiras" required>
          <option value="">Selecione</option>
          <option value="Sim">Sim</option>
          <option value="Não">Não</option>
        </select>
      </label>

      <label>
        Me comprometo a realizar uma adoção responsável, sou consciente de que ao adotar esse animal estou me comprometendo a manter sua integridade e bem estar e que estou sujeito à Lei nº 9.605/1998 que prevê pena de detenção além de multa em caso de maus tratos ou abandono.
        <select name="compromisso" required>
          <option value="">Selecione</option>
          <option value="Sim">Sim</option>
          <option value="Não">Não</option>
        </select>
      </label>

      <input type="hidden" name="acao" value="#"> <!-- colocar ação do controller -->

      <button type="submit">Enviar</button> <!-- colocar ação do controller -->
    <!--  <button type="reset">Limpar</button> Colocar um botão que limpa os dados -->
    </form>
  </main>

  <footer>
    <div>
      ❤️ Petch <br>
      Todos os direitos reservados
    </div>
  </footer>

</body>
</html>
