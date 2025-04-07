<?php
require_once __DIR__ . '/../../config.php'; // Ou o arquivo correto que contém routes.php

// Inicia a sessão apenas se ainda não estiver ativa
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once dirname(__DIR__) . '/../config/database.php'; // Configuração do banco
require_once dirname(__DIR__) . '/../app/models/Animal.php'; // Classe Animal


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['acao']) && $_POST['acao'] === 'cadastrar_animal'){
    $animal = new Animal($pdo);
    try {
        // validações dos campos obrigatorios para cadastro
        if (empty($_POST["nome"]) || empty($_POST["especie"]) || empty($_POST["porte"]) || empty($_POST["status"]) || empty($_POST["localidade"])) {
            throw new Exception("Preencha todos os campos!");
        }

        // Cadastro do usuario 
        if ($animal->create(
            $_POST['nome'],
            $_POST['especie'],
            $_POST['porte'],
            $_POST['localidade'],
            $_POST['status'],
            $_POST['raca'] ?? null,
            $_POST['idade'] ?? null,
            $_POST['historico_medico'] ?? null,
            $_POST['caminho_foto'] ?? null,
            $_SESSION['usuario']['id'] ?? null,
            $_POST['descricao'] ?? null
        )) {
            $_SESSION['sucesso'] = "Cadastro realizado com sucesso!";
            header("Location: " . ADMIN_PATH . "/dashboard.php");
        } else {
            throw new Exception("Erro ao cadastrar o animal!");
        }

    } catch (Exception $e) {
        // Captura a exceção e exibe a mensagem de erro
        $_SESSION['erro'] = $e->getMessage();
        header("Location: " . PUBLIC_PATH . "/cadastro_animal.php");
        exit;
    }
}

?>