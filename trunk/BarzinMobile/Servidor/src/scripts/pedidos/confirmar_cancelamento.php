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

if ($pedido->get_estado() != "Cancelamento Solicitado") {
	$erro = new Erro("Não foi solicitado cancelamento para esse pedido.");
	echo $erro->get_json();
	exit;
}

$pedido->set_estado("Cancelado");

$bar = $banco->recupera_bar_pelo_item($pedido->get_item_id());
if (get_class((Object) $bar) != "Bar") {
	echo $bar->get_json();
	exit;
}

$conta = $banco->recupera_conta($pedido->get_conta_id());
if (get_class((Object) $conta) != "Conta") {
	echo $conta->get_json();
	exit;
}

$mesa = $banco->recupera_mesa($conta->get_mesa_id());
if (get_class((Object) $mesa) != "Mesa") {
	echo $mesa->get_json();
	exit;
}

$item = $banco->recupera_item($pedido->get_item_id());
if (get_class((Object) $item) != "Item") {
	echo $item->get_json();
	exit;
}

$texto_pessoas = "";
foreach ($pedido->get_pessoas() as $indice => $pessoa) {
 	$texto_pessoas .= trim($pessoa->get_nome());
 	if ($indice < count($pedido->get_pessoas()) - 2) {
 		$texto_pessoas .= ", ";
 	}
 	elseif ($indice == count($pedido->get_pessoas()) - 2) {
 		$texto_pessoas .= " e ";
 	} 
}

$msg = "O cancelamento do pedido de ".$pedido->get_quantidade()." unid. de ".$item->get_nome().", para $texto_pessoas, foi aceito.";

$mensagem = new Mensagem();
$mensagem->set_mesa_id($mesa->get_id());
$mensagem->set_mensagem($msg);

$banco->salvar_mensagem($mensagem);

$resposta = $banco->setar_precisa_atualizar_pedidos($bar->get_id());
if ($resposta != "ok") {
	$erro = new Erro($resposta);
	echo $erro->get_json();
	exit;
}

echo $banco->salvar_pedido($pedido);
?>