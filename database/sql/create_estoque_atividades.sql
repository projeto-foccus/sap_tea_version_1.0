CREATE TABLE estoque_atividades (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    aluno_id BIGINT UNSIGNED NOT NULL,
    eixo VARCHAR(30) NOT NULL,
    cod_atividade VARCHAR(20) NOT NULL,
    descricao VARCHAR(255) NULL,
    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY estoque_unico (aluno_id, eixo, cod_atividade),
    INDEX idx_aluno_eixo (aluno_id, eixo)
);
