<?php
header('Access-Control-Allow-Origin: *');

include_once '../../classes/dao.php';
include_once '../../classes/bar.php';
include_once '../../classes/cardapio.php';
include_once '../../classes/erro.php';

$banco = new DAO();

$codigo_mesa = $_REQUEST["codigo_mesa"];
$versao_cardapio = $_REQUEST["versao_cardapio"];

$bar = $banco->recupera_bar_pelo_codigo_da_mesa($codigo_mesa);

if (get_class($bar) == 'Erro') {
	echo $bar->get_erro();
	exit;
}

$cardapio = $banco->recupera_cardapio($bar);

if ($cardapio->get_versao() > intval($versao_cardapio)) {
	echo $cardapio->get_json();
	exit;
}

echo 0;

?>