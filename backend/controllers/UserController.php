<?php
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../config/database.php';

session_start();

// aqui onde vai acontecer a validação para o cadastro do usuário
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['acao']) && $_POST['acao'] === 'cadastrar'){
    $user - new User($pdo);
    if($user->create($_POST['nome'], $_POST['senha'])){
        $_SESSION['sucesso'] = "Cadastro realizado! Faça o login.";
        header("Location: /frontend/views,login.php");
    }else{
        $SESSION['erro'] = "Erro ao cadastrar. Tente outro email.";
        header("Location: /frontend/views/cadastro.php");
    }
    exit;
}

?>