create database pincelsolto;

use pincelsolto;

CREATE TABLE eventos (
	id INT AUTO_INCREMENT PRIMARY KEY,
    nome_evento VARCHAR(255) NOT NULL,
    horario VARCHAR(255) NOT NULL,
    data_evento DATE NOT NULL,
    local_evento VARCHAR(255) NOT NULL,
    descricao_evento TEXT NOT NULL,
    imagem_evento VARCHAR(255) NOT NULL,
    link_evento VARCHAR(255),
    data_publicacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    id_artista INT,
    FOREIGN KEY (id_artista) REFERENCES artista(id)  -- Caso tenha uma tabela de artistas
);

CREATE TABLE imagens (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome_arquivo VARCHAR(255) NOT NULL,
    caminho VARCHAR(255) NOT NULL,
    artista_id INT NOT NULL,
    descricao TEXT,
    FOREIGN KEY (artista_id) REFERENCES artista(id) ON DELETE CASCADE
);

CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    senha VARCHAR(255) NOT NULL,
    bio VARCHAR(255),
    foto_perfil VARCHAR(255)
);

CREATE TABLE artista (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    senha VARCHAR(255) NOT NULL,
    area VARCHAR(100) NOT NULL,
    estilo VARCHAR(100) NOT NULL,
    bio VARCHAR(255),
    foto_perfil VARCHAR(255)
);

CREATE TABLE mensagens_contato (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    mensagem TEXT NOT NULL,
    data_envio TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
