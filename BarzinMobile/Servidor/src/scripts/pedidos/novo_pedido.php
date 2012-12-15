<?php
include_once '../../classes/dao.php';
include_once '../../classes/pedido.php';

$banco = new DAO();

$item_id = $_REQUEST["item_id"];
$quantidade = $_REQUEST["quantidade"];
$tablet_id = $_REQUEST["tablet_id"];

$pedido = new Pedido($item_id, $quantidade);

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

echo $banco->salvar_pedido($pedido, $tablet_id);
?>