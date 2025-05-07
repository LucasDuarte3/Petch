<?php
require_once __DIR__ . '/../../config.php'; // Ou o arquivo correto que contém routes.php

// Inicia a sessão apenas se ainda não estiver ativa
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once dirname(__DIR__) . '/models/User.php'; 
require_once dirname(__DIR__) . '/../config/database.php'; 

if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST["acao"]) && $_POST['acao'] === 'login'){
    $userModel = new User($pdo);

    // Validações básicas
    if(empty($_POST['email'] || empty($_POST['senha']))){
        $_SESSION['erro'] = "Preencha todos os campos!";
        header("Location: " . PUBLIC_PATH . "/login.php");
        exit;
    }

    // Verifica credenciais
    $usuario = $userModel->verifyCredentials($_POST['email'], $_POST['senha']);

    if($usuario){
        // Login bem-sucedido
        $_SESSION['usuario'] = [
            'id' => $usuario['id'],
            'nome' => $usuario['nome'],
            'email' => $usuario['email'],
            'tipo' => $usuario['tipo']
        ];
        if ($usuario && !$usuario['verificado']) {
            $_SESSION['erro'] = "Confirme seu e-mail antes de fazer login!";
            header("Location: " . PUBLIC_PATH . "/login.php");
            exit;
        }

        $_SESSION['sucesso'] = "Login realizado com sucesso!";
        
        // Redireciona conforme o tipo de usuário
        if ($usuario['tipo'] === 'admin') {
            header("Location: " . ADMIN_PATH . "/dashboard.php");
        } else {
            header("Location: " . PUBLIC_PATH . "/perfil.php");
        }
    }else {
        $_SESSION['erro'] = "Email ou senha incorreto";
        header("Location: " . PUBLIC_PATH . "/login.php");
    }
    exit;



    if(isset($_POST['acao']) && $_POST['acao'] === 'logout'){
        // Logout
        session_unset();
        session_destroy();
        $_SESSION['sucesso'] = "Você saiu com segurança!";
        header("Location: " . PUBLIC_PATH . "/login.php");
        exit;
    }
}
?>