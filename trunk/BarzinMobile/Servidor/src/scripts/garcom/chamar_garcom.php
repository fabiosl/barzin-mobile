<?php
header('Access-Control-Allow-Origin: *');

include_once '../../classes/dao.php';
include_once '../../classes/chamado_garcom.php';

$banco = new DAO();

$codigo_mesa = $_REQUEST["codigo_mesa"];

$mesa = $banco->recupera_mesa_pelo_codigo($codigo_mesa);
if (get_class($mesa) != "Mesa") {
	echo $mesa->get_json();
	exit;
}

if (!$banco->consulta_ha_conta_aberta($mesa->get_id())) {
	$erro = new Erro("Não há conta aberta na mesa.");
	echo $erro->get_json();
	exit;
}

if ($banco->consulta_ha_chamado_garcom_pra_mesa($mesa->get_id())) {
	$erro = new Erro("Um garçom já foi chamado para essa mesa.");
	echo $erro->get_json();
	exit;
}

$chamado = new Chamado_Garcom($mesa->get_id());

$bar = $banco->recupera_bar_pela_mesa($mesa->get_id());
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

echo $banco->salvar_chamado_garcom($chamado);

?>