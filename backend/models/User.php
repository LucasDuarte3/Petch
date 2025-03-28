<?php
class User{
    private $pdo;

    // conexao com o banco
    public function __construct($pdo){
        $this->pdo = $pdo;
    }

    // aqui fica a função de cadastro do usuario
    public function create($nome, $email, $senha, $tipo = 'usuario'){
        try{
            $senhaHash = password_hash($senha, PASSWORD_DEFAULT);
            $sql = "INSERT INTO usuarios (nome, email, senha, tipo) VALUES (?, ?, ?, ?)";
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute([$nome, $email, $senhaHash, $tipo]);
        } catch (pdoException $e) {
            error_log("Erro no cadastro: " . $e->getMessage());
            return false;
        }
    }

    public function emailExist($email){
        $sql = "SELECT id FROM usuarios WHERE email = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$email]);
        return (bool) $stmt->fetch();
    }

    // aqui ele vai buscar por emial para fazer o login
    public function findByEmail($email){
        $sql = "SELECT * FROM usuarios WHERE email = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function verifyCredentials($email, $senha){
        $user = $this->findByEmail($email);
        return ($user && password_verify($senha, $user['senha'])) ? $user : false;
    }
}
?>