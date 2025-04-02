<?php
session_start();

function protegerRota($nivelAcesso = 'usuario') {
    // Verifica se o usuário está logado
    if (!isset($_SESSION['usuario'])) {
        $_SESSION['erro'] = "Faça login para acessar!";
        header("Location: ../../frontend/views/login.php");
        exit;
    }

    // Verifica o nível de acesso
    if ($_SESSION['usuario']['tipo'] !== $nivelAcesso && $nivelAcesso !== 'usuario') {
        $_SESSION['erro'] = "Acesso não autorizado!";
        header("Location: ../../frontend/views/dashboard.php");
        exit;
    }
}

// Agora, sempre que você quiser proteger uma página, basta chamá-la no início do arquivo
// protegerRota('admin'); // Apenas usuários do tipo "admin" podem acessar
?>
