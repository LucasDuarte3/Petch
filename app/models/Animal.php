<?php

class Animal{
    private $pdo;

    public function __construct(PDO $pdo){
        $this->pdo = $pdo;
    }

    public function create($nome, $especie, $porte, $localidade, $status, $raca = null, $idade = null, $historico_medico = null, $caminho_foto=null, $usuario=null, $descricao=null) {
        try {
            $data_cadastro = $data_cadastro ?? date('Y-m-d H:i:s');
            $sql = "INSERT INTO animais (nome, especie, raca, idade, porte, descricao, historico_medico, status, caminho_foto, usuario_id, localidade, data_cadastro) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?);";
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute([$nome, $especie, $raca, $idade, $porte, $descricao, $historico_medico, $status, $caminho_foto, $usuario, $localidade, $data_cadastro]);
            
        } catch (PDOException $e) {
            error_log("Erro no cadastro do animal: " . $e->getMessage());            
            return false;
        }
    }

    public function listaAnimaisPorId($id_usuario){
        $sql = "SELECT * FROM animais WHERE id_usuario = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id_usuario]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getAnimalByID($id){
        $sql = "SELECT * FROM animais WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>