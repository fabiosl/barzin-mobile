-- Antes de tudo, criar o banco e o usu�rio:
--
-- Entra no phpMyAdmin:
--	Tela inicial > Banco de Dados > Criar novo banco de dados
--		banco: barzin
--		collation: utf8_general_ci
--	Depois de criado, entrar em Tela inicial > barzin (na lista � esquerda) > Privil�gios > Adicionar novo usu�rio
--		Nome do usu�rio: barzin
--		Servidor: localhost
--		Senha: 123456
--		Conceder todos os privil�gios no banco de dados "barzin"

CREATE TABLE usuarios (
    login   VARCHAR(20)			PRIMARY KEY,
    senha   TEXT 				NOT NULL
);

CREATE TABLE bares (
    id              			SERIAL			PRIMARY KEY,
    admin_login     			VARCHAR(20) 	NOT NULL    UNIQUE,
    func_login      			VARCHAR(20) 	NOT NULL    UNIQUE,
    nome            			VARCHAR(50) 	NOT NULL    UNIQUE,
    rua							TEXT			NOT NULL,
    numero          			TEXT,   
    complemento     			TEXT,
    bairro          			TEXT 			NOT NULL,
    cidade          			TEXT    		NOT NULL,
    estado          			TEXT    		NOT NULL,
    cep             			TEXT    		NOT NULL,
    telefone1       			TEXT    		NOT NULL,
    telefone2       			TEXT    		NOT NULL,
    email           			VARCHAR(50)		NOT NULL    UNIQUE,
    ultima_atualizacao_pedidos 	TIMESTAMP 		NOT NULL,
    versao_cardapio				BIGINT UNSIGNED,  
    
    CONSTRAINT bar_admin_fk FOREIGN KEY (admin_login) REFERENCES usuarios(login),
    CONSTRAINT bar_func_fk FOREIGN KEY (func_login) REFERENCES usuarios(login)
);

CREATE TABLE categorias (
    id                  SERIAL				PRIMARY KEY,
    categoria_mae_id    BIGINT UNSIGNED,
    bar_id 				BIGINT UNSIGNED 	NOT NULL,
    nome                TEXT 				NOT NULL,
    
    CONSTRAINT categoria_bar_fk FOREIGN KEY (bar_id) REFERENCES bares(id) ON DELETE CASCADE,
    CONSTRAINT categoria_mae_fk FOREIGN KEY (categoria_mae_id) REFERENCES categorias(id) ON DELETE CASCADE
);

CREATE TABLE itens (
    id              SERIAL 				PRIMARY KEY,
    categoria_id    BIGINT UNSIGNED 	NOT NULL,
    nome            TEXT    			NOT NULL,
    descricao       TEXT    			NOT NULL,
    preco           FLOAT   			NOT NULL,
    disponivel		BOOLEAN 			NOT NULL,
    passado         BOOLEAN 			NOT NULL 	DEFAULT FALSE,
    
    CONSTRAINT item_categoria_fk FOREIGN KEY (categoria_id) REFERENCES categorias(id) ON DELETE CASCADE 
);

CREATE TABLE mesas (
	id			SERIAL 				PRIMARY KEY,
	bar_id		BIGINT UNSIGNED 	NOT NULL,
	codigo 		VARCHAR(4) 			UNIQUE,
	nome 		VARCHAR(30) 		NOT NULL,
	
	CONSTRAINT mesas_bar_fk FOREIGN KEY (bar_id) REFERENCES bares(id) ON DELETE CASCADE
);

CREATE TABLE contas (
    id                      SERIAL 				PRIMARY KEY,
    mesa_id               	BIGINT UNSIGNED 	NOT NULL,
    estado                  TEXT 				NOT NULL,     
    data_hora_abertura      TIMESTAMP,
    data_hora_fechamento    TIMESTAMP,
    
    CONSTRAINT conta_mesa_fk FOREIGN KEY (mesa_id) REFERENCES mesas(id) ON DELETE CASCADE,
    CONSTRAINT estado_conta_valido CHECK (estado IN ('ABERTA', 'FECHADA'))
);

CREATE TABLE pedidos (
    id          SERIAL				PRIMARY KEY,
    item_id     BIGINT UNSIGNED 	NOT NULL,
    conta_id    BIGINT UNSIGNED 	NOT NULL,
    quantidade  INTEGER     		NOT NULL,
    estado      TEXT        		NOT NULL,
    data_hora   TIMESTAMP   		NOT NULL,
    comentario	TEXT				NOT NULL,
    
    CONSTRAINT pedido_item_fk FOREIGN KEY (item_id) REFERENCES itens(id),
    CONSTRAINT pedido_conta_fk FOREIGN KEY (conta_id) REFERENCES contas(id) ON DELETE CASCADE,
    CONSTRAINT estado_pedido_valido CHECK (estado IN ('EM_ANDAMENTO', 'CONCLUIDO', 'CANCELADO'))
);

CREATE TABLE pessoas (
	id			SERIAL 				PRIMARY KEY,
	mesa_id		BIGINT UNSIGNED 	NOT NULL,
	nome		TEXT 				NOT NULL,
	
	CONSTRAINT mesa_pessoas_fk FOREIGN KEY (mesa_id) REFERENCES mesas(id) ON DELETE CASCADE
);

CREATE TABLE pedidos_pessoas (
	id			SERIAL 				PRIMARY KEY,
	pedido_id	BIGINT UNSIGNED 	NOT NULL,
	pessoa_id	BIGINT UNSIGNED 	NOT NULL,
	
	CONSTRAINT pedido_pedidos_pessoas_fk FOREIGN KEY (pedido_id) REFERENCES pedidos(id) ON DELETE CASCADE,
	CONSTRAINT pessoa_pedidos_pessoas_fk FOREIGN KEY (pessoa_id) REFERENCES pessoas(id) ON DELETE CASCADE
);

