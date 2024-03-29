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
include_once 'chamado_garcom.php';
include_once 'solicitacao_conta.php';
include_once 'mensagem.php';

class DAO {

    function DAO() {
    	// Online no JPRibeiro.com
		// $host = "localhost";
		// $usuario = "jpribeir_barzin";
		// $senha = "b4rz1n";
		// $db = "jpribeir_barzin";
    	
    	// Local
     	date_default_timezone_set ('America/Recife');
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
    
    function atualiza_codigo_mesa($id_mesa) {
    	$salvar = mysql_query("UPDATE mesas 
    							SET codigo = '".$this->gerar_codigo_mesa()."'
    							WHERE id = $id_mesa");
    	if (!$salvar) {
    		return mysql_error();
    	}
    	return "ok";
    }
    
    function atualiza_pessoas_pedido($pedido) {
    	$id_pedido = mysql_real_escape_string($pedido->get_id());
    	$remover_pessoas = mysql_query("DELETE FROM pedidos_pessoas 
    										WHERE pedido_id = $id_pedido");
    	foreach ($pedido->get_pessoas() as $pessoa) {
    		$inserir = mysql_query("INSERT INTO pedidos_pessoas (pedido_id, pessoa_id) VALUES(
    									$id_pedido, 
    									".$pessoa->get_id().")");
    	}
    }
    
    function atualiza_ultima_atualizacao_pessoas($id_mesa) {
    	$id_mesa = mysql_real_escape_string($id_mesa);
    	$atualizar = mysql_query("UPDATE mesas
    								SET ultima_atualizacao_pessoas = NOW() 
    								WHERE id = $id_mesa");
    	if (!$atualizar) {
    		return mysql_error();
    	}
    	return "ok";
    }
    
    function cadastrar_bar($bar, $admin_senha, $func_senha) {
    	$admin_login = mysql_real_escape_string($bar->get_admin_login());
    	$admin_senha = mysql_real_escape_string($admin_senha);
    	$func_login = mysql_real_escape_string($bar->get_func_login());
    	$func_senha = mysql_real_escape_string($func_senha);
    	
    	$inserir_admin = mysql_query("INSERT INTO usuarios (login, senha) VALUES (
    									'$admin_login', 
    									MD5('$admin_senha'))");
    	if (!$inserir_admin) {
    		$erro = new Erro(mysql_error());
    		return $erro->get_json();
    	}
    	
    	$inserir_func = mysql_query("INSERT INTO usuarios (login, senha) VALUES (
    	    									'$func_login', 
    	    									MD5('$func_senha'))");
    	if (!$inserir_func) {
    		$erro = new Erro(mysql_error());
    		return $erro->get_json();
    	}
    	
    	return $this->salvar_bar($bar);
    }
    
    function consulta_codigo_valido($codigo_mesa) {
    	$codigo_mesa = mysql_real_escape_string($codigo_mesa);
    	$consulta = mysql_query("SELECT *
            						FROM mesas m
            						WHERE m.codigo = '$codigo_mesa'");
    	return mysql_num_rows($consulta) == 1;
    }
    
    function consulta_existe_email($email) {
    	$email = mysql_real_escape_string($email);
    	$consulta = mysql_query("SELECT *
                        				FROM bares b 
                        				WHERE b.email = '$email'");
    	return mysql_num_rows($consulta) > 0;
    }
    
    function consulta_existe_email_em_outro_bar($email, $id_bar) {
    	$email = mysql_real_escape_string($email);
    	$id_bar = mysql_real_escape_string($id_bar);
    	$consulta = mysql_query("SELECT *
                        			FROM bares b 
                        			WHERE b.email = '$email' 
    									AND b.id != $id_bar");
    	return mysql_num_rows($consulta) > 0;
    }
    
    function consulta_existe_usuario($usuario) {
    	$usuario = mysql_real_escape_string($usuario);
    	$consulta = mysql_query("SELECT *
                						FROM usuarios u
                						WHERE u.login = '$usuario'");
    	return mysql_num_rows($consulta) > 0;
    }
    
    function consulta_ha_chamado_garcom_pra_mesa($id_mesa) {
    	$id_mesa = mysql_real_escape_string($id_mesa);
    	$consulta = mysql_query("SELECT *
            						FROM chamados_garcom c
            						WHERE c.mesa_id = $id_mesa");
    	return mysql_num_rows($consulta) > 0;
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

    function consulta_ha_solicitacao_conta_da_mesa($id_mesa) {
        $id_mesa = mysql_real_escape_string($id_mesa);
        $consulta = mysql_query("SELECT *
                                    FROM solicitacoes_conta s
                                    WHERE s.mesa_id = $id_mesa");
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

    function enviar_msg_pra_mesas_abertas($id_bar, $mensagem) {
        $id_bar = mysql_real_escape_string($id_bar);
        $mensagem = mysql_real_escape_string($mensagem);

        $consulta_mesas = mysql_query("SELECT m.id
                                        FROM contas c, mesas m
                                        WHERE c.estado = 'Aberta'
                                            AND c.mesa_id = m.id
                                            AND m.bar_id = $id_bar");
        
        while (list($id_mesa) = mysql_fetch_array($consulta_mesas)) {
            $msg = new Mensagem();
            $msg->set_mesa_id($id_mesa);
            $msg->set_mensagem($mensagem);
            $this->salvar_mensagem($msg);
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
    
    function excluir_chamado_garcom($id_chamado) {
    	$id_chamado = mysql_real_escape_string($id_chamado);
    	$excluir = mysql_query("DELETE FROM chamados_garcom
        	    					WHERE id = $id_chamado");
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
    
    function excluir_mensagem($id_mensagem) {
        $id_mensagem = mysql_real_escape_string($id_mensagem);
        $excluir = mysql_query("DELETE FROM mensagens
                                    WHERE id = $id_mensagem");
        if (!$excluir) {
            return mysql_error();
        }
        return "ok";
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
    	
    	$consulta_se_existe = mysql_query("SELECT p.mesa_id 
    										FROM pessoas p 
    										WHERE p.id = $id_pessoa");
    	if (mysql_num_rows($consulta_se_existe) != 1) {
    		return "Não foi encontrada pessoa com ID $id_pessoa";
    	}
    	list($mesa_id) = mysql_fetch_array($consulta_se_existe);
    	
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

        // A partir daqui, pode excluir
    	
    	$excluir = mysql_query("DELETE FROM pessoas
        				    		WHERE id = $id_pessoa");
    	if (!$excluir) {
    		return mysql_error();
    	}
    	
    	$resposta = $this->atualiza_ultima_atualizacao_pessoas($mesa_id);
    	if ($resposta != "ok") {
    		return $resposta;
    	}
    	
    	return "ok";
    }
    
    function excluir_pessoas_da_mesa($id_mesa) {
    	$id_mesa = mysql_real_escape_string($id_mesa);
    	
    	$excluir = mysql_query("DELETE FROM pessoas 
    								WHERE mesa_id = $id_mesa");
    	if (!$excluir) {
    		return mysql_error();
    	}
    	return "ok";
    }

    function excluir_solicitacao_conta($id_solicitacao_conta) {
        $id_solicitacao_conta = mysql_real_escape_string($id_solicitacao_conta);
        $excluir = mysql_query("DELETE FROM solicitacoes_conta
                                    WHERE id = $id_solicitacao_conta");
        if (!$excluir) {
            return mysql_error();
        }
        return "ok";
    }
    
    function fechar_conta($id_mesa) {
    	$id_mesa = mysql_real_escape_string($id_mesa);
    	
    	$remover_pessoas = mysql_query("DELETE FROM pessoas
    										WHERE mesa_id = $id_mesa");
    	if (!$remover_pessoas) {
    		return mysql_error();
    	}
    	
    	$remover_pedidos_pendentes = mysql_query("DELETE FROM p
    												USING pedidos p, contas c
    												WHERE p.conta_id = c.id 
    													AND c.mesa_id = $id_mesa
    													AND p.estado IN ('Pendente', 'Cancelamento Solicitado')");
    	if (!$remover_pedidos_pendentes) {
    		return mysql_error();
    	}

        $remover_chamados_garcom = mysql_query("DELETE FROM chamados_garcom
                                                    WHERE mesa_id = $id_mesa");
        if (!$remover_chamados_garcom) {
            return mysql_error();
        }

        $remover_solicitacoes_conta = mysql_query("DELETE FROM solicitacoes_conta
                                                    WHERE mesa_id = $id_mesa");
        if (!$remover_solicitacoes_conta) {
            return mysql_error();
        }
    	
        $remover_mensagens = mysql_query("DELETE FROM mensagens
                                            WHERE mesa_id = $id_mesa");
        if (!$remover_mensagens) {
            return mysql_error();
        }

    	$atualizar = mysql_query("UPDATE contas 
    								SET estado = 'Fechada', 
    									data_hora_fechamento = NOW() 
    								WHERE estado = 'Aberta' 
    									AND mesa_id = $id_mesa");
    	if (!$atualizar) {
    		return mysql_error();
    	}
    	
    	return $this->atualiza_codigo_mesa($id_mesa);
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
        	        					WHERE b.id = $id_bar");
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
    
    function recupera_chamado_garcom($id_chamado) {
    	$id_chamado = mysql_real_escape_string($id_chamado);
    	$consulta_chamado = mysql_query("SELECT c.id, c.mesa_id, UNIX_TIMESTAMP(c.data_hora) AS data_hora 
        									FROM chamados_garcom c
        									WHERE c.id = $id_chamado");
    	if (mysql_num_rows($consulta_chamado)) {
    		$chamado = new Chamado_Garcom();
    		$chamado->setar_atributos_consulta($consulta_chamado);
    		return $chamado;
    	}
    	return new Erro("Não foi encontrado chamado ao garçom com o ID $id_chamado");
    }
    
	function recupera_chamados_garcom($id_bar) {
    	$id_bar = mysql_real_escape_string($id_bar);
    	$consulta = mysql_query("SELECT c.id
        							FROM chamados_garcom c, mesas m
        							WHERE c.mesa_id = m.id 
        								AND m.bar_id = $id_bar
        							ORDER BY c.data_hora ASC");
    	$lista_chamados = array();
    	while (list($id_chamado) = mysql_fetch_array($consulta)) {
    		$lista_chamados[] = $this->recupera_chamado_garcom($id_chamado);
    	}
    	return $lista_chamados;
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
        												AND p.estado != 'Pendente'");
    		$total = 0;
    		while (list($id_pedido) = mysql_fetch_array($consulta_pedidos)) {
    			$pedido = $this->recupera_pedido($id_pedido);
    			$conta->adicionar_pedido($pedido);
    			if ($pedido->get_estado() == 'Atendido') {
    				$total += $this->recupera_total_pedido($id_pedido);
    			}
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
    
    function recupera_conta_pela_pessoa($id_pessoa) {
    	$id_pessoa = mysql_real_escape_string($id_pessoa);
    	$consulta = mysql_query("SELECT c.id
            						FROM contas c, pessoas p  
            						WHERE c.mesa_id = p.mesa_id 
            							AND p.id = $id_pessoa 
            							AND c.estado = 'Aberta'");
    	if (mysql_num_rows($consulta) == 1) {
    		list($id_conta) = mysql_fetch_array($consulta);
    		return $this->recupera_conta($id_conta);
    	}
    	return new Erro("Não foi encontrada conta aberta para pessoa com ID $id_pessoa");
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

    function recupera_mensagem($id_mensagem) {
        $id_mensagem = mysql_real_escape_string($id_mensagem);
        $consulta_mensagem = mysql_query("SELECT m.id, m.mesa_id, m.mensagem, UNIX_TIMESTAMP(m.data_hora) AS data_hora 
                                                FROM mensagens m
                                                WHERE m.id = $id_mensagem");
        if (mysql_num_rows($consulta_mensagem)) {
            $mensagem = new Mensagem();
            $mensagem->setar_atributos_consulta($consulta_mensagem);
            return $mensagem;
        }
        return new Erro("Não foi encontrada mensagem com o ID $id_mensagem");
    }

    function recupera_mensagens_pra_mesa_depois_de($id_mesa, $ultima_hora) {
        $id_mesa = mysql_real_escape_string($id_mesa);
        $ultima_hora = mysql_real_escape_string($ultima_hora);
        $consulta_mensagens = mysql_query("SELECT m.id 
                                                FROM mensagens m
                                                WHERE m.mesa_id = $id_mesa
                                                    AND UNIX_TIMESTAMP(m.data_hora) > $ultima_hora
                                                ORDER BY m.data_hora ASC");
        $lista_mensagens = array();
        while (list($id_mensagem) = mysql_fetch_array($consulta_mensagens)) {
            $lista_mensagens[] = $this->recupera_mensagem($id_mensagem);
        }
        return $lista_mensagens;
    }
    
    function recupera_mesa($id_mesa) {
    	$id_mesa = mysql_real_escape_string($id_mesa);
    	$consulta_mesa = mysql_query("SELECT m.id, m.bar_id, m.nome, m.codigo, UNIX_TIMESTAMP(m.ultima_atualizacao_pessoas) AS ultima_atualizacao_pessoas
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
    
    function recupera_mesa_pelo_codigo($codigo_mesa) {
    	$codigo_mesa = mysql_real_escape_string($codigo_mesa);
    	 
    	$consulta_mesa = mysql_query("SELECT m.id
                    						FROM mesas m
                    						WHERE m.codigo = '$codigo_mesa'");
    	 
    	if (mysql_num_rows($consulta_mesa) != 1) {
    		return new Erro("Não foi encontrada mesa com o código $codigo_mesa");
    	}
    	list($id) = mysql_fetch_array($consulta_mesa);
    	return $this->recupera_mesa($id);
    }
    
    function recupera_mesa_pelo_codigo_e_bar($codigo_mesa, $id_bar) {
    	$codigo_mesa = mysql_real_escape_string($codigo_mesa);
    	$id_bar = mysql_real_escape_string($id_bar);
    	
    	$bar = $this->recupera_bar($id_bar);
    	if (get_class($bar) != "Bar") {
    		return new Erro("Não foi encontrado bar com id $id_bar");
    	}
    	
    	$consulta_mesa = mysql_query("SELECT m.id
                						FROM mesas m 
                						WHERE m.codigo = '$codigo_mesa'
                							AND m.bar_id = $id_bar");
                							
    	if (mysql_num_rows($consulta_mesa) != 1) {
    		return new Erro("Não foi encontrada mesa com o código $codigo_mesa, no bar ".$bar->get_nome());
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
    	$consulta_pedido = mysql_query("SELECT p.id, p.item_id, p.conta_id, p.quantidade, p.estado, UNIX_TIMESTAMP(p.data_hora) AS data_hora, p.comentario, UNIX_TIMESTAMP(p.data_hora_solicitacao_cancelamento) AS data_hora_solicitacao_cancelamento
    										FROM pedidos p
    										WHERE p.id=$id_pedido");
    	if (mysql_num_rows($consulta_pedido)) {
    		$pedido = new Pedido();
    		$pedido->setar_atributos_consulta($consulta_pedido);
    		$pedido->set_hora_formatado();
    		$consulta_pessoas = mysql_query("SELECT p.pessoa_id 
    											FROM pedidos_pessoas p 
    											WHERE p.pedido_id = $id_pedido");
    		$pessoas = array();
    		while (list($id_pessoa) = mysql_fetch_array($consulta_pessoas)) {
    			$pessoa = $this->recupera_pessoa($id_pessoa);
    			$pessoas[] = $pessoa;
    		}
    		$pedido->set_pessoas($pessoas);
    		return $pedido;
    	}
    	return new Erro("Não foi encontrado pedido com o ID $id_pedido");
    }
    
    function recupera_pedidos_com_cancelamento_solicitado($id_bar) {
    	$pedidos = array();
    	$id_bar = mysql_real_escape_string($id_bar);
    	$consulta_pedidos_pendentes = mysql_query("SELECT DISTINCT p.id
        												FROM pedidos p, contas c, mesas m
        												WHERE p.estado='Cancelamento Solicitado'
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
    
    function recupera_pedidos_da_mesa($id_mesa) {
    	$pedidos = array();
    	$id_mesa = mysql_real_escape_string($id_mesa);
    	$consulta_pedidos_pendentes = mysql_query("SELECT DISTINCT p.id
            												FROM pedidos p, contas c
            												WHERE p.conta_id = c.id
            													AND c.mesa_id = $id_mesa
            													AND c.estado = 'Aberta'
            												ORDER BY p.data_hora DESC");
    	while (list($id_pedido) = mysql_fetch_array($consulta_pedidos_pendentes)) {
    		$pedido = $this->recupera_pedido($id_pedido);
    		if (get_class($pedido) == "Pedido") {
    			$pedidos[] = $pedido;
    		}
    	}
    	return $pedidos;
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
    
    function recupera_pedidos_por_pessoa($id_pessoa) {
    	$pedidos = array();
    	$id_pessoa = mysql_real_escape_string($id_pessoa);
    	$consulta_pedidos = mysql_query("SELECT DISTINCT pp.pedido_id
            								FROM pedidos_pessoas pp, pedidos p, itens i   
            								WHERE pp.pessoa_id = $id_pessoa
            									AND pp.pedido_id = p.id 
            									AND p.estado = 'Atendido'
            									AND p.item_id = i.id 
            								ORDER BY i.nome ASC");
    	while (list($id_pedido) = mysql_fetch_array($consulta_pedidos)) {
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

    function recupera_solicitacao_conta($id_solicitacao_conta) {
        $id_solicitacao_conta = mysql_real_escape_string($id_solicitacao_conta);
        $consulta_solicitacao = mysql_query("SELECT s.id, s.mesa_id, UNIX_TIMESTAMP(s.data_hora) AS data_hora 
                                                FROM solicitacoes_conta s
                                                WHERE s.id = $id_solicitacao_conta");
        if (mysql_num_rows($consulta_solicitacao)) {
            $solicitacao_conta = new Solicitacao_conta();
            $solicitacao_conta->setar_atributos_consulta($consulta_solicitacao);
            return $solicitacao_conta;
        }
        return new Erro("Não foi encontrada solicitação de conta com o ID $id_solicitacao_conta");
    }
    
    function recupera_solicitacoes_conta($id_bar) {
        $id_bar = mysql_real_escape_string($id_bar);
        $consulta = mysql_query("SELECT s.id
                                    FROM solicitacoes_conta s, mesas m
                                    WHERE s.mesa_id = m.id 
                                        AND m.bar_id = $id_bar
                                    ORDER BY s.data_hora ASC");
        $lista_solicitacoes = array();
        while (list($id_solicitacao) = mysql_fetch_array($consulta)) {
            $lista_solicitacoes[] = $this->recupera_solicitacao_conta($id_solicitacao);
        }
        return $lista_solicitacoes;
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
    
    function recupera_ultima_atualizacao_pessoas($id_mesa) {
    	$id_mesa = mysql_real_escape_string($id_mesa);
    	$consulta = mysql_query("SELECT UNIX_TIMESTAMP(m.ultima_atualizacao_pessoas)
    	    						FROM mesas m  
    	    						WHERE m.id = $id_mesa");
    	list($timestamp) = mysql_fetch_array($consulta);
    	return $timestamp;
    }

    function recupera_ultima_atualizacao_mensagens($id_mesa) {
        $id_mesa = mysql_real_escape_string($id_mesa);
        $consulta = mysql_query("SELECT UNIX_TIMESTAMP(m.data_hora)
                                    FROM mensagens m  
                                    WHERE m.mesa_id = $id_mesa
                                    ORDER BY m.data_hora DESC 
                                    LIMIT 1");
        list($timestamp) = mysql_fetch_array($consulta);
        return $timestamp;
    }
    
    function salvar_bar($bar) {
    	$id_bar = mysql_real_escape_string($bar->get_id());
    	$nome = mysql_real_escape_string($bar->get_nome());
    	$rua = mysql_real_escape_string($bar->get_rua());
    	$numero = mysql_real_escape_string($bar->get_numero());
    	$complemento = mysql_real_escape_string($bar->get_complemento());
    	$bairro = mysql_real_escape_string($bar->get_bairro());
    	$cidade = mysql_real_escape_string($bar->get_cidade());
    	$estado = mysql_real_escape_string($bar->get_estado());
    	$cep = mysql_real_escape_string($bar->get_cep());
    	$telefone1 = mysql_real_escape_string($bar->get_telefone1());
    	$telefone2 = mysql_real_escape_string($bar->get_telefone2());
    	$email = mysql_real_escape_string($bar->get_email());
    	$admin_login = mysql_real_escape_string($bar->get_admin_login());
    	$func_login = mysql_real_escape_string($bar->get_func_login());
    	$consulta = mysql_query("SELECT * 
    								FROM bares b 
    								WHERE b.id = $id_bar");
    	if (mysql_num_rows($consulta) == 1) {
    		$salvar = mysql_query("UPDATE bares 
    								SET nome = '$nome', 
    									rua = '$rua', 
    									numero = '$numero',  
    									complemento = '$complemento', 
    									bairro = '$bairro', 
    									cidade = '$cidade', 
    									estado = '$estado', 
    									cep = '$cep', 
    									telefone1 = '$telefone1', 
    									telefone2 = '$telefone2', 
    									email = '$email', 
    									admin_login = '$admin_login', 
    									func_login = '$func_login'
    								WHERE id = $id_bar");
    	}
    	else {
    		$salvar = mysql_query("INSERT INTO bares (nome, rua, numero, complemento, bairro, cidade, estado, cep, telefone1, telefone2, email, admin_login, func_login) VALUES (
    								'$nome', 
    								'$rua', 
    								'$numero', 
    								'$complemento', 
    								'$bairro', 
    								'$cidade', 
    								'$estado', 
    								'$cep', 
    								'$telefone1',
    								'$telefone2',
    								'$email', 
    								'$admin_login', 
    								'$func_login')");
    		$bar->set_id(mysql_insert_id());
    	}
    	
    	if (!$salvar) {
    		$erro = new Erro(mysql_error());
    		return $erro->get_json();
    	}
    	return $bar->get_json();
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
    
    function salvar_chamado_garcom($chamado_garcom) {
    	$id = mysql_real_escape_string($chamado_garcom->get_id());
    	$mesa_id = mysql_real_escape_string($chamado_garcom->get_mesa_id());
    	$data_hora = mysql_real_escape_string($chamado_garcom->get_data_hora_mysql());
    	$consulta = mysql_query("SELECT c.id
    	    						FROM chamados_garcom c 
    	    						WHERE c.id = $id");
    	if (mysql_num_rows($consulta) == 1) {
    		$salvar = mysql_query("UPDATE chamados_garcom
    	    						SET	mesa_id = $mesa_id,
    	    							data_hora = '$data_hora'
    	    						WHERE id = $id");
    	}
    	else {
    		$salvar = mysql_query("INSERT INTO chamados_garcom (mesa_id, data_hora) VALUES (
    								$mesa_id,
    								'$data_hora')");
    		$chamado_garcom->set_id(mysql_insert_id());
    	}
    
    	if (!$salvar) {
    		$erro = new Erro(mysql_error());
    		return $erro->get_json();
    	}
    
    	return $chamado_garcom->get_json();
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

    function salvar_mensagem($mensagem_obj) {
        $id = mysql_real_escape_string($mensagem_obj->get_id());
        $mesa_id = mysql_real_escape_string($mensagem_obj->get_mesa_id());
        $mensagem = mysql_real_escape_string($mensagem_obj->get_mensagem());
        $data_hora = mysql_real_escape_string($mensagem_obj->get_data_hora_mysql());
        $consulta = mysql_query("SELECT m.id
                                    FROM mensagens m 
                                    WHERE m.id = $id");
        if (mysql_num_rows($consulta) == 1) {
            $salvar = mysql_query("UPDATE mensagens
                                    SET mesa_id = $mesa_id,
                                        mensagem = '$mensagem', 
                                        data_hora = '$data_hora'
                                    WHERE id = $id");
        }
        else {
            $salvar = mysql_query("INSERT INTO mensagens (mesa_id, mensagem, data_hora) VALUES (
                                    $mesa_id, 
                                    '$mensagem', 
                                    '$data_hora')");
            $mensagem_obj->set_id(mysql_insert_id());
        }
    
        if (!$salvar) {
            $erro = new Erro(mysql_error());
            return $erro->get_json();
        }
    
        return $mensagem_obj->get_json();
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
									'".$this->gerar_codigo_mesa()."')");
			}
			$id_mesa = mysql_insert_id();
			if (!$salvar) {
				return mysql_error();
			}
			
			return "ok";
	}
	
	function salvar_pedido($pedido) {
		$id = mysql_real_escape_string($pedido->get_id());
		$item_id = mysql_real_escape_string($pedido->get_item_id());
		$conta_id = mysql_real_escape_string($pedido->get_conta_id());
		$quantidade = mysql_real_escape_string($pedido->get_quantidade());
		$data_hora = mysql_real_escape_string($pedido->get_data_hora_mysql());
		$estado = mysql_real_escape_string($pedido->get_estado());
		$comentario = mysql_real_escape_string($pedido->get_comentario());
		$data_hora_solicitacao_cancelamento = mysql_real_escape_string($pedido->get_data_hora_solicitacao_cancelamento_mysql());
		$consulta = mysql_query("SELECT p.id
	    							FROM pedidos p
	    							WHERE p.id = $id");
		if (mysql_num_rows($consulta) == 1) {
			$salvar = mysql_query("UPDATE pedidos
	    								SET	item_id = $item_id,
	    									conta_id = $conta_id,
	    									quantidade = $quantidade,
	    									data_hora = '$data_hora',
	    									estado = '$estado', 
	    									comentario = '$comentario',
	    									data_hora_solicitacao_cancelamento = '$data_hora_solicitacao_cancelamento'
	    								WHERE id=$id");
		}
		else {
			$salvar = mysql_query("INSERT INTO pedidos (item_id, conta_id, quantidade, estado, data_hora, comentario, data_hora_solicitacao_cancelamento) VALUES (
									$item_id,
									$conta_id,
									$quantidade,
									'$estado',
									'$data_hora', 
									'$comentario', 
									'$data_hora_solicitacao_cancelamento')");
			$pedido->set_id(mysql_insert_id());
		}
		
		if (!$salvar) {
			$erro = new Erro(mysql_error());
			return $erro->get_json();
		}
		
		$this->atualiza_pessoas_pedido($pedido);
		
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
		
		$resposta = $this->atualiza_ultima_atualizacao_pessoas($mesa_id);
		if ($resposta != "ok") {
			return new Erro($resposta);
		}
		
		$pessoa = $this->recupera_pessoa($id_pessoa);
		return $pessoa;
	}

    function salvar_solicitacao_conta($solicitacao_conta) {
        $id = mysql_real_escape_string($solicitacao_conta->get_id());
        $mesa_id = mysql_real_escape_string($solicitacao_conta->get_mesa_id());
        $data_hora = mysql_real_escape_string($solicitacao_conta->get_data_hora_mysql());
        $consulta = mysql_query("SELECT s.id
                                    FROM solicitacoes_conta s 
                                    WHERE s.id = $id");
        if (mysql_num_rows($consulta) == 1) {
            $salvar = mysql_query("UPDATE solicitacoes_conta
                                    SET mesa_id = $mesa_id,
                                        data_hora = '$data_hora'
                                    WHERE id = $id");
        }
        else {
            $salvar = mysql_query("INSERT INTO solicitacoes_conta (mesa_id, data_hora) VALUES (
                                    $mesa_id,
                                    '$data_hora')");
            $solicitacao_conta->set_id(mysql_insert_id());
        }
    
        if (!$salvar) {
            $erro = new Erro(mysql_error());
            return $erro->get_json();
        }
    
        return $solicitacao_conta->get_json();
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