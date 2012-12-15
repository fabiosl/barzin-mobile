<?php
include_once '../../classes/dao.php';
include_once '../../classes/bar.php';
include_once '../../classes/cardapio.php';
include_once '../../classes/erro.php';

$banco = new DAO();

$id_tablet = $_REQUEST["id_tablet"];
$versao_cardapio = $_REQUEST["versao_cardapio"];

$bar = $banco->recupera_bar_pelo_tablet($id_tablet);

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