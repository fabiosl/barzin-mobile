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

$cardapio = $banco->recupera_cardapio($bar);

echo "{\"mesa\": ".$mesa->get_json().", \"bar\": ".$bar->get_json().", \"cardapio\": ".$cardapio->get_json()."}";
?>