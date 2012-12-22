<?php
include_once '../../classes/dao.php';
include_once '../../classes/pedido.php';

$banco = new DAO();

$item_id = $_REQUEST["item_id"];
$quantidade = $_REQUEST["quantidade"];
$pessoas = $_REQUEST["pessoas"];
$comentario = $_REQUEST["comentario"];

$pedido = new Pedido($item_id, $quantidade, $pessoas, $comentario);

$conta = $banco->recupera_conta_pela_pessoa($pessoas[0]);
if (get_class($conta) == "Erro") {
	echo $conta->get_json();
	exit;
}

$pedido->set_conta_id($conta->get_id());

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