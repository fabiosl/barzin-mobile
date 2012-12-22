<?php
include_once '../classes/dao.php';

$banco = new DAO();

$operacao = $_REQUEST["operacao"];

// Fechar conta
if ($operacao == "fechar") {
	$conta = $banco->recupera_conta_aberta($_REQUEST["id"]);
	$mesa_id = $conta->get_mesa_id();

	$teve_pedido = false;
	foreach ($conta->get_pedidos() as $pedido) {
		if ($pedido->get_estado() == 'Atendido') {
			$teve_pedido = true;
			break;
		}
	}
	
	if ($teve_pedido) {
		echo $banco->fechar_conta($mesa_id);
		exit;
	}
	else {
		$resposta = $banco->excluir_conta($conta);
		if ($resposta != "ok") {
			echo $resposta;
			exit;
		}
		
		$banco->atualiza_codigo_mesa($mesa_id);
		
		$resposta =  $banco->excluir_pessoas_da_mesa($mesa_id);
		if ($resposta != "ok") {
			echo $resposta;
			exit;
		}
		
		$bar = $banco->recupera_bar_pela_mesa($mesa_id);
		echo $banco->setar_precisa_atualizar_pedidos($bar->get_id());
		exit;
	}
}

elseif ($operacao == "criar") {
	$bar_id = $_REQUEST["bar_id"];
	$nome = $_REQUEST["nome"];
	$mesa = new Mesa();
	$mesa->set_nome($nome);
	$mesa->set_bar_id($bar_id);
	echo $banco->salvar_mesa($mesa);
	exit;
}

elseif ($operacao == "alterar") {
	$id = $_REQUEST["id"];
	$nome = $_REQUEST["nome"];
	$mesa = $banco->recupera_mesa($id);
	if (get_class($mesa) == "Erro") {
		echo $mesa->get_erro();
		exit;
	}
	$mesa->set_nome($nome);
	echo $banco->salvar_mesa($mesa);
	exit;
}

elseif ($operacao == "excluir_mesa") {
	$id = $_REQUEST["id"];
	$mesa = $banco->recupera_mesa($id);
	if (get_class($mesa) == "Erro") {
		echo $mesa->get_erro();
		exit;
	}
	
	echo $banco->excluir_mesa($mesa);
	exit;
}
?>