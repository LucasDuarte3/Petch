<?php
// conexao do mysql
$host = 'localhost';
$db = 'site_animal';
$user = 'root';
$password = '';

try{
    $pdo = new PDO("mysql:host=$host;dbname:$db;chatset=utf8", $user, $password);
    $pdo->setAttibute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}catch{
    die("erro ao conectar: ", $e->getMessage());
}
?>