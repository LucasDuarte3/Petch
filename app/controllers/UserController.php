<?php
require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../service/MailService.php';
require_once __DIR__ . '/../models/Adocao.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['acao']) && $_POST['acao'] === 'cadastrar') {
    $user = new User($pdo);
    $mailService = new MailService();

    try {
        // 1. VALIDAÇÃO DOS DADOS
        $requiredFields = [
            'nome', 'email', 'confirmacao_email', 
            'senha', 'confirmacao_senha', 'estado', 'cidade', 'cep', 
            'endereco'
        ];

        foreach ($requiredFields as $field) {
            if (empty($_POST[$field])) {
                throw new Exception("O campo " . ucfirst(str_replace('_', ' ', $field)) . " é obrigatório!");
            }
        }

        // Verifica se emails coincidem
        if ($_POST['email'] !== $_POST['confirmacao_email']) {
            throw new Exception("Os e-mails não coincidem!");
        }

        // Verifica se senhas coincidem
        if ($_POST['senha'] !== $_POST['confirmacao_senha']) {
            throw new Exception("As senhas não coincidem!");
        }

        // Verifica se email já existe
        if ($user->emailExists($_POST['email'])) {
            throw new Exception("Este e-mail já está cadastrado!");
        }

        // Verifica CPF/CNPJ
        $documento = $_POST['cpf'] ?? $_POST['cnpj'] ?? null;
        if ($documento && $user->documentExists($documento)) {
            throw new Exception("Este CPF/CNPJ já está cadastrado!");
        }

        // 2. PREPARA DADOS PARA CADASTRO
        $dadosUsuario = [
            'nome' => $_POST['nome'],
            'email' => $_POST['email'],
            'senha' => $_POST['senha'],
            'telefone' => $_POST['telefone'] ?? null,
            'endereco' => $_POST['endereco'] ?? null,
            'cpf_cnpj' => $_POST['cpf'] ?? $_POST['cnpj'] ?? null,
            'tipo' => 'usuario', // Tipo de usuário (pode ser 'usuario' ou 'admin')
            'token_verificacao' => bin2hex(random_bytes(32)), // Verificação de e-mail
            'token_reset' => null, // Só será preenchido quando o usuário solicitar
        ];

        // 3. CADASTRA USUÁRIO COM TOKEN
        $userId = $user->createWithToken($dadosUsuario);

        if ($userId) {
            $_SESSION['usuario_temp'] = [
                'email' => $_POST['email'],
                'nome' => $_POST['nome'],
                'token' => $dadosUsuario['token_verificacao']
            ];
            // 4. ENVIA E-MAIL DE CONFIRMAÇÃO
            $confirmacaoUrl = (isset($_SERVER['HTTPS']) ? 'https://' : 'http://') . 
                     $_SERVER['HTTP_HOST'] . 
                     PUBLIC_PATH . 
                     "/confirmacao/confirmar.php?token=" . 
                     urlencode($dadosUsuario['token_verificacao']);
            
            if ($mailService->sendConfirmationEmail(
                $_POST['email'],
                $_POST['nome'],
                $confirmacaoUrl
            )) {
                $_SESSION['sucesso'] = "Cadastro realizado com sucesso! Verifique seu e-mail para ativar sua conta.";
            } else {
                $_SESSION['aviso'] = "Cadastro realizado, mas não foi possível enviar o e-mail de confirmação. <a href='".BASE_PATH."/reenviar-confirmacao.php'>Clique aqui</a> para reenviar.";
            }
            
            header("Location: " . PUBLIC_PATH . "/login.php");
            exit();
        }

    } catch (Exception $e) {
        $_SESSION['erro'] = $e->getMessage();
        $_SESSION['dados_formulario'] = $_POST; // Mantém os dados digitados
        header("Location: " . PUBLIC_PATH . "/cadastro.php");
        exit();
    }
}

// ============================
// FORMULÁRIO DE ADOÇÃO
// ============================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['acao']) && $_POST['acao'] === 'form_adocao') {
    $formAdocao = new FormAdocao($pdo);

    try {
        $dados = [
            'nome' => $_POST['nome'],
            'email' => $_POST['email'],
            'telefone' => $_POST['telefone'],
            'endereco' => $_POST['endereco'],
            'tipo_moradia' => $_POST['tipo_moradia'],
            'tela_protecao' => $_POST['tela_protecao'],
            'condominio_permite' => $_POST['condominio_permite'],
            'espaco_suficiente' => $_POST['espaco_suficiente'],
            'condicoes_financeiras' => $_POST['condicoes_financeiras'],
            'compromisso' => $_POST['compromisso']
        ];

        $formAdocao->salvar($dados);

        header("Location: " . PUBLIC_PATH . "/confirmacao/sucesso.html");
        exit();

    } catch (Exception $e) {
        $_SESSION['erro'] = "Erro ao enviar formulário: " . $e->getMessage();
        header("Location: " . PUBLIC_PATH . "/form_adocao.php");
        exit();
    }
}

// Rota para reenviar confirmação (opcional)
if (isset($_GET['acao']) && $_GET['acao'] === 'reenviar-confirmacao') {
    if (!empty($_SESSION['usuario_temp'])) {
        $mailService = new MailService();
        $confirmacaoUrl = PUBLIC_PATH . "/confirmacao/confirmar.php?token=" . $_SESSION['usuario_temp']['token'];
        
        if ($mailService->sendConfirmationEmail(
            $_SESSION['usuario_temp']['email'],
            $_SESSION['usuario_temp']['nome'],
            $confirmacaoUrl
        )) {
            $_SESSION['sucesso'] = "E-mail de confirmação reenviado com sucesso!";
        } else {
            $_SESSION['erro'] = "Erro ao reenviar e-mail de confirmação.";
            
        }
    }
    header("Location: " . PUBLIC_PATH . "/login.php");
    exit();
}
?>