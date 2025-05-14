<?php
// logout.php – encerra a sessão e redireciona para a tela de login
session_start();
// Remove todas as variáveis de sessão
session_unset();
// Destroi a sessão
session_destroy();
// Redireciona para o login
header('Location: login.php');
exit;
