<?php

require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../config/database.php';

session_start();

if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST["acao"]) && $_POST['acao'] === 'login'){
    $userModel = new User($pdo);

    // Validações básicas
    if(empty($_POST['email'] || empty($_POST['senha']))){
        $_SESSION['erro'] = "Preencha todos os campos";
        header("Location: /frontend/views/login.php");
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

        $_SESSION['sucesso'] = "Login realizado com sucesso";
        header("Location: /frontend/views/dashboard.php");
    }else {
        $_SESSION['erro'] = "Email ou senha incorretos";
        header("Location: /frontend/views/login.php");
    }
    exit;

    if(isset($_POST['acao']) && $_POST['acao'] === 'logout'){
        // Logout
        session_unset();
        session_destroy();
        $_SESSION['sucesso'] = "Você saiu com segurança!";
        header("Location: /frontend/views/login.php");
        exit;
    }
}
?>