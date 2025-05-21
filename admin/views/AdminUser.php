<?php
// Processa ações
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once __DIR__ . '/../../app/models/Admin.php';
    $adminModel = new Admin($pdo);
    
    try {
        switch ($_POST['user_action']) {
            case 'toggle_status':
                $adminModel->toggleUserStatus($_POST['user_id'], $_POST['new_status']);
                $_SESSION['sucesso'] = "Status atualizado!";
                break;
            case 'delete':
                $adminModel->deleteUser($_POST['user_id']);
                $_SESSION['sucesso'] = "Usuário excluído!";
                break;
        }
    } catch (Exception $e) {
        $_SESSION['erro'] = $e->getMessage();
    }
}

// Obtém lista de usuários
$adminModel = new Admin($pdo);
$usuarios = $adminModel->listUsers();
?>

<div class="management-section">
    <h2><i class="bi bi-people-fill"></i> Gestão de Usuários</h2>
    <?php include __DIR__ . '/partials/user_table.php'; ?>
</div>