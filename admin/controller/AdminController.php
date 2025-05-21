<?php
require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../models/Admin.php';
require_once __DIR__ . '/../../config/database.php';
require_once ROOT_PATH . '/app/models/User.php';
require_once ROOT_PATH . '/app/models/Animal.php';
require_once ROOT_PATH . '/app/models/Adocao.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Verifica se é admin
//if (empty($_SESSION['usuario']) || $_SESSION['usuario']['tipo'] !== 'admin') {
//    header("Location: " . PUBLIC_PATH . "/login.php");
//    exit;
//}

$adminModel = new Admin($pdo);
$userModel = new User($pdo);
$animalModel = new Animal($pdo);
$adocaoModel = new FormAdocao($pdo);

// CRUD Usuários
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['acao'])) {
    try {
        switch ($_POST['acao']) {
            case 'bloquear_usuario':
                $adminModel->toggleUserStatus($_POST['user_id'], 0);
                $_SESSION['sucesso'] = "Usuário bloqueado com sucesso!";
                break;
                
            case 'desbloquear_usuario':
                $adminModel->toggleUserStatus($_POST['user_id'], 1);
                $_SESSION['sucesso'] = "Usuário desbloqueado com sucesso!";
                break;
                
            case 'excluir_usuario':
                $adminModel->deleteUser($_POST['user_id']);
                $_SESSION['sucesso'] = "Usuário excluído com sucesso!";
                break;
                
            case 'aprovar_adocao':
                $adminModel->approveAdoption($_POST['adocao_id'], $_POST['animal_id']);
                $_SESSION['sucesso'] = "Adoção aprovada com sucesso!";
                break;
                
            case 'recusar_adocao':
                $adminModel->rejectAdoption($_POST['adocao_id']);
                $_SESSION['sucesso'] = "Adoção recusada com sucesso!";
                break;
                
            case 'excluir_animal':
                $adminModel->deleteAnimal($_POST['animal_id']);
                $_SESSION['sucesso'] = "Animal excluído com sucesso!";
                break;
                
            case 'limpar_adocoes_antigas':
                $adminModel->cleanOldAdoptions(30); // 30 dias
                $_SESSION['sucesso'] = "Adoções antigas removidas com sucesso!";
                break;
        }
        
        header("Location: " . ADMIN_PATH . "/" . $_POST['redirect'] . ".php");
        exit;
    } catch (Exception $e) {
        $_SESSION['erro'] = $e->getMessage();
        header("Location: " . ADMIN_PATH . "/" . $_POST['redirect'] . ".php");
        exit;
    }
}

// Relatórios (pode ser acessado via GET)
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['relatorio'])) {
    switch ($_GET['relatorio']) {
        case 'estatisticas':
            echo json_encode([
                'total_usuarios' => $adminModel->countUsers(),
                'usuarios_ativos' => $adminModel->countActiveUsers(),
                'total_animais' => $adminModel->countAnimals(),
                'animais_disponiveis' => $adminModel->countAvailableAnimals(),
                //'total_adocoes' => $adminModel->countAdoptions(),
                //'adocoes_aprovadas' => $adminModel->countApprovedAdoptions()
            ]);
            exit;
    }
}

if ($_GET['relatorio'] ?? '' === 'data') {
    echo json_encode([
        //'aprovadas' => $adminModel->countApprovedAdoptions(),
        'pendentes' => $adminModel->countPendingAdoptions(),
        //'recusadas' => $adminModel->countRejectedAdoptions(),
        'usuarios' => $adminModel->getMonthlyUserGrowth(),
        'animais' => $adminModel->countAnimalsByType()
    ]);
    exit;
}
?>