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
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?= ASSETS_PATH ?>/footerStyle.css">
</head>
<body>
    <footer>
        <div class="footer-link">
            <div class="link-column">
                <h3>ADOTE</h3>
                <ul>
                    <li><a href="#">Adote com responsabilidade</a></li>
                    <li><a href="#">Pesquisar animais</a></li>
                </ul>
            </div>

            <div class="link-column">
                <h3>COLABORE</h3>
                <ul>
                    <li><a href="#">Doe qualquer valor</a></li>
                    <li><a href="#">Seja uma empresa parceira</a></li>
                </ul>
            </div>

            <div class="link-column">
                <h3>DIVULGUE UM ANIMAL</h3>
                <ul>
                    <li><a href="#">Cadastrar animal</a></li>
                    <li><a href="#">Perguntas frequentes</a></li>
                </ul>
            </div>

            <div class="link-column">
                <h3>SOBRE O AMIGO</h3>
                <ul>
                    <li><a href="#">Sobre Petch</a></li>
                    <li><a href="#">Termos de uso e política de privacidade</a></li>
                </ul>
            </div>

            <div class="link-column">
                <h3>PERFIL</h3>
                <ul>
                    <li><a href="#">Minha página de perfil</a></li>
                    <li><a href="#">Cadastre-se</a></li>
                </ul>
            </div>
        </div>
        <div class="copyright">
            <p>Todos os direitos reservados</p>
        </div>
    </footer>
</body>
</html>