<?php
require_once __DIR__ . '/../../config.php'; // Ou o arquivo correto que contém routes.php

// Inicia a sessão apenas se ainda não estiver ativa
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/User.php';

session_start();

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class MailService
{
    private $mailer;

    public function __construct()
    {
        $this->mailer = new PHPMailer(true);
        // Configurações do servidor SMTP
        $this->mailer->isSMTP();
        $this->mailer->Host = 'smtp.example.com';
        $this->mailer->SMTPAuth = true;
        $this->mailer->Username = 'seu@email.com';
        $this->mailer->Password = 'sua_senha';
        $this->mailer->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $this->mailer->Port = 587;
    }

    public function sendVerificationEmail($to, $name, $token)
    {
        try {
            $this->mailer->setFrom('seu@email.com', 'Seu Nome');
            $this->mailer->addAddress($to, $name);
            $this->mailer->isHTML(true);
            $this->mailer->Subject = 'Verifique seu email';
            $this->mailer->Body = "Clique no link para verificar: <a href='http://seusite.com/verify?token=$token'>Verificar Email</a>";
            
            return $this->mailer->send();
        } catch (Exception $e) {
            error_log("Erro ao enviar email: " . $e->getMessage());
            return false;
        }
    }
}

?>