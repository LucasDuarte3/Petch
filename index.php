<?php
// Página inicial: lista de animais cadastrados
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/config/database.php';

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

try {
    // Antes: exibia todos os animais, independente do status
// $stmt = $pdo->query("SELECT id, nome, especie, raca, idade, porte, descricao, foto_blob FROM animais ORDER BY id DESC");

// Agora: só traz animais disponíveis (aprovados pelo admin)
    $stmt = $pdo->query("SELECT id, nome, especie, raca, idade, porte, descricao, foto_blob 
                     FROM animais 
                     WHERE status = 'disponível'
                     ORDER BY id DESC");
    $animais = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die('Erro ao carregar animais: ' . htmlspecialchars($e->getMessage()));
}
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Peth - Adote um Animal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="<?= ASSETS_PATH ?>/styleIndex.css">
    <style>
    body {
      font-family: 'Arial', sans-serif;
      margin: 0;
      background-color: #f1f1f1;
      color: #003366;
    }

    .topbar {
      background-color: #0047a0;
      color: white;
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 1rem 2rem;
      height: 80px;
    }

    .logo {
      font-weight: bold;
      font-size: 1.5rem;
      display: flex;
      align-items: center;
      gap: 0.5rem;
    }

    .logo-link {
      color: white;
      text-decoration: none;
      display: flex;
      align-items: center;
      height: 100%;
      padding: 0 10px;
      transition: opacity 0.3s;
    }

    .menu {
      padding: 8px 12px;
      border-radius: 4px;
      transition: background-color 0.3s;
    }

    .menu:hover {
      background-color: rgba(255,255,255,0.1);
    }

    .user-icon a {
      display: flex;
      align-items: center;
      justify-content: center;
      width: 100px;
      height: 80px;
      text-decoration: none;
      color: white;
    }

    .logout-icon {
      display: flex;
      align-items: center;
      justify-content: center;
      width: 100px;
      height: 80px;
      
    }

    .user-icon a:hover {
      background-color: rgba(255,255,255,0.2);
    }

    .icon {
      text-decoration: none;
      color: white;
    }
  </style>
</head>

<body>
    <header class="topbar">
    <div class="logo">
         <a href="<?= PUBLIC_PATH ?>/index.php" class="logo-link">❤️ Petch</a>
       </div>

    <nav>
      <a href="<?= PUBLIC_PATH ?>/consulta_animal.php" class="menu icon">Buscar Animal</a>
    </nav>

    <div style="display: flex; gap: 10px;">
      <div class="user-icon">
        <a href="<?= PUBLIC_PATH ?>/cadastro.php"><i class="fas fa-user icon"></i>Cadastre-se</a>
      </div>
      <div class="logout-icon">
        <a href="<?= PUBLIC_PATH ?>/login.php" class="icon"><i class="fas fa-sign-out-alt"></i>Logar</a>
      </div>
    </div>
  </header>

    <div class="hero-section">
        <img src="<?= IMG_PATH ?>/imagemFundoInicial.jpg" alt="Banner principal" class="hero-image">
        <div class="hero-content">
            <h1>Encontre seu novo melhor amigo</h1>
            <p class="hero-subtitle">Adote um animal de estimação e transforme duas vidas: a dele e a sua.</p>
            <div class="hero-buttons">
  <?php if (isset($_SESSION['usuario'])): ?>
    <!-- Usuário logado -->
    <a href="<?= BASE_PATH ?>/public/formadocao.php" class="btn btn-primary btn-lg">
      Adotar um Pet
    </a>
    <a href="<?= BASE_PATH ?>/public/cadastro_animal.php" class="btn btn-primary btn-lg">
      Doar um Pet
    </a>
  <?php else: ?>
    <!-- Usuário não logado -->
    <a href="<?= BASE_PATH ?>/public/login.php" class="btn btn-primary btn-lg">
      Adotar um Pet
    </a>
    <a href="<?= BASE_PATH ?>/public/login.php" class="btn btn-primary btn-lg">
      Doar um Pet
    </a>
  <?php endif; ?>
  </div>
        </div>
    </div>

    <div class="pets-cards">
          <main class="container py-5">
    <?php if (empty($animais)): ?>
      <p class="text-muted">Nenhum animal cadastrado no momento.</p>
    <?php else: ?>
      <div class="row">
<?php foreach ($animais as $animal): ?>
  <div class="col-md-4 mb-4">
    <div class="card h-100">
      <?php if (!empty($animal['foto_blob'])): ?>
        <?php $base64 = base64_encode($animal['foto_blob']); ?>
        <img
          src="data:image/jpeg;base64,<?= $base64 ?>"
          class="card-img-top animal-card-img"
          alt="Foto de <?= htmlspecialchars($animal['nome']) ?>"
          style="cursor:pointer"
          data-nome="<?= htmlspecialchars($animal['nome']) ?>"
          data-especie="<?= htmlspecialchars($animal['especie']) ?>"
          data-raca="<?= htmlspecialchars($animal['raca']) ?>"
          data-idade="<?= htmlspecialchars($animal['idade']) ?>"
          data-porte="<?= htmlspecialchars($animal['porte']) ?>"
          data-desc="<?= htmlspecialchars($animal['descricao']) ?>"
          data-img="data:image/jpeg;base64,<?= $base64 ?>"
            data-id="<?= $animal['id'] ?>"
        >
      <?php endif; ?>
      <div class="card-body">
        <h5 class="card-title"><?= htmlspecialchars($animal['nome']); ?></h5>
        <p class="card-text"><?= nl2br(htmlspecialchars($animal['descricao'])); ?></p>
        <ul class="list-unstyled">
          <li><strong>Espécie:</strong> <?= htmlspecialchars($animal['especie']); ?></li>
          <li><strong>Raça:</strong> <?= htmlspecialchars($animal['raca']); ?></li>
          <li><strong>Idade:</strong> <?= htmlspecialchars($animal['idade']); ?></li>
          <li><strong>Porte:</strong> <?= htmlspecialchars($animal['porte']); ?></li>
        </ul>
        <!-- Botão removido, pois o click já será na imagem -->
      </div>
    </div>
  </div>
<?php endforeach; ?>
      </div>
    <?php endif; ?>

</main>
    <?php include 'app/views/footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">

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

<!-- Modal de Detalhes do Animal -->
<div class="modal fade" id="animalModal" tabindex="-1" aria-labelledby="modalAnimalName" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content" style="border-radius: 20px;">
      <div class="modal-body p-0" style="display: flex; flex-direction: row;">

        <!-- Foto à esquerda -->
        <div class="modal-img-col" style="flex:1; background: #fafafa; display:flex; align-items:center; justify-content:center; border-radius:20px 0 0 20px;">
          <img id="modalAnimalImg" src="" alt="Foto do animal" style="max-width: 100%; max-height: 400px; border-radius:16px;"/>
        </div>

        <!-- Informações à direita -->
        <div class="modal-info-col" style="flex:1.5; padding: 32px 24px 24px 24px;">
          <h2 id="modalAnimalName" style="font-weight: bold; margin-bottom: 12px;"></h2>
          <ul class="list-unstyled mb-2" style="font-size: 1.08rem;">
            <li><b>Espécie:</b> <span id="modalAnimalEspecie"></span></li>
            <li><b>Raça:</b> <span id="modalAnimalRaca"></span></li>
            <li><b>Idade:</b> <span id="modalAnimalIdade"></span></li>
            <li><b>Porte:</b> <span id="modalAnimalPorte"></span></li>
          </ul>
          <div id="modalAnimalDesc" class="mb-4" style="color: #444;"></div>
          <button type="button" id="btnQueroAdotar" class="btn btn-warning btn-lg w-100" style="font-weight:bold; border-radius:12px;">
  Quero adotar
</button>

        </div>
      </div>
    </div>
  </div>
</div>


<script>
  let currentAnimalId = null;
document.addEventListener("DOMContentLoaded", function () {
  document.querySelectorAll('.animal-card-img').forEach(function(img) {
    img.addEventListener('click', function() {
      document.getElementById('modalAnimalName').textContent = img.dataset.nome;
      document.getElementById('modalAnimalEspecie').textContent = img.dataset.especie;
      document.getElementById('modalAnimalRaca').textContent = img.dataset.raca;
      document.getElementById('modalAnimalIdade').textContent = img.dataset.idade + " ano(s)";
      document.getElementById('modalAnimalPorte').textContent = img.dataset.porte;
      document.getElementById('modalAnimalDesc').textContent = img.dataset.desc;
      document.getElementById('modalAnimalImg').src = img.dataset.img;
currentAnimalId = img.dataset.id;
      // Abre o modal do bootstrap
      var myModal = new bootstrap.Modal(document.getElementById('animalModal'));
      myModal.show();
    });
  });
  document.getElementById('btnQueroAdotar').onclick = function() {
    if (currentAnimalId) {
      window.location.href = "<?= BASE_PATH ?>/public/formadocao.php?id=" + currentAnimalId;
    }
  }
});
</script>


</body>

</html>