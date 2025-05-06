create database site_animal;

USE site_animal;
 
-- Usuários (administradores e usuários comuns)
CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    senha VARCHAR(255) NOT NULL,
    telefone VARCHAR(20),
    endereco TEXT,
    cpf_cnpj VARCHAR(18) UNIQUE NULL,  -- Aceita CPF ou CNPJ
    tipo VARCHAR(20) NOT NULL DEFAULT 'usuario', -- 'admin' ou 'usuario'
    data_cadastro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
 
-- Animais
CREATE TABLE animais (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    especie VARCHAR(50) NOT NULL,  -- Ex: 'cachorro', 'gato', 'outro'
    raca VARCHAR(100),
    idade INT,
    porte VARCHAR(20) NOT NULL,  -- Ex: 'pequeno', 'médio', 'grande'
    descricao TEXT,
    historico_medico TEXT,
    status VARCHAR(50) NOT NULL DEFAULT 'disponível',  -- Ex: 'disponível', 'em processo de adoção', 'adotado'
    caminho_foto VARCHAR(255),
    usuario_id INT,  -- Quem cadastrou o animal
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE SET NULL
);
 
-- Solicitações de adoção
CREATE TABLE solicitacoes_adocao (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    animal_id INT NOT NULL,
    status VARCHAR(20) NOT NULL DEFAULT 'pendente', -- Ex: 'pendente', 'aprovado', 'negado'
    mensagem TEXT,
    data_solicitacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE,
    FOREIGN KEY (animal_id) REFERENCES animais(id) ON DELETE CASCADE
);
 
-- Histórico de adoções
CREATE TABLE historico_adocoes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    animal_id INT NOT NULL,
    data_adocao TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE,
    FOREIGN KEY (animal_id) REFERENCES animais(id) ON DELETE CASCADE
);
 
-- Notificações
CREATE TABLE notificacoes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    mensagem TEXT NOT NULL,
    data_envio TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE
);
 
ALTER TABLE animais ADD COLUMN localidade VARCHAR(100) NOT NULL;
 
-- buscar animais filtros
SELECT * FROM animais
WHERE (localidade = 'São Paulo' OR 'São Paulo' = '')
AND (porte = 'médio' OR 'médio' = '')
AND (especie = 'cachorro' OR 'cachorro' = '');
 
ALTER TABLE animais ADD COLUMN data_cadastro TIMESTAMP DEFAULT CURRENT_TIMESTAMP;
SELECT * FROM animais ORDER BY data_cadastro DESC;
 
-- Otimizar buscas com índices
CREATE INDEX idx_especie ON animais(especie);
CREATE INDEX idx_porte ON animais(porte);
CREATE INDEX idx_localidade ON animais(localidade);
 
ALTER TABLE usuarios
ADD COLUMN token_verificacao VARCHAR(32) NULL,
ADD COLUMN verificado TINYINT(1) DEFAULT 0;
alter table usuarios add column token_reset varchar(255) default null;
alter table usuarios modify token_verificacao varchar(32) not null;
DELETE FROM usuarios WHERE id > 0;
ALTER TABLE usuarios MODIFY token_verificacao VARCHAR(64) NOT NULL;

SELECT * FROM usuarios;



