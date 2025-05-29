<?php
class Admin
{
    private $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }
    public function countAdoptions()
    {
        $sql = "SELECT COUNT(*) as total FROM historico_adocoes";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['total'];
    }
    public function listAdoptions($filters = [])
{
    $sql = "SELECT sa.id, sa.status, sa.data_solicitacao,
                   u.nome AS usuario_nome,
                   a.nome AS animal_nome
            FROM solicitacoes_adocao sa
            JOIN usuarios u ON sa.usuario_id = u.id
            JOIN animais a ON sa.animal_id = a.id
            WHERE 1=1";
    $params = [];

    if (isset($filters['status'])) {
        $sql .= " AND sa.status = ?";
        $params[] = $filters['status'];
    }

    $sql .= " ORDER BY sa.id DESC";
    $stmt = $this->pdo->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


    // Usuários
    public function toggleUserStatus($userId, $status)
    {
        $stmt = $this->pdo->prepare("UPDATE usuarios SET ativo = ? WHERE id = ?");
        return $stmt->execute([$status, $userId]);
    }

    public function deleteUser($userId)
    {
        // Antes de deletar, podemos mover para uma tabela de usuários excluídos
        $stmt = $this->pdo->prepare("DELETE FROM usuarios WHERE id = ?");
        return $stmt->execute([$userId]);
    }

    public function listUsers($filters = [])
    {
        $sql = "SELECT * FROM usuarios WHERE 1=1";
        $params = [];

        if (!empty($filters['search'])) {
            $sql .= " AND (nome LIKE ? OR email LIKE ?)";
            $params[] = '%' . $filters['search'] . '%';
            $params[] = '%' . $filters['search'] . '%';
        }

        if (isset($filters['ativo'])) {
            $sql .= " AND ativo = ?";
            $params[] = $filters['ativo'];
        }

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Animais
    public function deleteAnimal($animalId)
    {
        $stmt = $this->pdo->prepare("DELETE FROM animais WHERE id = ?");
        return $stmt->execute([$animalId]);
    }

    public function listAnimals($filters = [])
{
    $sql = "SELECT a.id, a.nome, a.especie, a.raca, a.idade, a.porte, a.status, u.nome as dono_nome
            FROM animais a
            LEFT JOIN usuarios u ON a.usuario_id = u.id
            WHERE 1=1";
    $params = [];

    if (!empty($filters['search'])) {
        $sql .= " AND (a.nome LIKE ? OR a.raca LIKE ?)";
        $params[] = '%' . $filters['search'] . '%';
        $params[] = '%' . $filters['search'] . '%';
    }

    if (isset($filters['status'])) {
        $sql .= " AND a.status = ?";
        $params[] = $filters['status'];
    }

    $sql .= " ORDER BY a.id DESC";
    $stmt = $this->pdo->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


    // Adoções
    public function approveAdoption($adocaoId, $animalId)
    {
        $this->pdo->beginTransaction();
        try {
            // 1. Atualiza status da solicitação
            $stmt = $this->pdo->prepare("UPDATE solicitacoes_adocao SET status = 'aprovado' WHERE id = ?");
            $stmt->execute([$adocaoId]);

            // 2. Atualiza status do animal
            $stmt = $this->pdo->prepare("UPDATE animais SET status = 'adotado' WHERE id = ?");
            $stmt->execute([$animalId]);

            // 3. Registra no histórico
            $solicitacao = $this->pdo->query("SELECT usuario_id, animal_id FROM solicitacoes_adocao WHERE id = $adocaoId")->fetch();

            $stmt = $this->pdo->prepare("INSERT INTO historico_adocoes (usuario_id, animal_id) VALUES (?, ?)");
            $stmt->execute([$solicitacao['usuario_id'], $solicitacao['animal_id']]);

            $this->pdo->commit();
            return true;
        } catch (Exception $e) {
            $this->pdo->rollBack();
            throw $e;
        }
    }

    public function rejectAdoption($adocaoId)
    {
        $stmt = $this->pdo->prepare("UPDATE solicitacoes_adocao SET status = 'negado' WHERE id = ?");
        return $stmt->execute([$adocaoId]);
    }

    public function cleanOldAdoptions($days)
    {
        $stmt = $this->pdo->prepare("DELETE FROM solicitacoes_adocao WHERE status = 'pendente' AND data_solicitacao < DATE_SUB(NOW(), INTERVAL ? DAY)");
        return $stmt->execute([$days]);
    }

    //public function listAdoptions($filters = []) {
    //    $sql = "SELECT f.*, a.nome as animal_nome, u.nome as usuario_nome 
    //            FROM formulario_adocao f
    //            LEFT JOIN animais a ON f.animal_id = a.id
    //            LEFT JOIN usuarios u ON f.usuario_id = u.id
    //            WHERE 1=1";
    //    $params = [];
    //
    //    if (!empty($filters['status'])) {
    //        $sql .= " AND f.status = ?";
    //        $params[] = $filters['status'];
    //    }
    //
    //   $stmt = $this->pdo->prepare($sql);
    //   $stmt->execute($params);
    //   return $stmt->fetchAll(PDO::FETCH_ASSOC);
    //}

    // Relatórios
    public function countUsers()
    {
        $stmt = $this->pdo->query("SELECT COUNT(*) FROM usuarios");
        return $stmt->fetchColumn();
    }

    public function countActiveUsers()
    {
        $stmt = $this->pdo->query("SELECT COUNT(*) FROM usuarios WHERE ativo = 1");
        return $stmt->fetchColumn();
    }

    public function countAnimals()
    {
        $stmt = $this->pdo->query("SELECT COUNT(*) FROM animais");
        return $stmt->fetchColumn();
    }

    public function countAvailableAnimals()
    {
        $stmt = $this->pdo->query("SELECT COUNT(*) FROM animais WHERE adotado = 0");
        return $stmt->fetchColumn();
    }

    //public function countAdoptions() {
    //    $stmt = $this->pdo->query("SELECT COUNT(*) FROM formulario_adocao");
    //    return $stmt->fetchColumn();
    //}

    //public function countApprovedAdoptions() {
    //    $stmt = $this->pdo->query("SELECT COUNT(*) FROM formulario_adocao WHERE status = 'aprovado'");
    //    return $stmt->fetchColumn();
    //}

    public function countPendingAdoptions()
    {
        $stmt = $this->pdo->query("SELECT COUNT(*) FROM solicitacoes_adocao WHERE status = 'pendente'");
        return $stmt->fetchColumn();
    }

    public function getMonthlyUserGrowth()
    {
        $stmt = $this->pdo->query("SELECT 
            MONTH(data_cadastro) as mes, 
            COUNT(*) as total 
            FROM usuarios 
            WHERE YEAR(data_cadastro) = YEAR(CURRENT_DATE) 
            GROUP BY MONTH(data_cadastro) 
            ORDER BY mes");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function listPendingAnimals()
{
    $sql = "SELECT * FROM animais WHERE status = 'aguardando' ORDER BY id DESC";
    $stmt = $this->pdo->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


    public function countAnimalsByType()
    {
        $stmt = $this->pdo->query("SELECT especie, COUNT(*) as total FROM animais GROUP BY especie");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function approveAnimal($animalId)
{
    $stmt = $this->pdo->prepare("UPDATE animais SET status = 'disponível' WHERE id = ?");
    return $stmt->execute([$animalId]);
}

public function rejectAnimal($animalId)
{
    $stmt = $this->pdo->prepare("DELETE FROM animais WHERE id = ?");
    return $stmt->execute([$animalId]);
}

}
