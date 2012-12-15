<?php
include_once '../../classes/dao.php';

$banco = new DAO();

$id_tablet = $_REQUEST["id_tablet"];

$tablet = $banco->recupera_tablet($id_tablet);
if (get_class($tablet) == "Erro") {
	echo $tablet->get_json();
	exit;
}

if (!$tablet->get_disponivel()) {
	$erro = new Erro("A mesa escolhida não está disponível para associação.");
	echo $erro->get_json();
	exit;
}

$tablet->set_disponivel(0);

$resultado = $banco->salvar_tablet($tablet);

if ($resultado != "ok") {
	$erro = new Erro($resultado);
	echo $erro->get_json();
	exit;
}
else {
	$bar = $banco->recupera_bar($tablet->get_bar_id());
	echo "{\"nome\":\"".$bar->get_nome()."\"}";
	exit;	
}
?>