<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../../config.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once dirname(__DIR__) . '/../config/database.php';

// SÓ CADASTRA ANIMAL!
if (
    $_SERVER['REQUEST_METHOD'] === 'POST'
    && ($_POST['acao'] ?? '') === 'cadastrar_animal'
) {
    $conf = $_SESSION['animal_confirmation'] ?? [];

    $nome             = $conf['nome']             ?? '';
    $especie          = $conf['especie']          ?? '';
    $raca             = $conf['raca']             ?? '';
    $idade            = $conf['idade']            ?? null;
    $porte            = $conf['porte']            ?? '';
    $historico_medico = $conf['historico_medico'] ?? null;
    $doencas_cronicas = $conf['doencas']          ?? null;
    $comportamento    = $conf['descricao']        ?? null;
    $fotoPath         = $conf['foto_path']        ?? '';
    $usuario_id       = $_SESSION['usuario']['id'] ?? null;

    $fotoData = null;
    if ($fotoPath && file_exists($fotoPath)) {
        $fotoData = file_get_contents($fotoPath);
    }

    try {
        $status = 'aguardando';
        $sql = "INSERT INTO animais
            (nome, especie, raca, idade, porte,
            historico_medico, doencas_cronicas, comportamento,
            foto_blob, usuario_id, status)
            VALUES
            (:nome, :especie, :raca, :idade, :porte,
            :historico_medico, :doencas_cronicas, :comportamento,
            :foto_blob, :usuario_id, :status)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':nome',              $nome);
        $stmt->bindParam(':especie',           $especie);
        $stmt->bindParam(':raca',              $raca);
        $stmt->bindParam(':idade',             $idade);
        $stmt->bindParam(':porte',             $porte);
        $stmt->bindParam(':historico_medico',  $historico_medico);
        $stmt->bindParam(':doencas_cronicas',  $doencas_cronicas);
        $stmt->bindParam(':comportamento',     $comportamento);
        $stmt->bindParam(':foto_blob',         $fotoData, PDO::PARAM_LOB);
        $stmt->bindParam(':usuario_id',        $usuario_id);
        $stmt->bindParam(':status',            $status);
        $stmt->execute();

        // Remove temporário
        if ($fotoPath && file_exists($fotoPath)) {
            unlink($fotoPath);
            $tmpDir = dirname($fotoPath);
            if (is_dir($tmpDir) && count(scandir($tmpDir)) === 2) {
                rmdir($tmpDir);
            }
        }

        $_SESSION['sucesso'] = 'Seu anúncio foi enviado para revisão! Em breve nossa equipe analisara o anúncio.';
        header('Location: ' . BASE_PATH . '/index.php');
        exit;
    } catch (PDOException $e) {
        echo '<pre>Erro de banco de dados: ' . htmlspecialchars($e->getMessage()) . '</pre>';
        exit;
    }
}
?>
