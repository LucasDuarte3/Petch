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

    public function processarFormularioAdocao($dados) {
    $sql = "INSERT INTO form_adocao (
                usuario_id, animal_id, nome, email, telefone, endereco, 
                tipo_moradia, possui_tela_protecao, condominio_aceita, 
                espaco_para_animal, condicoes_financeiras, motivo_adocao, 
                experiencia_animais, outros_animais, compromisso, status
            ) VALUES (
                :usuario_id, :animal_id, :nome, :email, :telefone, :endereco, 
                :tipo_moradia, :possui_tela_protecao, :condominio_aceita, 
                :espaco_para_animal, :condicoes_financeiras, :motivo_adocao, 
                :experiencia_animais, :outros_animais, :compromisso, 'pendente'
            )";
    
    $stmt = $this->pdo->prepare($sql);
    return $stmt->execute([
        ':usuario_id' => $dados['usuario_id'] ?? null,
        ':animal_id' => $dados['animal_id'] ?? null,
        ':nome' => $dados['nome'],
        ':email' => $dados['email'],
        ':telefone' => $dados['telefone'],
        ':endereco' => $dados['endereco'],
        ':tipo_moradia' => $dados['tipo_moradia'],
        ':possui_tela_protecao' => $dados['possui_tela_protecao'],
        ':condominio_aceita' => $dados['condominio_aceita'],
        ':espaco_para_animal' => $dados['espaco_para_animal'],
        ':condicoes_financeiras' => $dados['condicoes_financeiras'],
        ':motivo_adocao' => $dados['motivo_adocao'] ?? null,
        ':experiencia_animais' => $dados['experiencia_animais'],
        ':outros_animais' => $dados['outros_animais'],
        ':compromisso' => $dados['compromisso'] ?? 'Não'
    ]);
}

    public function listAdoptions($filters = [])
    {
        $sql = "SELECT 
                    f.id, 
                    f.status, 
                    f.criado_em AS data_solicitacao,
                    f.nome AS usuario_nome, 
                    f.email AS usuario_email,
                    f.telefone,
                    a.nome AS animal_nome, 
                    a.id AS animal_id,
                    f.motivo_adocao,
                    f.possui_tela_protecao,
                    f.condominio_aceita,
                    f.espaco_para_animal,
                    f.condicoes_financeiras,
                    f.experiencia_animais,
                    f.outros_animais,
                    f.compromisso
                FROM form_adocao f
                LEFT JOIN animais a ON f.animal_id = a.id
                WHERE 1=1";
        
        $params = [];
        
        if (isset($filters['status'])) {
            $sql .= " AND f.status = ?";
            $params[] = $filters['status'];
        }
        
        $sql .= " ORDER BY f.criado_em DESC";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function approveAdoption($adocaoId, $animalId)
    {
        $this->pdo->beginTransaction();
        try {
            // 1. Atualiza status da solicitação
            $stmt = $this->pdo->prepare("UPDATE form_adocao SET status = 'aprovado' WHERE id = ?");
            $stmt->execute([$adocaoId]);

            // 2. Atualiza status do animal
            $stmt = $this->pdo->prepare("UPDATE animais SET status = 'adotado' WHERE id = ?");
            $stmt->execute([$animalId]);

            $this->pdo->commit();
            return true;
        } catch (Exception $e) {
            $this->pdo->rollBack();
            throw $e;
        }
    }

    public function rejectAdoption($adocaoId)
    {
        $stmt = $this->pdo->prepare("UPDATE form_adocao SET status = 'recusado' WHERE id = ?");
        return $stmt->execute([$adocaoId]);
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
   // Parte de listagem dos animais agora inclui o dono, pois fizemos um LEFT JOIN na tabela de usuários.
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
    

    public function cleanOldAdoptions($days)
    {
        $stmt = $this->pdo->prepare("DELETE FROM form_adocao WHERE status = 'pendente' AND data_solicitacao < DATE_SUB(NOW(), INTERVAL ? DAY)");
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
        $stmt = $this->pdo->query("SELECT COUNT(*) FROM form_adocao WHERE status = 'pendente'");
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

 // Listagem exclusiva de anúncios "aguardando" para aparecer só na view do admin
    public function listPendingAnimals()
{
    $sql = "SELECT a.*, u.nome as dono_nome
            FROM animais a
            LEFT JOIN usuarios u ON a.usuario_id = u.id
            WHERE a.status = 'aguardando'
            ORDER BY a.id DESC";
    $stmt = $this->pdo->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


    public function countAnimalsByType()
    {
        $stmt = $this->pdo->query("SELECT especie, COUNT(*) as total FROM animais GROUP BY especie");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // ---------- ALTERAÇÃO PARA GERENCIAR ANÚNCIOS PENDENTES DO ADMIN ----------
    // Agora o admin pode aprovar (muda status pra disponível) ou rejeitar (deleta)
public function approveAnimal($animalId)
{
    // Se aprovar, só troca status pra "disponível"
    $sql = "UPDATE animais SET status = 'disponível' WHERE id = ?";
    $stmt = $this->pdo->prepare($sql);
    return $stmt->execute([$animalId]);
}


public function rejectAnimal($animalId)
{
    // Se recusar, deleta o animal do banco (não volta mais pro index)
    $sql = "DELETE FROM animais WHERE id = ?";
    $stmt = $this->pdo->prepare($sql);
    return $stmt->execute([$animalId]);
}

public function listarSolicitacoesAdocao() {
    $sql = "SELECT 
                fa.id,
                fa.criado_em AS data_solicitacao,
                u.nome AS usuario_nome,
                u.email AS email_usuario,
                a.nome AS animal_nome,
                fa.possui_tela_protecao,
                fa.condominio_aceita,
                fa.espaco_para_animal,
                fa.condicoes_financeiras,
                fa.motivo_adocao,
                fa.experiencia_animais,
                fa.outros_animais,
                fa.compromisso
            FROM form_adocao fa
            LEFT JOIN usuarios u ON fa.usuario_id = u.id
            LEFT JOIN animais a ON fa.animal_id = a.id
            ORDER BY fa.criado_em DESC";
    
    $stmt = $this->pdo->query($sql);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}



}
