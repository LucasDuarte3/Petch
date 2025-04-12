<?php

class User{
    private $pdo;

    // conexao com o banco
    public function __construct(PDO $pdo){
        $this->pdo = $pdo;
    }

    // aqui fica a função de cadastro do usuario
    public function create($nome, $email, $senha, $tipo = 'usuario', $telefone = null, $endereco = null, $cpf_cnpj = null) {
        try {
            // Validação básica de CPF/CNPJ
            $cpf_cnpj = preg_replace('/[^0-9]/', '', $cpf_cnpj);
            if (empty($cpf_cnpj) || (!in_array(strlen($cpf_cnpj), [11, 14]))) {
                throw new Exception("CPF/CNPJ inválido.");
            }

            $senhaHash = password_hash($senha, PASSWORD_DEFAULT);
            $sql = "INSERT INTO usuarios (nome, email, senha, tipo, telefone, endereco, cpf_cnpj) 
                    VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute([$nome, $email, $senhaHash, $tipo, $telefone, $endereco, $cpf_cnpj]);
            
        } catch (PDOException $e) {
            error_log("Erro no cadastro: " . $e->getMessage());
            
            // Verifica se o erro é por CPF/CNPJ duplicado
            if (strpos($e->getMessage(), 'cpf_cnpj') !== false) {
                throw new Exception("CPF/CNPJ já cadastrado!");
            }
            
            return false;
        }
    }

    public function createWithToken($nome, $email, $senha) {
        $token = bin2hex(random_bytes(16));
        $senhaHash = password_hash($senha, PASSWORD_DEFAULT);
        
        $sql = "INSERT INTO usuarios (nome, email, senha, token_verificacao) VALUES (?, ?, ?, ?)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$nome, $email, $senhaHash, $token]) ? $token : false;
    }
    
    public function verifyToken($token) {
        $sql = "UPDATE usuarios SET token_verificacao = NULL, verificado = 1 WHERE token_verificacao = ?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$token]);
    }

    // Verifica se e-mail já existe
    public function emailExists($email){
        $sql = "SELECT id FROM usuarios WHERE email = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$email]);
        return (bool) $stmt->fetch();
    }

    // Verifica se CPF/CNPJ já existe
    public function documentExists($cpf_cnpj) {
        $cpf_cnpj = preg_replace('/[^0-9]/', '', $cpf_cnpj);
        $sql = "SELECT id FROM usuarios WHERE cpf_cnpj = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$cpf_cnpj]);
        return (bool) $stmt->fetch();
    }

    // aqui ele vai buscar por email para fazer o login
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