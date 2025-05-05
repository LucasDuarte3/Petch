<?php
require_once __DIR__ . '/../config.php'; // Ou o arquivo correto que contém routes.php

// Inicia a sessão apenas se ainda não estiver ativa
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function protegerRota($nivelAcesso = 'usuario') {
    // Verifica se o usuário está logado
    if (!isset($_SESSION['usuario'])) {
        $_SESSION['erro'] = "Faça login para acessar!";
        header("Location: " . PUBLIC_PATH . "/login.php");
        exit;
    }

    // Verificar se o e-mail foi confirmado
    if (isset($_SESSION['usuario']['verificado']) && !$_SESSION['usuario']['verificado']) {
        $_SESSION['erro'] = "Por favor, verifique seu e-mail antes de acessar.";
        header("Location: /frontend/views/auth/login.php");
        exit;
    }

    // Verifica o nível de acesso
    if ($_SESSION['usuario']['tipo'] !== $nivelAcesso && $nivelAcesso !== 'usuario') {
        $_SESSION['erro'] = "Acesso não autorizado!";
        header("Location: " . ADMIN_PATH . "/dashboard.php");
        exit;
    }
}

// Agora, sempre que você quiser proteger uma página, basta chamá-la no início do arquivo
// protegerRota('admin'); // Apenas usuários do tipo "admin" podem acessar
?>
