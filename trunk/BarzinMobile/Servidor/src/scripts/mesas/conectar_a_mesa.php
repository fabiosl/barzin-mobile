<?php
header('Access-Control-Allow-Origin: *');

include_once '../../classes/dao.php';

$banco = new DAO();

$codigo_mesa = $_REQUEST["codigo_mesa"];
$id_bar = $_REQUEST["id_bar"];

$mesa = $banco->recupera_mesa_pelo_codigo_e_bar($codigo_mesa, $id_bar);
if (get_class($mesa) == "Erro") {
	echo $mesa->get_json();
	exit;
}

$bar = $banco->recupera_bar($id_bar);
if (get_class($bar) == "Erro") {
	echo $bar->get_json();
	exit;
}

$conta = $banco->recupera_conta_aberta($mesa->get_id());
$conta_json = "{}";
if ($conta != null && get_class($conta) == "Conta") {
	$conta_json = $conta->get_json();
}

$cardapio = $banco->recupera_cardapio($bar);

echo "{\"mesa\": ".$mesa->get_json().", \"bar\": ".$bar->get_json().", \"conta\": ".$conta_json.", \"cardapio\": ".$cardapio->get_json()."}";
?>