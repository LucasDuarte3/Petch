<?php
// confirmacao/confirmar.php

// 1. Inclui configurações e conexão com banco
require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../app/models/User.php';
require_once __DIR__ . '/../../app/controllers/UserController.php';

// 2. Inicia sessão se não estiver ativa
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 3. Processa o token apenas se existir na URL
if (isset($_GET['token'])) {
    $token = $_GET['token'];
    $user = new User($pdo);
    
    try {
        // 4. Tenta verificar o token
        if ($user->verifyEmail($token)) {
            $_SESSION['sucesso'] = "✅ E-mail confirmado com sucesso! Faça login para continuar.";
        } else {
            $_SESSION['erro'] = "❌ Token inválido. <a href='".PUBLIC_PATH."/reenviar-confirmacao.php'>Reenviar link</a>";
        }
    } catch (Exception $e) {
        $_SESSION['erro'] = "⚠️ Erro ao confirmar e-mail: " . $e->getMessage();
    }
} else {
    $_SESSION['erro'] = "🔍 Token não fornecido na URL.";
}

// 5. Redireciona de volta para o login
header("Location: " . PUBLIC_PATH . "/login.php");
exit();
?>