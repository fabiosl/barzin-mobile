<?php
include_once '../classes/dao.php';

$banco = new DAO();

$operacao = $_REQUEST["operacao"];

// Excluir conta. o Excluir mesa tá lá embaixo
if ($operacao == "excluir") {
	$conta = $banco->recupera_conta_aberta($_REQUEST["id"]);
	$mesa_id = $conta->get_mesa_id();

	$teve_pedido = false;
	foreach ($conta->get_pedidos() as $pedido) {
		if ($pedido->get_estado() == 'Atendido') {
			$teve_pedido = true;
		}
	}
	
	foreach ($banco->recupera_pedidos_pendentes_da_mesa($mesa_id) as $pedido) {
		$excluir = $banco->excluir_pedido($pedido->get_id());
		if ($excluir != "ok") {
			echo $excluir;
			exit;
		}
	}

	if ($teve_pedido) {
		$conta->set_estado('Fechada');
		$conta->set_data_hora_fechamento(time());
		echo $banco->salvar_conta($conta);
		exit;
	}
	else {
		echo $banco->excluir_conta($conta);
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