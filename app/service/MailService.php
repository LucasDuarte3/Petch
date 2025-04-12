<?php
// Inclua o arquivo do PHPMailer
require __DIR__ . '/../../vendor/autoload.php'; // Esta linha carrega automaticamente todas as classes do Composer, incluindo o PHPMailer

// Crie uma instância do PHPMailer
$mail = new PHPMailer\PHPMailer\PHPMailer();

// Configurações do servidor SMTP do Gmail
$mail->isSMTP();
$mail->Host = 'smtp.gmail.com';
$mail->SMTPAuth = true;
$mail->Username = 'senaclpoo@gmail.com'; // Insira seu endereço de e-mail do Gmail
$mail->Password = 'oormdbnavvkuiqgl'; // Insira sua senha do Gmail
$mail->SMTPSecure = 'tls';
$mail->Port = 587;

// Configurações do e-mail
$mail->setFrom('senaclpoo@gmail.com', 'Seu Nome');
$mail->addAddress('deyse.sousadasilva@gmail.com'); // Insira o endereço de e-mail do destinatário
$mail->Subject = 'Assunto do E-mail';
$mail->Body = 'Corpo do E-mail';

// Verifica se o e-mail foi enviado com sucesso
if($mail->send()) {
    echo 'E-mail enviado com sucesso!';
} else {
    echo 'Erro ao enviar o e-mail: ' . $mail->ErrorInfo;
}
?>
