<?php
include_once 'item.php';
include_once 'categoria.php';
include_once 'cardapio.php';
include_once 'bar.php';
include_once 'conta.php';
include_once 'pedido.php';
include_once 'erro.php';
include_once 'mesa.php';
include_once 'pessoa.php';

class DAO {

    function DAO() {
		$host = "localhost";
		$usuario = "barzin";
		$senha = "123456";
		$db = "barzin";
    	
    	mysql_connect($host, $usuario, $senha);
    	mysql_select_db($db);
        mysql_set_charset('utf8');
    }

    function alterar_senha($usuario, $senha) {
	    $usuario = mysql_real_escape_string($usuario);
	    $senha = mysql_real_escape_string($senha);
	    $alterar = mysql_query("UPDATE usuarios
    									SET senha=MD5('$senha')
    									WHERE login='$usuario'");
    	if (!$alterar) {
    		return mysql_error();
    	}
   		return "ok";
    }
    
    function consulta_codigo_valido($codigo_mesa) {
    	$codigo_mesa = mysql_real_escape_string($codigo_mesa);
    	$consulta = mysql_query("SELECT *
            						FROM mesas m
            						WHERE m.codigo = '$codigo_mesa'");
    	return mysql_num_rows($consulta) == 1;
    }
    
    function consulta_ha_conta_aberta($id_mesa) {
    	$id_mesa = mysql_real_escape_string($id_mesa);
    	$consulta = mysql_query("SELECT *
        										FROM contas c
        										WHERE c.estado='Aberta'
        											AND c.mesa_id=$id_mesa");
    	return mysql_num_rows($consulta) > 0;
    }
    
    function consulta_ha_conta_aberta_por_codigo($codigo_mesa) {
    	$codigo_mesa = mysql_real_escape_string($codigo_mesa);
    	$consulta = mysql_query("SELECT *
            						FROM contas c, mesas m 
            						WHERE c.estado = 'Aberta'
            							AND c.mesa_id = m.id 
    									AND m.codigo = '$codigo_mesa'");
    	return mysql_num_rows($consulta) > 0;
    }
    
    function consulta_ha_mesas_abertas($id_bar) {
    	return $this->consulta_num_mesas_abertas($id_bar) > 0;
    }
    
    function consulta_ha_pedido_com_item($id_item) {
    	$id_item = mysql_real_escape_string($id_item);
    	$consulta = mysql_query("SELECT *
    									FROM pedidos p
    									WHERE p.item_id=$id_item");
    	return mysql_num_rows($consulta) > 0;
    }
    
    function consulta_num_categorias_do_bar($id_bar) {
    	$id_bar = mysql_real_escape_string($id_bar);
    	$consulta = mysql_query("SELECT *
        									FROM categorias c 
        									WHERE c.bar_id=$id_bar");
    	return mysql_num_rows($consulta);
    }
    
    function consulta_num_contas_na_mesa($id_mesa) {
    	$id_mesa = mysql_real_escape_string($id_mesa);
    	$consulta = mysql_query("SELECT *
    									FROM contas c
    									WHERE c.mesa_id=$id_mesa");
    	return mysql_num_rows($consulta);
    }
    
    function consulta_num_itens_do_bar($id_bar) {
    	$id_bar = mysql_real_escape_string($id_bar);
    	$consulta = mysql_query("SELECT *
    									FROM itens i, categorias c 
    									WHERE i.categoria_id=c.id 
    										AND c.bar_id=$id_bar");
    	return mysql_num_rows($consulta);
    }
    
    function consulta_num_mesas_abertas($id_bar) {
    	$id_bar = mysql_real_escape_string($id_bar);
    	$consulta = mysql_query("SELECT *
    									FROM contas c, mesas m
    									WHERE c.estado='Aberta'
    										AND c.mesa_id=m.id
    										AND m.bar_id=$id_bar");
    	return mysql_num_rows($consulta);
    }
    
    function excluir_categoria($categoria) {
    	$id_categoria = mysql_real_escape_string($categoria->get_id());
    
    	$consulta_bar = mysql_query("SELECT c.bar_id
    										FROM categorias c
    										WHERE c.id=$id_categoria");
    	list($id_bar) = mysql_fetch_array($consulta_bar);
    
    	$excluir = mysql_query("DELETE FROM categorias
    	    							WHERE id=$id_categoria");
    	if (!$excluir) {
    		return mysql_error();
    	}
    	return $this->incrementar_versao_cardapio($id_bar);
    }
    
    function excluir_conta($conta) {
    	$id_conta = mysql_real_escape_string($conta->get_id());
    	$excluir = mysql_query("DELETE FROM contas
        									WHERE id=$id_conta");
    	if (!$excluir) {
    		return mysql_error();
    	}
    	return "ok";
    }
    
    function excluir_item($item) {
    	$id_item = mysql_real_escape_string($item->get_id());
    
    	$consulta_bar = mysql_query("SELECT c.bar_id
    										FROM itens i, categorias c
    										WHERE i.id=$id_item
    											AND c.id=i.categoria_id");
    	list($id_bar) = mysql_fetch_array($consulta_bar);
    
    	if ($this->consulta_ha_pedido_com_item($id_item)) {
    		$item->set_passado(true);
    		$salvar = $this->salvar_item($item);
    	}
    	else {
    		$salvar = mysql_query("DELETE FROM itens
    									WHERE id=$id_item");
    	}
    
    	if (!$salvar) {
    		return mysql_error();
    	}
    	return $this->incrementar_versao_cardapio($id_bar);
    
    }
    
    function excluir_mesa($mesa) {
    	$id_mesa = mysql_real_escape_string($mesa->get_id());
    	$excluir = mysql_query("DELETE FROM mesas
        									WHERE id=$id_mesa");
    	if (!$excluir) {
    		return mysql_error();
    	}
    	return "ok";
    }
    
    function excluir_pedido($id_pedido) {
    	$id_pedido = mysql_real_escape_string($id_pedido);
    	$excluir = mysql_query("DELETE FROM pedidos
    				    				WHERE id=$id_pedido");
    	if (!$excluir) {
    		return mysql_error();
    	}
    	return "ok";
    }
    
    function excluir_pessoa($id_pessoa) {
    	$id_pessoa = mysql_real_escape_string($id_pessoa);
    	
    	$consulta_se_existe = mysql_query("SELECT * 
    										FROM pessoas 
    										WHERE id = $id_pessoa");
    	if (mysql_num_rows($consulta_se_existe) != 1) {
    		return "Não foi encontrada pessoa com ID $id_pessoa";
    	}
    	
    	$consulta_pedidos = mysql_query("SELECT *
    				    					FROM pedidos_pessoas pp, pedidos p  
    				    					WHERE pp.pessoa_id = $id_pessoa 
    											AND pp.pedido_id = p.id 
    											AND p.estado != 'CANCELADO'");
    	if (mysql_num_rows($consulta_pedidos)) {
    		return "A pessoa já fez pedidos, então ela não poderá ser removida. Caso nenhum de seus pedidos tenham sido atendidos, eles devem ser cancelados e a pessoa poderá ser removida.";
    	}
    	
    	$consulta_outras_pessoas = mysqL_query("SELECT * 
												FROM pessoas p1, pessoas p2 
												WHERE p1.id != p2.id 
													AND p1.mesa_id = p2.mesa_id
    												AND p1.id = $id_pessoa");
    	if (mysql_num_rows($consulta_outras_pessoas) == 0) {
    		return "É necessário ter no mínimo uma pessoa na mesa.";
    	}
    	
    	$excluir = mysql_query("DELETE FROM pessoas
        				    		WHERE id=$id_pessoa");
    	if (!$excluir) {
    		return mysql_error();
    	}
    	return "ok";
    }
    
    function gerar_codigo_mesa() {
    	$consulta = mysql_query("SELECT LPAD(CAST(FLOOR(RAND() * 9999) AS CHAR(4)) , 4,  '0') AS codigo_aleatorio 
									FROM mesas
									WHERE \"codigo_aleatorio\" NOT IN (SELECT codigo 
																		FROM mesas)
									LIMIT 1");
    	list($codigo) = mysql_fetch_array($consulta);
    	return $codigo;
    }
    
    function get_tipo_usuario($login) {
    	$login = mysql_real_escape_string($login);
    	$consulta_admin = mysql_query("SELECT b.id
        									FROM bares b
        									WHERE b.admin_login='$login'");
    	if (mysql_num_rows($consulta_admin) == 1) {
    		return "admin";
    	}
    	else {
    		$consulta_func = mysql_query("SELECT b.id
        										FROM bares b
        										WHERE b.func_login='$login'");
    		if (mysql_num_rows($consulta_func)) {
    			return "funcionario";
    		}
    		else {
    			return "outro";
    		}
    	}
    }

    function incrementar_versao_cardapio($id_bar) {
    	$id_bar = mysql_real_escape_string($id_bar);
    	$incrementar = mysql_query("UPDATE bares
    										SET versao_cardapio = versao_cardapio + 1
    										WHERE id = $id_bar");
    	if (!$incrementar) {
    		return mysql_error();
    	}
    	return "ok";
    }
    
    function login_valido($login, $senha) {
        $usuario = mysql_real_escape_string(trim($login));
        $senha_md5 = md5(trim($senha));
        $consulta = mysql_query("SELECT * 
        							FROM usuarios 
        							WHERE login='$login' 
        								AND senha='$senha_md5'");
        if (mysql_num_rows($consulta) == 1) {
			return true;
        }
        return false;
    }
    
    function recupera_bar($id_bar) {
    	$id_bar = mysql_real_escape_string($id_bar);
    	$consulta_bar = mysql_query("SELECT b.*
        									FROM bares b
        	        						WHERE b.id=$id_bar");
    	if (mysql_num_rows($consulta_bar)) {
    		$bar = new Bar();
    		$bar->setar_atributos_consulta($consulta_bar);
    		return $bar;
    	}
    	return new Erro("Não foi encontrado bar com o ID $id_bar");
    }
    
    function recupera_bar_pelo_item($id_item) {
    	$id_item = mysql_real_escape_string($id_item);
    	$consulta = mysql_query("SELECT c.bar_id
        								FROM itens i, categorias c
        								WHERE i.id=$id_item
        									AND i.categoria_id=c.id");
    	if (mysql_num_rows($consulta) == 1) {
    		list($id_bar) = mysql_fetch_array($consulta);
    		$bar = $this->recupera_bar($id_bar);
    		return $bar;
    	}
    	return new Erro("Não foi encontrado item com o ID $id_item");
    }
    
    function recupera_bar_pelo_login($login_usuario) {
    	$login_usuario = mysql_real_escape_string($login_usuario);
        $consulta = mysql_query("SELECT b.* 
        							FROM bares b 
        							WHERE b.admin_login='$login_usuario'
	        							OR b.func_login='$login_usuario'");
        if (mysql_num_rows($consulta) == 1) {
        	$bar = new Bar();
        	$bar->setar_atributos_consulta($consulta);
        	return $bar;
        }
        return null;
    }
    
    function recupera_bar_pela_mesa($id_mesa) {
    	$id_mesa = mysql_real_escape_string($id_mesa);
    	$consulta = mysql_query("SELECT m.bar_id
            							FROM mesas m 
            							WHERE m.id=$id_mesa");
    	if (mysql_num_rows($consulta) == 1) {
    		list($id_bar) = mysql_fetch_array($consulta);
    		$bar = $this->recupera_bar($id_bar);
    		return $bar;
    	}
    	return new Erro("Não foi encontrada mesa com o ID $id_mesa");
    }
    
    function recupera_bar_pelo_codigo_da_mesa($codigo_mesa) {
    	$codigo_mesa = mysql_real_escape_string($codigo_mesa);
    	$consulta = mysql_query("SELECT m.bar_id
                					FROM mesas m 
                					WHERE m.codigo = '$codigo_mesa'");
    	if (mysql_num_rows($consulta) == 1) {
    		list($id_bar) = mysql_fetch_array($consulta);
    		$bar = $this->recupera_bar($id_bar);
    		return $bar;
    	}
    	return new Erro("Não foi encontrada mesa com o código $codigo_mesa");
    }
    
    function recupera_bares_todos() {
    	$consulta = mysql_query("SELECT id   
    								FROM bares 
    								ORDER BY nome");
    	$bares = array();
    	while (list($id) = mysql_fetch_array($consulta)) {
    		$bares[] = $this->recupera_bar($id);
    	}
    	return $bares;
    }
    
    function recupera_cardapio($bar) {
    	$id_bar = mysql_real_escape_string($bar->get_id());
    	$cardapio = new Cardapio();
    	$consulta_categorias = mysql_query("SELECT c.id
    										FROM categorias c
    										WHERE c.categoria_mae_id IS NULL
    											AND c.bar_id=$id_bar
    										ORDER BY c.nome");
    	while (list($categoria_id) = mysql_fetch_row($consulta_categorias)) {
    		$categoria = $this->recupera_categoria($categoria_id);
    		$cardapio->adiciona_categoria($categoria);
    	}
    	$consulta_versao = mysql_query("SELECT b.versao_cardapio
    										FROM bares b
    										WHERE b.id=$id_bar");
    	list($versao_cardapio) = mysql_fetch_array($consulta_versao);
    	$cardapio->set_versao($versao_cardapio);
    	return $cardapio;
    } 

    function recupera_categoria($id_categoria) {
    	$id_categoria = mysql_real_escape_string($id_categoria);
    	$consulta_categoria = mysql_query("SELECT c.*
    										FROM categorias c
    										WHERE c.id=$id_categoria");
    	if (mysql_num_rows($consulta_categoria) == 1) {
    		$categoria = new Categoria();
    		$categoria->setar_atributos_consulta($consulta_categoria);
    		$consulta_subcategorias = mysql_query("SELECT c.id
    												FROM categorias c
    												WHERE c.categoria_mae_id=$id_categoria
    												ORDER BY c.nome ASC");
    		while (list($subcategoria_id) = mysql_fetch_row($consulta_subcategorias)) {
    			$subcategoria = $this->recupera_categoria($subcategoria_id);
    			$categoria->adiciona_subcategoria($subcategoria);
    		}
    		$consulta_itens = mysql_query("SELECT i.id
											FROM itens i
											WHERE i.categoria_id=$id_categoria
												AND i.passado=0
    										ORDER BY i.nome ASC");
    		while (list($item_id) = mysql_fetch_array($consulta_itens)) {
    			$item = $this->recupera_item($item_id);
    			$categoria->adiciona_item($item);
    		}
    		return $categoria;
    	}
    	return null;
    }
    
    function recupera_conta($id_conta) {
    	$id_conta = mysql_real_escape_string($id_conta);
    	$consulta_conta = mysql_query("SELECT c.id, c.mesa_id, c.estado, UNIX_TIMESTAMP(c.data_hora_abertura) AS data_hora_abertura, UNIX_TIMESTAMP(c.data_hora_fechamento) AS data_hora_fechamento
        									FROM contas c
        									WHERE c.id=$id_conta");
    	if (mysql_num_rows($consulta_conta)) {
    		$conta = new Conta();
    		$conta->setar_atributos_consulta($consulta_conta);
    		$consulta_pedidos = mysql_query("SELECT p.id
        											FROM pedidos p
        											WHERE p.conta_id=".$conta->get_id()."
        												AND p.estado='Atendido'");
    		$total = 0;
    		while (list($id_pedido) = mysql_fetch_array($consulta_pedidos)) {
    			$pedido = $this->recupera_pedido($id_pedido);
    			$conta->adicionar_pedido($pedido);
    			$total += $this->recupera_total_pedido($id_pedido);
    		}
    		$conta->set_total($total);
    		return $conta;
    	}
    	return new Erro("Não foi encontrada conta com o ID $id_conta");
    }
    
    function recupera_conta_aberta($id_mesa) {
    	$id_mesa = mysql_real_escape_string($id_mesa);
    	$consulta = mysql_query("SELECT c.id
        								FROM contas c
        								WHERE c.mesa_id=$id_mesa
        									AND c.estado='Aberta'");
    	if (mysql_num_rows($consulta) == 1) {
    		list($id_conta) = mysql_fetch_array($consulta);
    		return $this->recupera_conta($id_conta);
    	}
    	return null;
    }
    
    function recupera_contas_fechadas($id_mesa) {
    	$id_mesa = mysql_real_escape_string($id_mesa);
    	$consulta = mysql_query("SELECT c.id
            								FROM contas c
            								WHERE c.mesa_id=$id_mesa
            									AND c.estado='Fechada'
        									ORDER BY c.data_hora_abertura DESC");
    	$contas = array();
    	while (list($id_conta) = mysql_fetch_array($consulta)) {
    		$contas[] = $this->recupera_conta($id_conta);
    	}
    	return $contas;
    }
    
    function recupera_item($id_item, $pasta_raiz = "..") {
    	$id_item = mysql_real_escape_string($id_item);
    	$consulta_item = mysql_query("SELECT i.*
    									FROM itens i
    									WHERE i.id=$id_item");
    	if (mysql_num_rows($consulta_item) == 1) {
    		$item = new Item();
    		$item->setar_atributos_consulta($consulta_item);
    		$item->set_pasta_raiz($pasta_raiz);
    		return $item;
    	}
    	return new Erro("Não foi encontrado item com o ID $id_item");
    }
    
    function recupera_mesa($id_mesa) {
    	$id_mesa = mysql_real_escape_string($id_mesa);
    	$consulta_mesa = mysql_query("SELECT m.*
    										FROM mesas m
            								WHERE m.id=$id_mesa");
    	if (mysql_num_rows($consulta_mesa)) {
    		$mesa = new Mesa();
    		$mesa->setar_atributos_consulta($consulta_mesa);
    		foreach ($this->recupera_pessoas_por_mesa($id_mesa) as $pessoa) {
    			$mesa->adicionar_pessoa($pessoa);
    		}
    		return $mesa;
    	}
    	return new Erro("Não foi encontrada mesa com o ID $id_mesa");
    }
    
    function recupera_mesa_pela_conta($id_conta) {
    	$id_conta = mysql_real_escape_string($id_conta);
    	$consulta_mesa = mysql_query("SELECT m.nome
            										FROM contas c, mesas m
            										WHERE c.id=$id_conta
            											AND c.mesa_id=m.id");
    	list($nome) = mysql_fetch_array($consulta_mesa);
    	return $nome;
    }
    
    function recupera_mesa_pelo_codigo($codigo_mesa, $id_bar) {
    	$codigo_mesa = mysql_real_escape_string($codigo_mesa);
    	$id_bar = mysql_real_escape_string($id_bar);
    	
    	$consulta_mesa = mysql_query("SELECT m.id
                						FROM mesas m
                						WHERE m.codigo = '$codigo_mesa'
                							AND m.bar_id = $id_bar");
                							
    	if (mysql_num_rows($consulta_mesa) != 1) {
    		return new Erro("Não foi encontrada mesa com o código $codigo_mesa, no bar com id $id_bar");
    	}
    	list($id) = mysql_fetch_array($consulta_mesa);
    	return $this->recupera_mesa($id);
    }
    
    function recupera_mesas($id_bar) {
    	$id_bar = mysql_real_escape_string($id_bar);
    	$consulta = mysql_query("SELECT m.id
        								FROM mesas m
        								WHERE m.bar_id=$id_bar
        								ORDER BY m.nome");
    	$lista_mesas = array();
    	while (list($id_mesa) = mysql_fetch_array($consulta)) {
    		$lista_mesas[] = $this->recupera_mesa($id_mesa);
    	}
    	return $lista_mesas;
    }
    
    function recupera_pedido($id_pedido) {
    	$id_pedido = mysql_real_escape_string($id_pedido);
    	$consulta_pedido = mysql_query("SELECT p.id, p.item_id, p.conta_id, p.quantidade, p.estado, UNIX_TIMESTAMP(p.data_hora) AS data_hora
    										FROM pedidos p
    										WHERE p.id=$id_pedido");
    	if (mysql_num_rows($consulta_pedido)) {
    		$pedido = new Pedido();
    		$pedido->setar_atributos_consulta($consulta_pedido);
    		$pedido->set_hora_formatado();
    		return $pedido;
    	}
    	return new Erro("Não foi encontrado pedido com o ID $id_pedido");
    }
    
    function recupera_pedidos_pendentes_do_bar($id_bar) {
    	$pedidos = array();
    	$id_bar = mysql_real_escape_string($id_bar);
    	$consulta_pedidos_pendentes = mysql_query("SELECT DISTINCT p.id
    												FROM pedidos p, contas c, mesas m
    												WHERE p.estado='Pendente'
    													AND p.conta_id=c.id
    													AND c.mesa_id=m.id
    													AND m.bar_id=$id_bar
    												ORDER BY p.data_hora ASC");
    	while (list($id_pedido) = mysql_fetch_array($consulta_pedidos_pendentes)) {
    		$pedido = $this->recupera_pedido($id_pedido);
    		if (get_class($pedido) == "Pedido") {
    			$pedidos[] = $pedido;
    		}
    	}
    	return $pedidos;
    }
    
    function recupera_pedidos_pendentes_da_mesa($id_mesa) {
    	$pedidos = array();
    	$id_mesa = mysql_real_escape_string($id_mesa);
    	$consulta_pedidos_pendentes = mysql_query("SELECT DISTINCT p.id
        												FROM pedidos p, contas c
        												WHERE p.estado='Pendente'
        													AND p.conta_id=c.id
        													AND c.mesa_id=$id_mesa
        												ORDER BY p.data_hora ASC");
    	while (list($id_pedido) = mysql_fetch_array($consulta_pedidos_pendentes)) {
    		$pedido = $this->recupera_pedido($id_pedido);
    		if (get_class($pedido) == "Pedido") {
    			$pedidos[] = $pedido;
    		}
    	}
    	return $pedidos;
    }
    
    function recupera_pessoa($id_pessoa) {
    	$id_pessoa = mysql_real_escape_string($id_pessoa);
    	$consulta_pessoa = mysql_query("SELECT p.* 
        										FROM pessoas p
        										WHERE p.id=$id_pessoa");
    	if (mysql_num_rows($consulta_pessoa)) {
    		$pessoa = new Pessoa();
    		$pessoa->setar_atributos_consulta($consulta_pessoa);
    		return $pessoa;
    	}
    	return new Erro("Não foi encontrado pessoa com o ID $id_pessoa");
    }
    
    function recupera_pessoas_por_mesa($id_mesa) {
    	$id_mesa = mysql_real_escape_string($id_mesa);
    	$consulta = mysql_query("SELECT p.id
            						FROM pessoas p
            						WHERE p.mesa_id=$id_mesa
            						ORDER BY p.nome");
    	$lista_pessoas = array();
    	while (list($id_pessoa) = mysql_fetch_array($consulta)) {
    		$lista_pessoas[] = $this->recupera_pessoa($id_pessoa);
    	}
    	return $lista_pessoas;
    }
    
    function recupera_total_pedido($id_pedido) {
    	$id_pedido = mysql_real_escape_string($id_pedido);
    	$consulta = mysql_query("SELECT p.quantidade * i.preco
    								FROM pedidos p, itens i
    								WHERE p.item_id=i.id
    									AND p.id=$id_pedido");
    	list($total) = mysql_fetch_array($consulta);
    	return $total;
    }
    
    
    
    function recupera_ultima_atualizacao_pedidos($id_bar) {
    	$id_bar = mysql_real_escape_string($id_bar);
    	$consulta = mysql_query("SELECT UNIX_TIMESTAMP(b.ultima_atualizacao_pedidos) 
    								FROM bares b 
    								WHERE b.id = $id_bar");
    	list($timestamp) = mysql_fetch_array($consulta);
    	return $timestamp;
    }
    
    function salvar_categoria($categoria) {
    	$id_categoria = mysql_real_escape_string($categoria->get_id());
    	$nome_categoria = mysql_real_escape_string($categoria->get_nome());
    	$id_bar = mysql_real_escape_string($categoria->get_bar_id());
    	$consulta = mysql_query("SELECT c.id
    								FROM categorias c
    								WHERE c.id=$id_categoria");
    	if (mysql_num_rows($consulta) == 1) {
    		$salvar = mysql_query("UPDATE categorias
    								SET nome='$nome_categoria'
    								WHERE id=$id_categoria");
    	}
    	else {
    		if ($categoria->get_categoria_mae_id() == null) {
    			$mae_id = "null";
    		}
    		else {
    			$mae_id = $categoria->get_categoria_mae_id();
    		}
    		$salvar = mysql_query("INSERT INTO categorias (categoria_mae_id, bar_id, nome) VALUES (
    								$mae_id, 
    								$id_bar,
    								'$nome_categoria')");
    		$id_categoria = mysql_insert_id();
    	}
    	
    	if (!$salvar) {
    		return mysql_error();
    	}
    	
    	$consulta_bar = mysql_query("SELECT c.bar_id
    											FROM categorias c
    											WHERE c.id=$id_categoria");
    	list($id_bar) = mysql_fetch_array($consulta_bar);
    	
    	return $this->incrementar_versao_cardapio($id_bar);
    }
	
	function salvar_conta($conta) {
		$id = mysql_real_escape_string($conta->get_id());
		$mesa_id = mysql_real_escape_string($conta->get_mesa_id());
		$estado = mysql_real_escape_string($conta->get_estado());
		$data_hora_abertura = mysql_real_escape_string($conta->get_data_hora_abertura_mysql());
		$data_hora_fechamento = mysql_real_escape_string($conta->get_data_hora_fechamento_mysql());
		$consulta = mysql_query("SELECT c.id
			    					FROM contas c
			    					WHERE c.id=$id");
		if (mysql_num_rows($consulta) == 1) {
			$salvar = mysql_query("UPDATE contas
			    					SET	mesa_id=$mesa_id,
			    						estado='$estado',
			    						data_hora_abertura='$data_hora_abertura',
			    						data_hora_fechamento='$data_hora_fechamento'
			    					WHERE id=$id");
		}
		else {
			$salvar = mysql_query("INSERT INTO contas (mesa_id, estado, data_hora_abertura, data_hora_fechamento) VALUES (
									$mesa_id,
									'$estado',
									'$data_hora_abertura',
									'$data_hora_fechamento')");
		}
		if (!$salvar) {
			return mysql_error();
		}
		return "ok";
	}
	
	function salvar_item($item) {
		$id_item = mysql_real_escape_string($item->get_id());
		$nome_item = mysql_real_escape_string($item->get_nome());
		$descricao_item = mysql_real_escape_string($item->get_descricao());
		$preco_item = mysql_real_escape_string($item->get_preco());
		$disponivel_item = mysql_real_escape_string($item->get_disponivel());
		$passado_item = mysql_real_escape_string($item->get_passado());
		$categoria_id_item = mysql_real_escape_string($item->get_categoria_id());
		$consulta = mysql_query("SELECT i.id
		    							FROM itens i
		    							WHERE i.id=$id_item");
		if (mysql_num_rows($consulta) == 1) {
			if ($this->consulta_ha_pedido_com_item($id_item)) {
				$salvar = mysql_query("UPDATE itens
						    				SET passado=1
						    				WHERE id=$id_item");
				if (!$salvar) {
					return mysql_error();
				}
				$novo_item = new Item();
				$novo_item->set_nome($nome_item);
				$novo_item->set_descricao($descricao_item);
				$novo_item->set_preco($preco_item);
				$novo_item->set_disponivel($disponivel_item);
				$novo_item->set_categoria_id($categoria_id_item);
				return $this->salvar_item($novo_item);
			}
			else {
				$salvar = mysql_query("UPDATE itens
											SET nome='$nome_item', 
												descricao='$descricao_item',
												preco=$preco_item,
												disponivel=$disponivel_item,
												categoria_id=$categoria_id_item
											WHERE id=$id_item");
			}
		}
		else {
			$salvar = mysql_query("INSERT INTO itens (nome, descricao, preco, disponivel, categoria_id, passado) VALUES (
										'$nome_item',
										'$descricao_item',
			$preco_item,
			$disponivel_item,
			$categoria_id_item,
			$passado_item)");
			$id_item = mysql_insert_id();
		}
	
		if (!$salvar) {
			return mysql_error();
		}

		$consulta_bar = mysql_query("SELECT c.bar_id
										FROM itens i, categorias c
										WHERE i.id=$id_item
											AND c.id=i.categoria_id");
		list($id_bar) = mysql_fetch_array($consulta_bar);

		return $this->incrementar_versao_cardapio($id_bar);
	}
	
	function salvar_mesa($mesa) {
		$id_mesa = mysql_real_escape_string($mesa->get_id());
		$bar_id = mysql_real_escape_string($mesa->get_bar_id());
		$nome = mysql_real_escape_string($mesa->get_nome());
		$consulta = mysql_query("SELECT t.id
		    								FROM mesas t
		    								WHERE t.id=$id_mesa");
		if (mysql_num_rows($consulta) == 1) {
			$salvar = mysql_query("UPDATE mesas
		    								SET nome='$nome',
		    									bar_id=$bar_id
		    								WHERE id=$id_mesa");
		}
		else {
			$consulta_nome = mysql_query("SELECT *
												FROM mesas m
												WHERE m.nome='$nome'
													AND m.bar_id = $bar_id");
			if (mysql_num_rows($consulta_nome) > 0) {
				return "Já há mesa cadastrada com o nome \"".$nome."\" nesse bar.";
			}
			$salvar = mysql_query("INSERT INTO mesas (bar_id, nome, codigo) VALUES (
									$bar_id,
									'$nome', 
									".$this->gerar_codigo_mesa().")");
			}
			$id_mesa = mysql_insert_id();
			if (!$salvar) {
				return mysql_error();
			}
			
			return "ok";
	}
	
	function salvar_pedido($pedido, $mesa_id = null) {
		$id = mysql_real_escape_string($pedido->get_id());
		$item_id = mysql_real_escape_string($pedido->get_item_id());
		$conta_id = mysql_real_escape_string($pedido->get_conta_id());
		$quantidade = mysql_real_escape_string($pedido->get_quantidade());
		$data_hora = mysql_real_escape_string($pedido->get_data_hora_mysql());
		$estado = mysql_real_escape_string($pedido->get_estado());
		$consulta = mysql_query("SELECT p.id
	    							FROM pedidos p
	    							WHERE p.id=$id");
		if (mysql_num_rows($consulta) == 1) {
			$salvar = mysql_query("UPDATE pedidos
	    								SET	item_id=$item_id,
	    									conta_id=$conta_id,
	    									quantidade=$quantidade,
	    									data_hora='$data_hora',
	    									estado='$estado'
	    								WHERE id=$id");
			$id_pedido_salvo = $id;
		}
		else {
			if ($mesa_id == null) {
				$erro = "Precisa informar mesa_id para essa operação";
				return $erro->get_json();
			}
			
			$salvar = mysql_query("INSERT INTO pedidos (item_id, conta_id, quantidade, estado, data_hora) VALUES (
									$item_id,
									$conta_id,
									$quantidade,
									'$estado',
									'$data_hora')");
			$id_pedido_salvo = mysql_insert_id();
		}
		
		if (!$salvar) {
			$erro = new Erro(mysql_error());
			return $erro->get_json();
		}
		
		$pedido = $this->recupera_pedido($id_pedido_salvo);
		return $pedido->get_json();
	}
	
	function salvar_pessoa($pessoa) {
		$id = mysql_real_escape_string($pessoa->get_id());
		$nome = mysql_real_escape_string($pessoa->get_nome());
		$mesa_id = mysql_real_escape_string($pessoa->get_mesa_id());
		$consulta_nome = mysql_query("SELECT *
										FROM pessoas p 
										WHERE p.nome = '$nome'
											AND p.mesa_id = $mesa_id
											AND p.id != $id");
		if (mysql_num_rows($consulta_nome)) {
			return new Erro("Já existe uma pessoa com o nome $nome na mesa.");
		}
		$consulta_se_ja_existe = mysql_query("SELECT *
	    										FROM pessoas p  
	    										WHERE p.id = $id");
		if (mysql_num_rows($consulta_se_ja_existe) > 0) {
			$alterar = mysql_query("UPDATE pessoas 
										SET nome = '$nome'
										WHERE id = $id");
			if (!$alterar) {
				return new Erro(mysql_error());
			}
			$id_pessoa = $id;
		}
		else {
			$inserir = mysql_query("INSERT INTO pessoas (nome, mesa_id) VALUES (
	    								'$nome',
	    								$mesa_id)");
			$id_pessoa = mysql_insert_id();
			if (!$inserir) {
				return new Erro(mysql_error());
			}
			
			$consulta_se_existe_conta_aberta = mysql_query("SELECT c.id
																FROM contas c
																WHERE mesa_id = $mesa_id
																	AND estado='Aberta'");
			if (mysql_num_rows($consulta_se_existe_conta_aberta) == 0) {
				$conta = new Conta($mesa_id);
				$criar_conta = $this->salvar_conta($conta);
				if ($criar_conta != 'ok') {
					return new Erro(mysql_error());
				}
			}
		}
		$pessoa = $this->recupera_pessoa($id_pessoa);
		return $pessoa;
	}
	
	function setar_precisa_atualizar_pedidos($id_bar) {
		$id_bar = mysql_real_escape_string($id_bar);
		$setar = mysql_query("UPDATE bares 
								SET ultima_atualizacao_pedidos = NOW()
								WHERE id = $id_bar");
		if (!$setar) {
			return mysql_error();
		}
		return "ok";
	}
}
?>