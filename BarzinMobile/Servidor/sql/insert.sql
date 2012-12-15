-- Bar do João
-- 5 mesas
-- 2 categorias (Lanches, Bebidas)
-- 2 Subcategorias da categoria Bebidas (Cervejas e Refrigerantes)
-- 2 Subcategorias da categoria Lanches (Sanduíches e Pastéis)
-- Sanduíches: X-Tudo, X-Burger, Misto Quente
-- Pastéis: Pastel Especial e Pastel de Queijo
-- Bebidas: Água
-- Cervejas: Skol e Heineken

-- Bar do Laerte
-- Tem nada ainda

-- ---------------------------------------------------------------------------------------------------
-- ------------------------------------------- BAR DO JOÃO -------------------------------------------
-- ---------------------------------------------------------------------------------------------------

-- Usuários

INSERT INTO usuarios (login, senha) VALUES ('joao_admin', md5('123456'));
INSERT INTO usuarios (login, senha) VALUES ('joao_func', md5('123456'));

-- Bar

INSERT INTO bares (admin_login, func_login, nome, rua, numero, complemento, bairro, cidade, estado, cep, telefone1, telefone2, email, versao_cardapio)
    VALUES (
        (SELECT login FROM usuarios WHERE login = 'joao_admin'),
        (SELECT login FROM usuarios WHERE login = 'joao_func'),
        'Bar do João', 'Rua Acre', '510', '', 'Liberdade', 'Campina Grande', 'PB', '58414-260', '(83) 3342.0823', '(83) 8824.5687', 'joaop.ribs@gmail.com', 1    
    );
    
-- Mesas

INSERT INTO mesas (bar_id, codigo, nome) 
	VALUES (
		(SELECT id FROM bares WHERE nome = 'Bar do João'),
		'0000', 
		'Mesa 1'
	);
	
INSERT INTO mesas (bar_id, codigo, nome) 
	VALUES (
		(SELECT id FROM bares WHERE nome = 'Bar do João'),
		'0001', 
		'Mesa 2'
	);
	
INSERT INTO mesas (bar_id, codigo, nome) 
	VALUES (
		(SELECT id FROM bares WHERE nome = 'Bar do João'),
		'0002', 
		'Mesa 3'
	);
	
INSERT INTO mesas (bar_id, codigo, nome) 
	VALUES (
		(SELECT id FROM bares WHERE nome = 'Bar do João'),
		'0003', 
		'Mesa 4'
	);
	
INSERT INTO mesas (bar_id, codigo, nome) 
	VALUES (
		(SELECT id FROM bares WHERE nome = 'Bar do João'),
		'0004', 
		'Mesa 5'
	);
	
-- Categorias

INSERT INTO categorias (bar_id, nome) 
    VALUES (
        (SELECT id FROM bares WHERE nome = 'Bar do João'), 'Lanches'
    );
    
INSERT INTO categorias (bar_id, nome) 
    VALUES (
        (SELECT id FROM bares WHERE nome = 'Bar do João'), 'Bebidas'
    );   
    
INSERT INTO categorias (bar_id, nome, categoria_mae_id) 
    VALUES (
        (SELECT id FROM bares WHERE nome = 'Bar do João'), 
        'Cervejas',
        (SELECT c.id FROM categorias c WHERE c.nome = 'Bebidas')
    );
    
INSERT INTO categorias (bar_id, nome, categoria_mae_id) 
    VALUES (
        (SELECT id FROM bares WHERE nome = 'Bar do João'), 
        'Refrigerantes',
        (SELECT c.id FROM categorias c WHERE c.nome = 'Bebidas')
    );
    
INSERT INTO categorias (bar_id, nome, categoria_mae_id) 
    VALUES (
        (SELECT id FROM bares WHERE nome = 'Bar do João'), 
        'Sanduíches',
        (SELECT c.id FROM categorias c WHERE c.nome = 'Lanches')
    );
    
INSERT INTO categorias (bar_id, nome, categoria_mae_id) 
    VALUES (
        (SELECT id FROM bares WHERE nome = 'Bar do João'), 
        'Pastéis',
        (SELECT c.id FROM categorias c WHERE c.nome = 'Lanches')
    );
    
-- Itens

INSERT INTO itens (categoria_id, nome, descricao, preco, disponivel)
    VALUES (
        (SELECT id FROM categorias WHERE nome = 'Sanduíches'),
        'X-Tudo',
        'Pão, hambúrguer, ovo, bacon, hambúrguer de frango, presunto, queijo, alface, tomate, milho e batata palha.',
        8, TRUE
    );    
 
INSERT INTO itens (categoria_id, nome, descricao, preco, disponivel)
    VALUES (
        (SELECT id FROM categorias WHERE nome = 'Sanduíches'),
        'X-Burger',
        'Pão, hambúrguer, queijo, alface, tomate, milho e batata palha.',
        5.5, TRUE
    );     
    
INSERT INTO itens (categoria_id, nome, descricao, preco, disponivel)
    VALUES (
        (SELECT id FROM categorias WHERE nome = 'Sanduíches'),
        'Misto Quente',
        'Pão de forma, 02 fatias de queijo e 02 fatias de presunto.',
        3, TRUE
    );
    
INSERT INTO itens (categoria_id, nome, descricao, preco, disponivel)
    VALUES (
        (SELECT id FROM categorias WHERE nome = 'Pastéis'),
        'Especial',
        'Carne moída, salsicha, queijo, presunto, milho e azeitona',
        4, TRUE
    );
    
INSERT INTO itens (categoria_id, nome, descricao, preco, disponivel)
    VALUES (
        (SELECT id FROM categorias WHERE nome = 'Pastéis'),
        'Pastel de Queijo',
        'Queijo mussarela',
        3, TRUE
    );
    
INSERT INTO itens (categoria_id, nome, descricao, preco, disponivel)
    VALUES (
        (SELECT id FROM categorias WHERE nome = 'Bebidas' AND bar_id = (SELECT id FROM bares WHERE nome = 'Bar do João')),
        'Água mineral',
        'Garrafa de 300 ml',
        2, TRUE
    );
    
INSERT INTO itens (categoria_id, nome, descricao, preco, disponivel)
    VALUES (
        (SELECT id FROM categorias WHERE nome = 'Cervejas' AND bar_id = (SELECT id FROM bares WHERE nome = 'Bar do João')),
        'Skol',
        'Lata de 350 ml',
        3, TRUE
    );
    
INSERT INTO itens (categoria_id, nome, descricao, preco, disponivel)
    VALUES (
        (SELECT id FROM categorias WHERE nome = 'Cervejas' AND bar_id = (SELECT id FROM bares WHERE nome = 'Bar do João')),
        'Heineken',
        'Lata de 350 ml',
        3.5, TRUE
    );
    
INSERT INTO itens (categoria_id, nome, descricao, preco, disponivel)
    VALUES (
        (SELECT id FROM categorias WHERE nome = 'Refrigerantes' AND bar_id = (SELECT id FROM bares WHERE nome = 'Bar do João')),
        'Refrigerante em Lata',
        'Lata de 350 ml',
        3, TRUE
    );
    
INSERT INTO itens (categoria_id, nome, descricao, preco, disponivel)
    VALUES (
        (SELECT id FROM categorias WHERE nome = 'Refrigerantes' AND bar_id = (SELECT id FROM bares WHERE nome = 'Bar do João')),
        'Refrigerante 1 Litro',
        'Garrafa de 1 L',
        5, TRUE
    );
    
INSERT INTO itens (categoria_id, nome, descricao, preco, disponivel)
    VALUES (
        (SELECT id FROM categorias WHERE nome = 'Refrigerantes' AND bar_id = (SELECT id FROM bares WHERE nome = 'Bar do João')),
        'Refrigerante 2 Litros',
        'Garrafa de 2 L pet',
        8, TRUE
    );     

-- -----------------------------------------------------------------------------------------------------    
-- ------------------------------------------- BAR DO LAERTE -------------------------------------------
-- -----------------------------------------------------------------------------------------------------

INSERT INTO usuarios (login, senha) VALUES ('laerte_admin', md5('123456'));
INSERT INTO usuarios (login, senha) VALUES ('laerte_func', md5('123456'));
    
INSERT INTO bares (admin_login, func_login, nome, rua, numero, complemento, bairro, cidade, estado, cep, telefone1, telefone2, email, versao_cardapio)
    VALUES (
        (SELECT login FROM usuarios WHERE login = 'laerte_admin'),
        (SELECT login FROM usuarios WHERE login = 'laerte_func'),
        'Bar do Laerte', 'Rua Emiliano Rosendo da Silva', '115', 'Bloco D, Apto. 003', 'Bodocongó', 'Campina Grande', 'PB', '58401-000', '(83) 9831.2483', '', 'laertexavier@gmail.com', 1    
    );