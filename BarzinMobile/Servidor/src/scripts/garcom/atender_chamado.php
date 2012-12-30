<?php
include_once '../../classes/dao.php';
include_once '../../classes/chamado_garcom.php';

$banco = new DAO();

$chamado_id = $_REQUEST["chamado_id"];

$chamado = $banco->recupera_chamado_garcom($chamado_id);
if (get_class((Object) $chamado) != "Chamado_Garcom") {
	echo $chamado->get_json();
	exit;
}

$bar = $banco->recupera_bar_pela_mesa($chamado->get_mesa_id());
if (get_class((Object) $bar) != "Bar") {
	echo $bar->get_json();
	exit;
}
$resposta = $banco->setar_precisa_atualizar_pedidos($chamado->get_mesa_id());
if ($resposta != "ok") {
	$erro = new Erro($resposta);
	echo $erro->get_json();
	exit;
}

echo $banco->excluir_chamado_garcom($chamado_id);
?>