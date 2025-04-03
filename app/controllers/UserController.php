<?php
require_once __DIR__ . '/../../config.php'; // Ou o arquivo correto que contém routes.php

// Inicia a sessão apenas se ainda não estiver ativa
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once dirname(__DIR__) . '/../config/database.php'; // Configuração do banco
require_once dirname(__DIR__) . '/../app/models/User.php'; // Classe User


// aqui onde vai acontecer a validação para o cadastro do usuário
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['acao']) && $_POST['acao'] === 'cadastrar'){
    $user = new User($pdo);
    try {
        // validações dos campos obrigatorios para cadastro
        if (empty($_POST['nome']) || empty($_POST['email']) || empty($_POST['senha']) || empty($_POST['cpf_cnpj'])) {
            throw new Exception("Preencha todos os campos!");
        }

        // Validação de formato básico de CPF/CNPJ (11 ou 14 dígitos)
        $cpf_cnpj = preg_replace('/[^0-9]/', '', $_POST['cpf_cnpj']);
        if (!in_array(strlen($cpf_cnpj), [11, 14])) {
            throw new Exception("CPF deve ter 11 dígitos ou CNPJ 14 dígitos!");
        }

        // Validação de e-mail duplicado
        if ($user->emailExists($_POST['email'])) {
            throw new Exception("Email já cadastrado!");
        }

        // Validação de CPF/CNPJ duplicado
        $sql = "SELECT id FROM usuarios WHERE cpf_cnpj = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$cpf_cnpj]);
        if ($stmt->fetch()) {
            throw new Exception("CPF/CNPJ já cadastrado no sistema!");
        }

        // Cadastro do usuario 
        if ($user->create(
            $_POST['nome'],
            $_POST['email'],
            $_POST['senha'],
            'usuario', // Tipo padrão
            $_POST['telefone'] ?? null,
            $_POST['endereco'] ?? null,
            $cpf_cnpj // Já formatado
        )) {
            $_SESSION['sucesso'] = "Cadastro realizado com sucesso!";
            header("Location: " . PUBLIC_PATH . "/login.php");
        } else {
            throw new Exception("Erro ao cadastrar. Tente outro email!");
        }

    } catch (Exception $e) {
        // Captura a exceção e exibe a mensagem de erro
        $_SESSION['erro'] = $e->getMessage();
        header("Location: " . PUBLIC_PATH . "/cadastro.php");
        exit;
    }
}

?>