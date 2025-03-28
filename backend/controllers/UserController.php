<?php
require_once __DIR__ . '/../models/User.php'; // importa classe user
require_once __DIR__ . '/../config/database.php'; // importa config banco

session_start(); // inicia a sessão

// aqui onde vai acontecer a validação para o cadastro do usuário
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['acao']) && $_POST['acao'] === 'cadastrar'){
    $user = new User($pdo);

    // validações dos campos obrigatorios para cadastro
    if(empty($_POST['nome']) || empty($_POST['email']) || empty($_POST['senha'])){
        $_SESSION['erro'] = "Preencha todos os campos!";
        header("Location: /frontend/views/cadastro.php");
        exit;
    }
    if($user->emailExists($_POST['email'])){
        $_SESSION['erro'] = "Email já cadastrado!";
        header("Location: /frontend/views/cadastro.php");
        exit;
    }
    // Cadastro do usuario 
    if($user->create($_POST['nome'], $_POST['email'], $_POST['senha'])){
        $_SESSION['sucesso'] = "Cadastro realizado! Faça o login.";
        header("Location: /frontend/views/login.php");
    }else{
        $_SESSION['erro'] = "Erro ao cadastrar. Tente outro email.";
        header("Location: /frontend/views/cadastro.php");
    }
    exit;
}

?>