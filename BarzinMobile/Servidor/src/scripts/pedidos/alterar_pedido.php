<?php
include_once '../../classes/dao.php';
include_once '../../classes/pedido.php';

$banco = new DAO();

$pedido_id = $_REQUEST["pedido_id"];
$quantidade = $_REQUEST["quantidade"];
$estado = $_REQUEST["estado"];

$pedido = $banco->recupera_pedido($pedido_id);
if (get_class((Object) $pedido) != "Pedido") {
	echo $pedido->get_json();
	exit;
}

$pedido->set_quantidade($quantidade);
$pedido->set_estado($estado);
$pedido->set_data_hora(time());

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