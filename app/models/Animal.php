<?php

class Animal{
    private $pdo;

    public function __construct(PDO $pdo){
        $this->pdo = $pdo;
    }

    public function create($nome, $especie, $raca = null, $idade = null, $porte, $historico_medico = null, $caminho_foto = null, $usuario = null, $localidade = null, $doencas_cronicas = null, $comportamento = null) {
        try {
            $sql = "INSERT INTO animais (nome, especie, raca, idade, porte, historico_medico, caminho_foto, 
                    usuario_id, localidade, doencas_cronicas, comportamento) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?);";
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute([
                $nome, $especie, $raca, $idade, $porte, $historico_medico, $caminho_foto,
                $usuario, $localidade, $doencas_cronicas, $comportamento
            ]);
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