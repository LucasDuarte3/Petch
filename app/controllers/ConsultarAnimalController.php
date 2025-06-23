<?php
require_once __DIR__ . '/../../config.php'; // Ou o arquivo correto que contém routes.php

// Inicia a sessão apenas se ainda não estiver ativa
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once dirname(__DIR__) . '/../config/database.php'; // Configuração do banco
require_once dirname(__DIR__) . '/../app/models/Animal.php'; // Classe Animal

$animalModel = new Animal($pdo);
$animaisDivulgados = $animalModel->countAnimaisDivulgados();
$animaisAdotados = $animalModel->countAnimaisAdotados();
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['acao']) && $_POST['acao'] === 'consultar') {
    try {
        // Coletar parâmetros de busca
        $especie = $_POST['especie'] ?? null;
        $raca = $_POST['raca'] ?? null;
        $idade = $_POST['idade'] ?? null;
        $porte = $_POST['porte'] ?? null;
        
        // Verificar se pelo menos um critério foi preenchido
        if (empty($especie) && empty($raca) && empty($idade) && empty($porte)) {
            throw new Exception("Preencha pelo menos um critério de busca!");
        }
        
        // Buscar animais com os critérios fornecidos
        $resultados = $animalModel->buscarAnimaisPorFiltros($especie, $raca, $idade, $porte);
        
        if (empty($resultados)) {
            throw new Exception("Nenhum animal encontrado com os critérios informados!");
        }
        
        // Armazenar resultados na sessão para exibição
        $_SESSION['resultados_busca'] = $resultados;
        header("Location: " . PUBLIC_PATH . "/resultados_busca.php");
        exit;
        
    } catch (Exception $e) {
        $_SESSION['erro'] = $e->getMessage();
        header("Location: " . PUBLIC_PATH . "/consulta_animal.php");
        exit;
    }
}
?>