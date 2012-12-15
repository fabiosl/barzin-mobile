<?php
include_once 'item.php';
include_once 'categoria.php';
include_once 'cardapio.php';
include_once 'bar.php';
include_once 'conta.php';
include_once 'pedido.php';
include_once 'erro.php';
include_once 'resumo.php';
include_once 'resumo_lista.php';
include_once 'tablet.php';
include_once 'tablet_lista.php';
include_once 'mensagem.php';
include_once 'mensagem_lista.php';

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
    
    function recupera_bar_pelo_tablet($id_tablet) {
    	$id_tablet = mysql_real_escape_string($id_tablet);
    	$consulta = mysql_query("SELECT t.bar_id
            							FROM tablets t 
            							WHERE t.id=$id_tablet");
    	if (mysql_num_rows($consulta) == 1) {
    		list($id_bar) = mysql_fetch_array($consulta);
    		$bar = $this->recupera_bar($id_bar);
    		return $bar;
    	}
    	return new Erro("Não foi encontrado tablet com o ID $id_tablet");
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

    function recupera_resumos_da_conta($id_conta) {
    	$lista_resumos = new Resumo_lista();
    	$id_conta = mysql_real_escape_string($id_conta);
    	// Itens pedidos na conta, ordenados pelo pedido mais recente (pendentes antes de atendidos)
    	$consulta_itens_pedidos = mysql_query("SELECT DISTINCT p.item_id
												FROM pedidos p
												WHERE p.conta_id=1
												ORDER BY p.estado DESC, 
    												p.data_hora DESC");
    	while (list($id_item) = mysql_fetch_array($consulta_itens_pedidos)) {
    		$item = $this->recupera_item($id_item);
    		if (get_class($item) == "Item") {
    			$resumo = new Resumo();
	    		$resumo->set_item($item);
    			$consulta_pedidos_do_item = mysql_query("SELECT p.id
    														FROM pedidos p
    														WHERE p.item_id=$id_item
	    														AND p.conta_id=$id_conta
    														ORDER BY p.estado DESC,
    															p.data_hora DESC");
    			while (list($id_pedido) = mysql_fetch_array($consulta_pedidos_do_item)) {
	    			$pedido = $this->recupera_pedido($id_pedido);
	    			if (get_class($pedido) == "Pedido") {
	    				$resumo->adicionar_pedido($pedido);
	    			}
    			}
    			$lista_resumos->adicionar($resumo);
    		}
    	}
    	return $lista_resumos;
    }
    
    function recupera_pedidos_pendentes_do_bar($id_bar) {
    	$pedidos = array();
    	$id_bar = mysql_real_escape_string($id_bar);
    	$consulta_pedidos_pendentes = mysql_query("SELECT DISTINCT p.id
    												FROM pedidos p, contas c, tablets t
    												WHERE p.estado='Pendente'
    													AND p.conta_id=c.id
    													AND c.tablet_id=t.id
    													AND t.bar_id=$id_bar
    												ORDER BY p.data_hora ASC");
    	while (list($id_pedido) = mysql_fetch_array($consulta_pedidos_pendentes)) {
    		$pedido = $this->recupera_pedido($id_pedido);
    		if (get_class($pedido) == "Pedido") {
    			$pedidos[] = $pedido;
    		}
    	}
    	return $pedidos;
    }
    
    function recupera_pedidos_pendentes_do_tablet($id_tablet) {
    	$pedidos = array();
    	$id_tablet = mysql_real_escape_string($id_tablet);
    	$consulta_pedidos_pendentes = mysql_query("SELECT DISTINCT p.id
        												FROM pedidos p, contas c
        												WHERE p.estado='Pendente'
        													AND p.conta_id=c.id
        													AND c.tablet_id=$id_tablet
        												ORDER BY p.data_hora ASC");
    	while (list($id_pedido) = mysql_fetch_array($consulta_pedidos_pendentes)) {
    		$pedido = $this->recupera_pedido($id_pedido);
    		if (get_class($pedido) == "Pedido") {
    			$pedidos[] = $pedido;
    		}
    	}
    	return $pedidos;
    }
    
    function recupera_tablet_pela_conta($id_conta) {
    	$id_conta = mysql_real_escape_string($id_conta);
    	$consulta_tablet = mysql_query("SELECT t.nome
    										FROM contas c, tablets t
    										WHERE c.id=$id_conta
    											AND c.tablet_id=t.id");
    	list($nome) = mysql_fetch_array($consulta_tablet);
    	return $nome;
    }
    
    function recupera_tablet($id_tablet) {
    	$id_tablet = mysql_real_escape_string($id_tablet);
    	$consulta_tablet = mysql_query("SELECT t.*
											FROM tablets t
        									WHERE t.id=$id_tablet");
    	if (mysql_num_rows($consulta_tablet)) {
    		$tablet = new Tablet();
    		$tablet->setar_atributos_consulta($consulta_tablet);
    		return $tablet;
    	}
    	return new Erro("Não foi encontrado tablet com o ID $id_tablet");
    }
    
    function recupera_tablets($id_bar) {
    	$id_bar = mysql_real_escape_string($id_bar);
    	$consulta = mysql_query("SELECT t.id
    								FROM tablets t
    								WHERE t.bar_id=$id_bar
    								ORDER BY t.nome");
    	$lista_tablets = new Tablet_lista();
    	while (list($id_tablet) = mysql_fetch_array($consulta)) {
    		$lista_tablets->adicionar($this->recupera_tablet($id_tablet));
    	}
    	return $lista_tablets;
    }
    
    function recupera_tablets_disponiveis($id_bar) {
    	$id_bar = mysql_real_escape_string($id_bar);
    	$consulta = mysql_query("SELECT t.id
        								FROM tablets t
        								WHERE t.bar_id=$id_bar
        									AND t.disponivel=TRUE
        								ORDER BY t.nome");
    	$lista_tablets = new Tablet_lista();
    	while (list($id_tablet) = mysql_fetch_array($consulta)) {
    		$lista_tablets->adicionar($this->recupera_tablet($id_tablet));
    	}
    	return $lista_tablets;
    }
    
    function recupera_tablets_ocupados($id_bar) {
    	$id_bar = mysql_real_escape_string($id_bar);
    	$consulta = mysql_query("SELECT t.id
            								FROM tablets t
            								WHERE t.bar_id=$id_bar
            									AND t.disponivel=FALSE
            								ORDER BY t.nome");
    	$lista_tablets = new Tablet_lista();
    	while (list($id_tablet) = mysql_fetch_array($consulta)) {
    		$lista_tablets->adicionar($this->recupera_tablet($id_tablet));
    	}
    	return $lista_tablets;
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
    
    function recupera_conta($id_conta) {
    	$id_conta = mysql_real_escape_string($id_conta);
    	$consulta_conta = mysql_query("SELECT c.id, c.tablet_id, c.estado, UNIX_TIMESTAMP(c.data_hora_abertura) AS data_hora_abertura, UNIX_TIMESTAMP(c.data_hora_fechamento) AS data_hora_fechamento
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
    
    function recupera_total_pedido($id_pedido) {
    	$id_pedido = mysql_real_escape_string($id_pedido);
    	$consulta = mysql_query("SELECT p.quantidade * i.preco
    								FROM pedidos p, itens i
    								WHERE p.item_id=i.id
    									AND p.id=$id_pedido");
    	list($total) = mysql_fetch_array($consulta);
    	return $total;
    }
    
    function recupera_conta_aberta($id_tablet) {
    	$id_tablet = mysql_real_escape_string($id_tablet);
    	$consulta = mysql_query("SELECT c.id
    								FROM contas c
    								WHERE c.tablet_id=$id_tablet
    									AND c.estado='Aberta'");
    	if (mysql_num_rows($consulta) == 1) {
    		list($id_conta) = mysql_fetch_array($consulta);
    		return $this->recupera_conta($id_conta);
    	}
    	return null;
    }
    
    function recupera_contas_fechadas($id_tablet) {
    	$id_tablet = mysql_real_escape_string($id_tablet);
    	$consulta = mysql_query("SELECT c.id
        								FROM contas c
        								WHERE c.tablet_id=$id_tablet
        									AND c.estado='Fechada'
    									ORDER BY c.data_hora_abertura DESC");
    	$contas = array();
    	while (list($id_conta) = mysql_fetch_array($consulta)) {
    		$contas[] = $this->recupera_conta($id_conta);
    	}
    	return $contas;
    }
    
    function recupera_msg($id_msg) {
    	$id_msg = mysql_real_escape_string($id_msg);
    	$consulta_msg = mysql_query("SELECT m.id, m.remetente_id, m.destinatario_id, m.mensagem, UNIX_TIMESTAMP(m.data_hora) AS data_hora, m.entregue, t.nome AS remetente_nome
										FROM mensagens m, tablets t
										WHERE m.id=$id_msg
    										AND m.remetente_id=t.id");
    	if (mysql_num_rows($consulta_msg)) {
    		$msg = new Mensagem();
    		$msg->setar_atributos_consulta($consulta_msg);
    		$msg->set_hora_formatado();
    		return $msg;
    	}
    	return new Erro("Não foi encontrada mensagem com o ID $id_msg");
    }
    
    function recupera_msgs_novas_para_tablet_por_remetente($id_tablet, $id_remetente) {
    	$id_tablet = mysql_real_escape_string($id_tablet);
    	$id_remetente = mysql_real_escape_string($id_remetente);
    	$consulta = mysql_query("SELECT m.id
    								FROM mensagens m
    								WHERE m.destinatario_id=$id_tablet
    									AND m.remetente_id=$id_remetente
    									AND m.entregue=0");
    	$lista_msgs = new Mensagem_lista();
    	while (list($id_msg) = mysql_fetch_array($consulta)) {
    		$msg = $this->recupera_msg($id_msg);
    		$lista_msgs->adicionar($msg);
    		$setar_entregue = mysql_query("UPDATE mensagens
    										SET entregue=1
    										WHERE id=$id_msg");
    		
    	}
    	return $lista_msgs;
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
	
	function salvar_conta($conta) {
		$id = mysql_real_escape_string($conta->get_id());
		$tablet_id = mysql_real_escape_string($conta->get_tablet_id());
		$estado = mysql_real_escape_string($conta->get_estado());
		$data_hora_abertura = mysql_real_escape_string($conta->get_data_hora_abertura_mysql());
		$data_hora_fechamento = mysql_real_escape_string($conta->get_data_hora_fechamento_mysql());
		$consulta = mysql_query("SELECT c.id
			    					FROM contas c
			    					WHERE c.id=$id");
		if (mysql_num_rows($consulta) == 1) {
			$salvar = mysql_query("UPDATE contas
			    					SET	tablet_id=$tablet_id,
			    						estado='$estado',
			    						data_hora_abertura='$data_hora_abertura',
			    						data_hora_fechamento='$data_hora_fechamento'
			    					WHERE id=$id");
		}
		else {
			$salvar = mysql_query("INSERT INTO contas (tablet_id, estado, data_hora_abertura, data_hora_fechamento) VALUES (
									$tablet_id,
									'$estado',
									'$data_hora_abertura',
									'$data_hora_fechamento')");
		}
		if (!$salvar) {
			return mysql_error();
		}
		return "ok";
	}
	
	function salvar_pedido($pedido, $tablet_id = null) {
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
			if ($tablet_id == null) {
				$erro = "Precisa informar tablet_id para essa operação";
				return $erro->get_json();
			}
			$consulta_se_existe_conta_aberta = mysql_query("SELECT c.id
																FROM contas c
																WHERE tablet_id=$tablet_id
																	AND estado='Aberta'");
			if (mysql_num_rows($consulta_se_existe_conta_aberta) == 1) {
				list($conta_id) = mysql_fetch_array($consulta_se_existe_conta_aberta);
			}
			else {
				$conta = new Conta($tablet_id);
				$criar_conta = $this->salvar_conta($conta);
				if ($criar_conta != 'ok') {
					$erro = new Erro(mysql_error());
					return $erro->get_json();
				}
				$conta_id = mysql_insert_id();
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
	
	function salvar_tablet($tablet) {
		$id_tablet = mysql_real_escape_string($tablet->get_id());
		$bar_id = mysql_real_escape_string($tablet->get_bar_id());
		$nome = mysql_real_escape_string($tablet->get_nome());
		$disponivel = mysql_real_escape_string($tablet->get_disponivel());
		$consulta = mysql_query("SELECT t.id
	    								FROM tablets t
	    								WHERE t.id=$id_tablet");
		if (mysql_num_rows($consulta) == 1) {
			$salvar = mysql_query("UPDATE tablets
	    								SET nome='$nome',
	    									bar_id=$bar_id,
	    									disponivel=$disponivel
	    								WHERE id=$id_tablet");
		}
		else {
			$consulta_nome = mysql_query("SELECT *
											FROM tablets t
											WHERE t.nome='$nome'
												AND t.bar_id = $bar_id");
			if (mysql_num_rows($consulta_nome) > 0) {
				return "Já há mesa cadastrada com o nome \"".$nome."\" nesse bar.";
			}
			$salvar = mysql_query("INSERT INTO tablets (bar_id, nome, disponivel) VALUES (
									$bar_id,
									'$nome',
									$disponivel)");
		}
		if (!$salvar) {
			return mysql_error();
		}
		return "ok";
	}
	
	function salvar_msg($msg) {
		$id_msg = mysql_real_escape_string($msg->get_id());
		$remetente_id = mysql_real_escape_string($msg->get_remetente_id());
		$destinatario_id = mysql_real_escape_string($msg->get_destinatario_id());
		$mensagem = mysql_real_escape_string($msg->get_mensagem());
		$data_hora = mysql_real_escape_string($msg->get_data_hora_mysql());
		$entregue = mysql_real_escape_string($msg->get_entregue());
		$consulta = mysql_query("SELECT m.id
		    						FROM mensagens m
		    						WHERE m.id=$id_msg");
		if (mysql_num_rows($consulta) == 1) {
			$salvar = mysql_query("UPDATE mensagens
		    						SET remetente_id=$remetente_id,
		    							destinatario_id=$destinatario_id,
		    							mensagem='$mensagem',
		    							data_hora='$data_hora',
		    							entregue=$entregue
		    						WHERE id=$id_msg");
		}
		else {
			$salvar = mysql_query("INSERT INTO mensagens (remetente_id, destinatario_id, mensagem, data_hora, entregue) VALUES (
									$remetente_id,
									$destinatario_id,
									'$mensagem',
									'$data_hora',
									$entregue)");
		}
		if (!$salvar) {
			$erro = new Erro(mysql_error());
			return $erro->get_json();
		}
		$msg = $this->recupera_msg(mysql_insert_id());
		return $msg->get_json();
	}
	
	function excluir_msg($id_msg) {
		$id_msg = mysql_real_escape_string($id_msg);
		$excluir = mysql_query("DELETE FROM mensagens
					    			WHERE id=$id_msg");
		if (!$excluir) {
			return mysql_error();
		}
		return "ok";
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
	
	function excluir_pedido($id_pedido) {
		$id_pedido = mysql_real_escape_string($id_pedido);
		$excluir = mysql_query("DELETE FROM pedidos
				    				WHERE id=$id_pedido");
		if (!$excluir) {
			return mysql_error();
		}
		return "ok";
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
	
	function excluir_msgs_para_tablet($id_tablet) {
		$id_tablet = mysql_real_escape_string($id_tablet);
		$consulta = mysql_query("SELECT m.id
									FROM mensagens m
									WHERE m.destinatario_id=$id_tablet");
		while (list($id_msg) = mysql_fetch_array($consulta)) {
			$resultado = $this->excluir_msg($id_msg);
			if ($resultado != "ok") {
				return $resultado;
			}
		}
		return "ok";
	}
	
	function excluir_msgs_enviadas_por_tablet($id_tablet) {
		$id_tablet = mysql_real_escape_string($id_tablet);
		$consulta = mysql_query("SELECT m.id
									FROM mensagens m
									WHERE m.remetente_id=$id_tablet");
		while (list($id_msg) = mysql_fetch_array($consulta)) {
			$resultado = $this->excluir_msg($id_msg);
			if ($resultado != "ok") {
				return $resultado;
			}
		}
		return "ok";
	}
	
	function excluir_tablet($tablet) {
		$id_tablet = mysql_real_escape_string($tablet->get_id());
		$excluir = mysql_query("DELETE FROM tablets
									WHERE id=$id_tablet");
		if (!$excluir) {
			return mysql_error();
		}
		return "ok";
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
	
	function consulta_ha_mesas_abertas($id_bar) {
		return $this->consulta_num_mesas_abertas($id_bar) > 0;
	}
	
	function consulta_ha_conta_aberta($id_tablet) {
		$id_tablet = mysql_real_escape_string($id_tablet);
		$consulta = mysql_query("SELECT *
										FROM contas c
										WHERE c.estado='Aberta'
											AND c.tablet_id=$id_tablet");
		return mysql_num_rows($consulta) > 0;
	}
	
	function consulta_ha_pedido_com_item($id_item) {
		$id_item = mysql_real_escape_string($id_item);
		$consulta = mysql_query("SELECT *
									FROM pedidos p
									WHERE p.item_id=$id_item");
		return mysql_num_rows($consulta) > 0;
	}
	
	function consulta_num_msgs_novas_para_tablet($id_tablet) {
		$id_tablet = mysql_real_escape_string($id_tablet);
		$consulta = mysql_query("SELECT m.id
	            						FROM mensagens m
	            						WHERE m.destinatario_id=$id_tablet
											AND m.entregue=0");
		return mysql_num_rows($consulta);
	}
	
	function consulta_num_msgs_novas_para_tablet_por_remetente($id_tablet, $id_remetente) {
		$id_tablet = mysql_real_escape_string($id_tablet);
		$id_remetente = mysql_real_escape_string($id_remetente);
		$consulta = mysql_query("SELECT m.id
		            				FROM mensagens m
		            				WHERE m.destinatario_id=$id_tablet
		            					AND m.remetente_id=$id_remetente
										AND m.entregue=0");
		return mysql_num_rows($consulta);
	}
	
	function consulta_quem_mandou_msgs_novas_para_tablet($id_tablet) {
		$id_tablet = mysql_real_escape_string($id_tablet);
		$consulta = mysql_query("SELECT m.remetente_id
		            				FROM mensagens m
		            				WHERE m.destinatario_id=$id_tablet
										AND m.entregue=0");
		$remetentes = Array();
		while (list($id) = mysql_fetch_array($consulta)) {
			$remetentes[] = $this->recupera_tablet($id);
		}
		return $remetentes;
	}
	
	function consulta_num_contas_no_tablet($id_tablet) {
		$id_tablet = mysql_real_escape_string($id_tablet);
		$consulta = mysql_query("SELECT *
									FROM contas c
									WHERE c.tablet_id=$id_tablet");
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
	
	function consulta_num_categorias_do_bar($id_bar) {
		$id_bar = mysql_real_escape_string($id_bar);
		$consulta = mysql_query("SELECT *
									FROM categorias c 
									WHERE c.bar_id=$id_bar");
		return mysql_num_rows($consulta);
	}
	
	function consulta_num_mesas_abertas($id_bar) {
		$id_bar = mysql_real_escape_string($id_bar);
		$consulta = mysql_query("SELECT *
									FROM contas c, tablets t
									WHERE c.estado='Aberta'
										AND c.tablet_id=t.id
										AND t.bar_id=$id_bar");
		return mysql_num_rows($consulta);
	}
	
	function marcar_pra_apagar_localmente($id_tablet) {
		$id_tablet = mysql_real_escape_string($id_tablet);
		$consulta = mysql_query("SELECT DISTINCT m.remetente_id, m.destinatario_id
									FROM mensagens m 
									WHERE m.remetente_id = $id_tablet");
		while (list($id_remetente, $id_destinatario) = mysql_fetch_array($consulta)) {
			$consulta_se_existe = mysql_query("SELECT *
												FROM precisa_apagar 
												WHERE quem_precisa_apagar = $id_destinatario 
													AND apagar_de_quem = $id_remetente");
			if (mysql_num_rows($consulta_se_existe) == 0) {
				$inserir = mysql_query("INSERT INTO precisa_apagar (quem_precisa_apagar, apagar_de_quem) VALUES (
											$id_destinatario,
											$id_remetente)");
				if (!$inserir) {
					return mysql_error();
				}
			}
		}
		return "ok";
	}
	
	function recupera_precisa_apagar($id_tablet) {
		$id_tablet = mysql_real_escape_string($id_tablet);
		$consulta = mysql_query("SELECT pa.apagar_de_quem 
									FROM precisa_apagar pa 
									WHERE quem_precisa_apagar = $id_tablet");
		$precisa_apagar = Array();
		while (list($id) = mysql_fetch_array($consulta)) {
			$precisa_apagar[] = $id;
		}
		return $precisa_apagar;
	}
	
	function excluir_precisa_apagar($id_tablet) {
		$id_tablet = mysql_real_escape_string($id_tablet);
		$excluir = mysql_query("DELETE FROM precisa_apagar 
									WHERE quem_precisa_apagar = $id_tablet");
		if (!$excluir) {
			return mysql_error();
		}
		return "ok";
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