<?php
include_once '../../classes/dao.php';

$banco = new DAO();

$pedido_id = $_REQUEST["pedido_id"];
$pedido = $banco->recupera_pedido($pedido_id);
if (gettype($pedido) == "object" && get_class($pedido) == "Erro") {
	echo $pedido->get_json();
	exit;
}

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

echo $banco->excluir_pedido($pedido_id);
?>