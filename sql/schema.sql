-- Criação do banco de dados
CREATE DATABASE IF NOT EXISTS contact_list
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE contact_list;

-- Tabela principal de contatos
CREATE TABLE IF NOT EXISTS contacts (
    id         INT            AUTO_INCREMENT PRIMARY KEY,
    name       VARCHAR(100)   NOT NULL,
    phone      VARCHAR(20)    NOT NULL,
    email      VARCHAR(100)   NULL,
    address    VARCHAR(255)   NULL,
    category   ENUM('personal','work','family','other') NOT NULL DEFAULT 'personal',
    notes      TEXT           NULL,
    created_at TIMESTAMP      NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP      NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;