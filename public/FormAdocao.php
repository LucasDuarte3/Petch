<?php
require_once __DIR__ . '/../config.php';
require_once ROOT_PATH . '/app/models/User.php';
require_once ROOT_PATH . '/app/controllers/UserController.php';
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}
if (!isset($_SESSION['usuario'])) {
  header("Location: " . BASE_PATH . "/public/login.php");
  exit;
}
$usuario = $_SESSION['usuario']; // Puxa os dados do usuário logado
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
    <h1>Solicitação de Adoção</h1>
    <p class="info">Olá, <b><?= htmlspecialchars($usuario['nome']) ?></b>!</p>
    <p class="info">Preencha o formulário abaixo para solicitar a adoção.<br>
      Suas informações pessoais já foram preenchidas pelo sistema.</p>

<form method="POST" action="<?= CONTROLLERS_PATH ?>/adocaoController.php">
  <input type="hidden" name="nome" value="<?= htmlspecialchars($usuario['nome'] ?? '') ?>">
  <input type="hidden" name="email" value="<?= htmlspecialchars($usuario['email'] ?? '') ?>">


  <?php if (isset($_GET['animal_id'])): ?>
    <input type="hidden" name="animal_id" value="<?= intval($_GET['animal_id']) ?>">
  <?php endif; ?>

      <!-- Campo de telefone (caso não esteja na sessão) -->
      <label for="telefone">Telefone:
      <input
        type="text"
        id="telefone"
        name="telefone"
        value="<?= htmlspecialchars($usuario['telefone'] ?? '') ?>"
        required
      ></label>

      <!-- Campo de endereço -->
      <label for="endereco">Endereço completo:
      <input
        type="text"
        id="endereco"
        name="endereco"
        placeholder="Rua, número, bairro, cidade"
        required
      ></label>

      <!-- Campo de tipo de moradia -->
      <label for="tipo_moradia">Tipo de moradia:
      <select name="tipo_moradia" id="tipo_moradia" required>
        <option value="">Selecione</option>
        <option value="Casa">Casa</option>
        <option value="Apartamento">Apartamento</option>
        <option value="Chácara">Chácara</option>
        <option value="Outro">Outro</option>
      </select></label>

      <!-- Telas de proteção -->
      <label>
        Todas as janelas possuem tela de proteção?
        <select name="possui_tela_protecao" required>
          <option value="">Selecione</option>
          <option value="Sim">Sim</option>
          <option value="Não">Não</option>
        </select>
      </label>

      <!-- Condomínio permite pets -->
      <label>
        O condomínio ou proprietário permite pets?
        <select name="condominio_aceita" required>
          <option value="">Selecione</option>
          <option value="Sim">Sim</option>
          <option value="Não">Não</option>
        </select>
      </label>

      <!-- Espaço suficiente -->
      <label>
        Seu lar tem espaço suficiente para o animal (porte do animal)?
        <select name="espaco_para_animal" required>
          <option value="">Selecione</option>
          <option value="Sim">Sim</option>
          <option value="Não">Não</option>
        </select>
      </label>

      <!-- Condições financeiras -->
      <label>
        Você possui condições de arcar com alimentação e cuidados veterinários?
        <select name="condicoes_financeiras" required>
          <option value="">Selecione</option>
          <option value="Sim">Sim</option>
          <option value="Não">Não</option>
        </select>
      </label>

      <!-- Motivo da adoção -->
      <label for="motivo_adocao">
        Por que você quer adotar um animal? (opcional)
      </label>
      <textarea
        name="motivo_adocao"
        id="motivo_adocao"
        rows="3"
        maxlength="250"
        placeholder="Conte um pouco sobre o motivo da adoção" required></textarea>


      <!-- Experiência prévia com animais -->
      <label>
        Você já teve animais de estimação antes?
        <select name="experiencia_animais" required>
          <option value="">Selecione</option>
          <option value="Sim">Sim</option>
          <option value="Não">Não</option>
        </select>
      </label>

      <!-- Outros animais na casa -->
      <label>
        Você já possui outros animais na residência?
        <select name="outros_animais" required>
          <option value="">Selecione</option>
          <option value="Sim">Sim</option>
          <option value="Não">Não</option>
        </select>
      </label>

      <!-- Compromisso legal -->
      <label>
        <input type="checkbox" name="compromisso" value="Sim" required>
        Declaro que me comprometo com a adoção responsável e estou ciente das responsabilidades legais.
      </label>

      <input type="hidden" name="acao" value="solicitar_adocao">

      <button type="submit">Enviar solicitação</button>
    </form>
  </main>

  <footer>
    <div>
      ❤️ Petch <br>
      Todos os direitos reservados
    </div>
  </footer>
  <script>
    // Remove os toasts automaticamente após a animação
    document.addEventListener('DOMContentLoaded', function() {
      const toasts = document.querySelectorAll('.toast');
      toasts.forEach(toast => {
        setTimeout(() => {
          toast.remove();
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