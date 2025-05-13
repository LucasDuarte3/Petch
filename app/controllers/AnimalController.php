<?php
// Ativa exibição de erros para debug
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Carrega configuração global (rotas, constantes)
require_once __DIR__ . '/../../config.php';

// Inicia sessão se ainda não estiver ativa
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Carrega conexão com banco
require_once dirname(__DIR__) . '/../config/database.php';

// Processa submissão de cadastro
if (
    $_SERVER['REQUEST_METHOD'] === 'POST'
    && isset($_POST['acao'])
    && $_POST['acao'] === 'cadastrar_animal'
) {
    // Recupera dados confirmados da sessão
    $conf = $_SESSION['animal_confirmation'] ?? [];
    $nome               = $conf['nome']               ?? '';
    $especie            = $conf['especie']            ?? '';
    $raca               = $conf['raca']               ?? '';
    $idade              = $conf['idade']              ?? null;
    $porte              = $conf['porte']              ?? '';
    // Ajuste: localidade e status passam string vazia, pois no form básico
    $localidade         = $conf['localidade']         ?? '';
    $historico_medico   = $conf['historico_medico']   ?? null;
    $doencas_cronicas   = $conf['doencas']            ?? null;
    $comportamento      = $conf['descricao']          ?? null;
    $caminho_foto       = $conf['foto_path']          ?? null;
    $usuario_id         = $_SESSION['usuario']['id']   ?? null;

    try {
        $sql = "INSERT INTO animais
            (nome, especie, raca, idade, porte,
             localidade, 
             historico_medico, doencas_cronicas, comportamento,
             caminho_foto, usuario_id)
            VALUES
            (:nome, :especie, :raca, :idade, :porte,
             :localidade, 
             :historico_medico, :doencas_cronicas, :comportamento,
             :caminho_foto, :usuario_id)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':nome'              => $nome,
            ':especie'           => $especie,
            ':raca'              => $raca,
            ':idade'             => $idade,
            ':porte'             => $porte,
            ':localidade'        => $localidade,
            ':historico_medico'  => $historico_medico,
            ':doencas_cronicas'  => $doencas_cronicas,
            ':comportamento'     => $comportamento,
            ':caminho_foto'      => $caminho_foto,
            ':usuario_id'        => $usuario_id,
        ]);
        $_SESSION['sucesso'] = 'Cadastro realizado com sucesso!';
        header('Location: ' . ADMIN_PATH . '/dashboard.php');
        exit;
    } catch (PDOException $e) {
        echo '<pre>Erro de banco de dados: ' . htmlspecialchars($e->getMessage()) . '</pre>';
        exit;
    }
}
