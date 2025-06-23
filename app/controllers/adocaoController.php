<?php
require_once __DIR__ . '/../../config.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once dirname(__DIR__) . '/../config/database.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ajusta aqui para pegar os campos EXATAMENTE como na tabela!
    $usuario_id = $_POST['usuario_id'] ?? null;

    $nome                   = trim($_POST['nome'] ?? '');
    $email                  = trim($_POST['email'] ?? '');
    $telefone               = trim($_POST['telefone'] ?? '');
    $endereco               = trim($_POST['endereco'] ?? '');
    $tipo_moradia           = $_POST['tipo_moradia'] ?? '';
    $possui_tela_protecao   = $_POST['possui_tela_protecao'] ?? '';
    $condominio_aceita      = $_POST['condominio_aceita'] ?? '';
    $espaco_para_animal     = $_POST['espaco_para_animal'] ?? '';
    $condicoes_financeiras  = $_POST['condicoes_financeiras'] ?? '';
    $compromisso            = $_POST['compromisso'] ?? '';

    // Validação simples
    $erros = [];
    foreach ([
        'nome', 'email', 'telefone', 'endereco', 'tipo_moradia', 'possui_tela_protecao', 'condominio_aceita',
        'espaco_para_animal', 'condicoes_financeiras', 'compromisso'
    ] as $campo) {
        if (empty($$campo)) {
            $erros[] = "Preencha o campo: " . ucfirst(str_replace('_', ' ', $campo));
        }
    }

    if ($compromisso !== 'Sim') {
        $erros[] = "Você deve se comprometer com a adoção responsável.";
    }

    if ($erros) {
        $_SESSION['erro'] = implode('<br>', $erros);
        header('Location: ' . BASE_PATH . '/public/formadocao.php');
        exit;
    }}

    // Salva no banco
try {
    $sql = "INSERT INTO form_adocao (
                usuario_id,
                nome,
                email,
                telefone,
                endereco,
                tipo_moradia,
                possui_tela_protecao,
                condominio_aceita,
                espaco_para_animal,
                condicoes_financeiras,
                motivo_adocao,
                experiencia_animais,
                outros_animais,
                compromisso
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        $usuario_id,
        $nome,
        $email,
        $telefone,
        $endereco,
        $tipo_moradia,
        $possui_tela_protecao,
        $condominio_aceita,
        $espaco_para_animal,
        $condicoes_financeiras,
        $_POST['motivo_adocao'] ?? '',
        $_POST['experiencia_animais'] ?? '',
        $_POST['outros_animais'] ?? '',
        $compromisso
    ]);
    $_SESSION['sucesso'] = "Solicitação de adoção enviada com sucesso! Aguarde retorno por e-mail.";
    header('Location: ' . BASE_PATH . '/index.php');
    exit;
} catch (PDOException $e) {
    $_SESSION['erro'] = "Erro ao enviar solicitação: " . htmlspecialchars($e->getMessage());
    header('Location: ' . BASE_PATH . '/public/formadocao.php');
    exit;
}
