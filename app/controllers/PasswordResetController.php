<?php
// app/controllers/PasswordResetController.php
require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../service/MailService.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Solicitação de redefinição (passo 1)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['acao']) && $_POST['acao'] === 'solicitar_reset') {
    $user = new User($pdo);
    $mailService = new MailService();

    try {
        if (empty($_POST['email'])) {
            throw new Exception("E-mail é obrigatório!");
        }

        // Gera token e envia e-mail
        $token = $user->generatePasswordResetToken($_POST['email']);
        $userData = $user->findByEmail($_POST['email']);

        if ($token && $userData) {
            $resetUrl = PUBLIC_PATH . "/RedefinirSenha.php?token=$token";
            
            if ($mailService->sendPasswordResetEmail(
                $userData['email'],
                $userData['nome'],
                $resetUrl
            )) {
                $_SESSION['sucesso'] = "E-mail de redefinição enviado! Verifique sua caixa de entrada.";
            } else {
                throw new Exception("Erro ao enviar e-mail. Tente novamente.");
            }
        }

        header("Location: " . PUBLIC_PATH . "/login.php");
        exit();
    } catch (Exception $e) {
        $_SESSION['erro'] = $e->getMessage();
        header("Location: " . PUBLIC_PATH . "/RedefinirSenha.php");
        exit();
    }
}

// Redefinição de senha (passo 2)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['acao']) && $_POST['acao'] === 'redefinir_senha') {
    $user = new User($pdo);

    try {
        if (empty($_POST['token']) || empty($_POST['senha']) || empty($_POST['confirmacao_senha'])) {
            throw new Exception("Todos os campos são obrigatórios!");
        }

        if ($_POST['senha'] !== $_POST['confirmacao_senha']) {
            throw new Exception("As senhas não coincidem!");
        }

        if ($user->resetPassword($_POST['token'], $_POST['senha'])) {
            $_SESSION['sucesso'] = "Senha redefinida com sucesso! Faça login.";
            header("Location: " . PUBLIC_PATH . "/login.php");
        } else {
            throw new Exception("Falha ao redefinir senha. Token inválido ou expirado.");
        }
    } catch (Exception $e) {
        $_SESSION['erro'] = $e->getMessage();
        header("Location: " . PUBLIC_PATH . "/RedefinirSenha.php?token=" . ($_POST['token'] ?? ''));
        exit();
    }
}
?>
