<?php
header('Access-Control-Allow-Origin: *');

include_once '../../classes/dao.php';
include_once '../../classes/pedido.php';

$banco = new DAO();

$pedido_id = $_REQUEST["pedido_id"];

$pedido = $banco->recupera_pedido($pedido_id);
if (get_class((Object) $pedido) != "Pedido") {
	echo $pedido->get_json();
	exit;
}

if ($pedido->get_estado() == "Cancelamento Solicitado") {
	$erro = new Erro("Uma solicitação de cancelamento para esse pedido já foi feita.");
	echo $erro->get_json();
	exit;
}
elseif ($pedido->get_estado() != "Pendente") {
	$erro = new Erro("O pedido só pode ser cancelado se estiver Pendente.");
	echo $erro->get_json();
	exit;
}

$pedido->set_estado("Cancelamento Solicitado");
$pedido->set_data_hora_solicitacao_cancelamento(time());

$bar = $banco->recupera_bar_pelo_item($pedido->get_item_id());
if (get_class((Object) $bar) != "Bar") {
	echo $bar->get_json();
	exit;
}
$resposta = $banco->setar_precisa_atualizar_pedidos($bar->get_id());
if ($resposta != "ok") {
	$erro = new Erro($resposta);
	echo $erro->get_json();
	exit;
}

echo $banco->salvar_pedido($pedido);
?>