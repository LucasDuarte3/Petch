<?php
require_once __DIR__ . '/../../config.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once dirname(__DIR__) . '/../config/database.php';
require_once dirname(__DIR__) . '/../app/models/Animal.php';

$animalModel = new Animal($pdo);
$userId = $_SESSION['usuario']['id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Busca de animais para deletar
        if (isset($_POST['acao']) && $_POST['acao'] === 'buscar') {
            $especie = $_POST['especie'] ?? null;
            $raca = $_POST['raca'] ?? null;
            $idade = $_POST['idade'] ?? null;
            $porte = $_POST['porte'] ?? null;
            
            if (empty($especie) && empty($raca) && empty($idade) && empty($porte)) {
                throw new Exception("Preencha pelo menos um critério de busca!");
            }
            
            $resultados = $animalModel->buscarAnimaisPorFiltros($especie, $raca, $idade, $porte, $userId );
            
            if (empty($resultados)) {
                throw new Exception("Nenhum animal encontrado com os critérios informados!");
            }
            
            $_SESSION['animais_para_deletar'] = $resultados;
            header("Location: " . PUBLIC_PATH . "/confirmar_exclusao.php");
            exit;
        }
        
        // Confirmação de exclusão
        if (isset($_POST['acao']) && $_POST['acao'] === 'confirmar_deletar') {
            if (empty($_POST['id_animal'])) {
                throw new Exception("Nenhum animal selecionado para exclusão!");
            }
            
            $id = (int)$_POST['id_animal'];
            if ($animalModel->deletarAnimal($id, $userId)) {
                $_SESSION['sucesso'] = "Animal excluído com sucesso!";
            } else {
                throw new Exception("Erro ao excluir o animal!");
            }
            
            header("Location: " . PUBLIC_PATH . "/deletar_animal.php");
            exit;
        }
    } catch (Exception $e) {
        $_SESSION['erro'] = $e->getMessage();
        header("Location: " . PUBLIC_PATH . "/deletar_animal.php");
        exit;
    }
}
?>