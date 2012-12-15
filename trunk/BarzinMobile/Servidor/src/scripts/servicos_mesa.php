<?php
include_once '../classes/dao.php';

$banco = new DAO();

$operacao = $_REQUEST["operacao"];

// Excluir conta. o Excluir tablet tá lá embaixo
if ($operacao == "excluir") {
	$conta = $banco->recupera_conta_aberta($_REQUEST["id"]);
	$tablet_id = $conta->get_tablet_id();

	$teve_pedido = false;
	foreach ($conta->get_pedidos() as $pedido) {
		if ($pedido->get_estado() == 'Atendido') {
			$teve_pedido = true;
		}
	}
	
	foreach ($banco->recupera_pedidos_pendentes_do_tablet($tablet_id) as $pedido) {
		$excluir = $banco->excluir_pedido($pedido->get_id());
		if ($excluir != "ok") {
			echo $excluir;
			exit;
		}
	}

	$marcar_pra_apagar = $banco->marcar_pra_apagar_localmente($tablet_id);
	if ($marcar_pra_apagar != "ok") {
		echo $marcar_pra_apagar;
		exit;
	}
	
	$excluir_msgs = $banco->excluir_msgs_enviadas_por_tablet($tablet_id);
	if ($excluir_msgs != "ok") {
		echo $excluir_msgs;
		exit;
	}
	
	$excluir_msgs = $banco->excluir_msgs_para_tablet($tablet_id);
	if ($excluir_msgs != "ok") {
		echo $excluir_msgs;
		exit;
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
	$tablet = new Tablet();
	$tablet->set_nome($nome);
	$tablet->set_bar_id($bar_id);
	echo $banco->salvar_tablet($tablet);
	exit;
}

elseif ($operacao == "alterar") {
	$id = $_REQUEST["id"];
	$nome = $_REQUEST["nome"];
	$tablet = $banco->recupera_tablet($id);
	if (get_class($tablet) == "Erro") {
		echo $tablet->get_erro();
		exit;
	}
	$tablet->set_nome($nome);
	echo $banco->salvar_tablet($tablet);
	exit;
}

elseif ($operacao == "excluir_tablet") {
	$id = $_REQUEST["id"];
	$tablet = $banco->recupera_tablet($id);
	if (get_class($tablet) == "Erro") {
		echo $tablet->get_erro();
		exit;
	}
	
	echo $banco->excluir_tablet($tablet);
	exit;
}
?>