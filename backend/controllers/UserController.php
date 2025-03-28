<?php
require_once __DIR__ . '/../models/User.php'; // importa classe user
require_once __DIR__ . '/../config/database.php'; // importa config banco

session_start(); // inicia a sessão

// aqui onde vai acontecer a validação para o cadastro do usuário
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['acao']) && $_POST['acao'] === 'cadastrar'){
    $user = new User($pdo);
    try {
        // validações dos campos obrigatorios para cadastro
        if (empty($_POST['nome']) || empty($_POST['email']) || empty($_POST['senha'])) {
            throw new Exception("Preencha todos os campos!");
        }
        if ($user->emailExists($_POST['email'])) {
            throw new Exception("Email já cadastrado!");
        }

        // Cadastro do usuario 
        if ($user->create($_POST['nome'], $_POST['email'], $_POST['senha'])) {
            $_SESSION['sucesso'] = "Cadastro realizado! Faça o login.";
            header("Location: /frontend/views/login.php");
        } else {
            throw new Exception("Erro ao cadastrar. Tente outro email.");
        }

    } catch (Exception $e) {
        // Captura a exceção e exibe a mensagem de erro
        $_SESSION['erro'] = $e->getMessage();
        header("Location: /frontend/views/cadastro.php");
        exit;
    }

    exit;
}

?>