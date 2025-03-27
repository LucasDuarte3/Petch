<?php
class User{
    private $pdo;

    public function __constructor($pdo){
        $this->pdo = $pdo;
    }

    // aqui fica a função de cadastro do usuario
    public function create($nome, $email, $senha, $tipo = 'usuario'){
        $senhaHash = password_hash($senha, PASSWORD_DEFAULT);
        $sql = "INSERT INTO usuarios (nome, email, senha, tipo) VALUES (?, ?, ?, ?)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$nome, $email, $senhaHash, $tipo]);
    }

    // aqui ele vai buscar por emial para fazer o login
    public function findByEmail($email){
        $sql = "SELECT * FROM usuarios WHERE email = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>