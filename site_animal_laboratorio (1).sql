CREATE DATABASE site_animal;
-- Selecionar banco
USE site_animal;

-- Tabela de Usuários
CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    senha VARCHAR(255) NOT NULL,
    telefone VARCHAR(20),
    endereco TEXT,
    cpf_cnpj VARCHAR(18) UNIQUE NULL,
    tipo VARCHAR(20) NOT NULL DEFAULT 'usuario',
    data_cadastro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    token_verificacao VARCHAR(64) NOT NULL,
    verificado TINYINT(1) DEFAULT 0,
    token_reset VARCHAR(255) DEFAULT NULL
);

-- Tabela de Animais
CREATE TABLE animais (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    especie VARCHAR(50) NOT NULL,
    raca VARCHAR(100),
    idade INT,
    porte VARCHAR(20) NOT NULL,
    historico_medico TEXT,
    usuario_id INT,
    data_cadastro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    doencas_cronicas TEXT NULL,
    comportamento TEXT,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE SET NULL
);

-- Índices para otimizar buscas
CREATE INDEX idx_especie ON animais(especie);
CREATE INDEX idx_porte ON animais(porte);

-- Tabela de Solicitações de Adoção
CREATE TABLE solicitacoes_adocao (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    animal_id INT NOT NULL,
    status VARCHAR(20) NOT NULL DEFAULT 'pendente',
    mensagem TEXT,
    data_solicitacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE,
    FOREIGN KEY (animal_id) REFERENCES animais(id) ON DELETE CASCADE
);

-- Tabela de Histórico de Adoções
CREATE TABLE historico_adocoes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    animal_id INT NOT NULL,
    data_adocao TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE,
    FOREIGN KEY (animal_id) REFERENCES animais(id) ON DELETE CASCADE
);

-- Tabela de Notificações
CREATE TABLE notificacoes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    mensagem TEXT NOT NULL,
    data_envio TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE
);
ALTER TABLE form_adocao ADD COLUMN status VARCHAR(20) DEFAULT 'pendente';
SELECT * FROM solicitacoes_adocao WHERE status = 'pendente';
SELECT * FROM form_adocao WHERE status = 'pendente';
-- Tabela do Formulário de Adoção
CREATE TABLE form_adocao (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(255),
    email VARCHAR(255),
    telefone VARCHAR(20),
    endereco VARCHAR(255),
    tipo_moradia VARCHAR(50),
    possui_tela_protecao VARCHAR(10),
    condominio_aceita VARCHAR(10),
    espaco_para_animal VARCHAR(10),
    condicoes_financeiras VARCHAR(10),
    compromisso VARCHAR(10),
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE administrador (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    senha VARCHAR(255) NOT NULL
);
-- inserir dados para teste de login do admin
INSERT INTO administrador (nome, email, senha)
VALUES ('Lucas', 'lucas@example.com', 'senha123');

ALTER TABLE animais
  ADD COLUMN status VARCHAR(50) NOT NULL DEFAULT 'aguardando',
  ADD COLUMN foto_blob LONGBLOB NULL DEFAULT NULL, -- (essa so caso não tenha ainda, essa aqui é pra aparecer as fotos do anuncio na tela inicial)
  ADD COLUMN descricao TEXT NULL DEFAULT NULL;
  
select * from usuarios;

INSERT INTO usuarios (nome, email, senha)
VALUES ('Hemerson Jhonatan', 'hemerson.j.pereira@gmail.com', '123456');

use site_animal;
DROP TABLE IF EXISTS form_adocao;
CREATE TABLE form_adocao (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT, -- Caso queira vincular com usuário logado
    nome VARCHAR(255),
    email VARCHAR(255),
    telefone VARCHAR(20),
    endereco VARCHAR(255),
    tipo_moradia VARCHAR(50),
    possui_tela_protecao VARCHAR(10),
    condominio_aceita VARCHAR(10),
    espaco_para_animal VARCHAR(10),
    condicoes_financeiras VARCHAR(10),
    motivo_adocao VARCHAR(250),                 -- Novo campo!
    experiencia_animais VARCHAR(10),            -- Novo campo!
    outros_animais VARCHAR(10),                 -- Novo campo!
    compromisso VARCHAR(10),
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE SET NULL
);
SET GLOBAL max_allowed_packet = 67108864; -- exemplo: 64MB
ALTER TABLE form_adocao ADD COLUMN animal_id INT;

insert into usuarios (tipo) values ('casa');

INSERT INTO form_adocao (
    usuario_id, animal_id, nome, email, telefone, status, criado_em
)
SELECT 
    s.usuario_id, 
    s.animal_id,
    u.nome,
    u.email,
    u.telefone,
    s.status,
    s.data_solicitacao
FROM solicitacoes_adocao s
JOIN usuarios u ON s.usuario_id = u.id
WHERE NOT EXISTS (
    SELECT 1 FROM form_adocao f 
    WHERE f.usuario_id = s.usuario_id 
    AND f.animal_id = s.animal_id
);
DROP TABLE IF EXISTS solicitacoes_adocao;
ALTER TABLE historico_adocoes 
ADD COLUMN form_adocao_id INT,
ADD FOREIGN KEY (form_adocao_id) REFERENCES form_adocao(id);