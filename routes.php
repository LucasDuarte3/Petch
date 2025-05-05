<?php
// routes.php - Centralizador de Rotas do Sistema Petch

// Configurações básicas
define('ROOT_PATH', __DIR__); // Caminho Físico Real do Projeto
define('BASE_PATH', '/Petch'); // Altere conforme sua estrutura de pastas. Só para links, não para include!
define('PUBLIC_PATH', BASE_PATH . '/public');
define('ADMIN_PATH', BASE_PATH . '/admin');
define('VIEWS_PATH', BASE_PATH . '/views');
define('APP_PATH', BASE_PATH . '/app');
define('CONTROLLERS_PATH', APP_PATH . '/controllers');
define('MIDDLEWARE_PATH', APP_PATH . '/middleware');
define('ASSETS_PATH', BASE_PATH . '/assets');
define('JS_PATH', BASE_PATH . '/js');
define('IMG_PATH', BASE_PATH . '/images');
define('STATIC_EXTENSIONS', ['css', 'js', 'png', 'jpg', 'jpeg', 'gif', 'svg', 'woff', 'woff2', 'ttf', 'eot']);

// Função para redirecionamento seguro
function redirect($path) {
    header("Location: " . BASE_PATH . $path);
    exit;
}

// Inicializa a sessão
session_start();

// Verifica a rota solicitada
$request_uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$request_method = $_SERVER['REQUEST_METHOD'];

// Remove o BASE_PATH da URI
$route = str_replace(BASE_PATH, '', $request_uri);

// Permitir acesso direto a arquivos dentro de public
if (strpos($route, '/public/') === 0) {
    require_once __DIR__ . $route;
    return;
}


// Verifica se a solicitação é para arquivos estáticos
$file_extension = pathinfo($route, PATHINFO_EXTENSION);
if (in_array($file_extension, STATIC_EXTENSIONS)) {
    return;
}


// Rotas Públicas
$publicRoutes = [
    '/' => 'index.php', // Corrigido para apontar para a raiz
    '/public/login' => 'public/login.php',
    '/public/cadastro' => 'public/cadastro.php'
];

// Rotas de API/Controllers
$apiRoutes = [
    '/auth' => 'app/controllers/AuthController.php',
    '/user' => 'app/controllers/UserController.php',
    '/mail' => 'app/controllers/MailController.php',
    '/modelUser' => 'app/models/User.php',
    '/modelMail' => 'app/models/UserMail.php',
    '/service' => 'app/service/MailService.php'
];

// Rotas Administrativas
$adminRoutes = [
    '/admin/dashboard' => 'admin/dashboard.php'
];

// Processamento das Rotas
try {
    // Verifica rotas públicas
    if (array_key_exists($route, $publicRoutes)) {
        require_once __DIR__ . '/' . $publicRoutes[$route];
        return;
    }
    
    // Verifica rotas de API
    if (array_key_exists($route, $apiRoutes)) {
        require_once __DIR__ . '/' . $apiRoutes[$route];
        return;
    }
    
    // Verifica rotas administrativas
    if (array_key_exists($route, $adminRoutes)) {
        require_once __DIR__ . '/' . $adminRoutes[$route];
        return;
    }
    
    
  
    
} catch (Exception $e) {
    http_response_code(500);
    error_log("Erro na rota: " . $e->getMessage());
    require_once __DIR__ . '/public/500.php';
}
