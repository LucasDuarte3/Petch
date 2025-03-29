<?php
session_start();

function protegerRota($nivelAcesso = 'usuario'){
    if(isset($_SESSION['usuario'])){
        if(!isset($_SESSION['usuario'])){
            $_SESSION['erro'] = "Faça login para acessar!";
            header("Location: /frontend/views/login.php");
            exit;
        }

        if ($_SESSION['usuario']['tipo'] !== $nivelAcesso && $nivelAcesso !== 'usuario') {
            $_SESSION['erro'] = "Acesso não autorizado!";
            header("Location: /frontend/views/dashboard.php");
            exit;
        }
    }
}

?>