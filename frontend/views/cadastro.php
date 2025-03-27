<?php
session_start();
if(isset($_SESSION['erro'])){
    echo "<div class='alert alert-danger'>{$_SESSION['erro']}</div>";
    unset($_SESSION['erro']);
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
<!-- aqui vai ficar o formulario de cadastro -->
    <div>
        <!-- apenas teste -->
        <h1>Cadastre-se</h1>
    </div>
</body>
</html>