<?php

class Animal{
    private $pdo;

    public function __construct(PDO $pdo){
        $this->pdo = $pdo;
    }

   public function create(
    $nome, $especie, $raca = null, $idade = null, $porte, $historico_medico = null, 
    $usuario_id = null, $localidade = null, $doencas_cronicas = null,
    $comportamento = null, $foto_blob = null, $status = 'aguardando'
    ) 
    {
    try {
        $sql = "INSERT INTO animais
            (nome, especie, raca, idade, porte, historico_medico,
             usuario_id, localidade, doencas_cronicas, comportamento, foto_blob, status)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            $nome,
            $especie,
            $raca,
            $idade,
            $porte,
            $historico_medico,
            $usuario_id,
            $localidade,
            $doencas_cronicas,
            $comportamento,
            $foto_blob,
            $status
        ]);
    } catch (PDOException $e) {
        error_log("Erro no cadastro do animal: " . $e->getMessage());
        return false;
    }
}


    public function listaAnimaisPorId($id_usuario){
        $sql = "SELECT * FROM animais WHERE usuario_id = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id_usuario]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAnimalByID($id){
        $sql = "SELECT * FROM animais WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    // funções para aparecer na seção do perfil do usuário
    public function countAnimaisDivulgados()
    {
        try {
            $sql = "SELECT COUNT(*) as total FROM animais";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['total'] ?? 0;
        } catch (PDOException $e) {
            error_log("Erro ao contar animais divulgados: " . $e->getMessage());
            return 0;
        }
    }

    public function countAnimaisAdotados()
    {
        try {
            $sql = "SELECT COUNT(*) as total FROM animais WHERE status = 'adotado'";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['total'] ?? 0;
        } catch (PDOException $e) {
            error_log("Erro ao contar animais adotados: " . $e->getMessage());
            return 0;
        }
    }
}
?>