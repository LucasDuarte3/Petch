<?php
// Arquivo de rota que recebe o POST e dispara o controller de cadastro
// Deve ficar em public/divulgar-animal.php

// Carrega configuração global e caminhos
try {
    require_once __DIR__ . '/../config.php';
} catch (\Exception $e) {
    http_response_code(500);
    echo '<h1>Erro de configuração: ' . htmlspecialchars($e->getMessage()) . '</h1>';
    exit;
}

// Dispara o controller: ajustado para o caminho correto do controlador
// O AnimalController.php está em app/controllers/AnimalController.php
$controllerPath = __DIR__ . '/../app/controllers/AnimalController.php';
if (!file_exists($controllerPath)) {
    http_response_code(500);
    echo '<h1>Controller não encontrado em: ' . htmlspecialchars($controllerPath) . '</h1>';
    exit;
}

require_once $controllerPath;

// Se o controller não redirecionar, mostramos erro
http_response_code(400);
echo '<h1>Requisição inválida ou não processada.</h1>';