<?php
require_once __DIR__ . '/../../config.php'; // Ou o arquivo correto que contém routes.php

// Inicia a sessão apenas se ainda não estiver ativa
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once dirname(__DIR__) . '/../config/database.php'; // Configuração do banco
require_once dirname(__DIR__) . '/../app/models/Animal.php'; // Classe Animal


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['acao']) && $_POST['acao'] === 'deletar'){
    $animal = new Animal($pdo);
    try {
        // validações dos campos obrigatorios para cadastro
        if (empty($_POST["id"])) {
            throw new Exception("Preencha o campo de ID!");
        }

        // Cadastro do usuario 
        if ($animal->deletarAnimal($_POST['id'])) {
            echo "<script type='javascript'>alert('Animal deletado com sucesso!');";
            header("Location: " . ADMIN_PATH . "/dashboard.php");
        } else {
            throw new Exception("Erro ao deletar o animal!");
        }

    } catch (Exception $e) {
        // Captura a exceção e exibe a mensagem de erro
        $_SESSION['erro'] = $e->getMessage();
        header("Location: " . PUBLIC_PATH . "/deletar_animal.php");
        exit;
    }
}

?>