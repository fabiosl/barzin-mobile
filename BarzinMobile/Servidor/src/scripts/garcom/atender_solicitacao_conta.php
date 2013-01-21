<?php
header('Access-Control-Allow-Origin: *');

include_once '../../classes/dao.php';
include_once '../../classes/solicitacao_conta.php';

$banco = new DAO();

$solicitacao_conta_id = $_REQUEST["solicitacao_conta_id"];

$solicitacao = $banco->recupera_solicitacao_conta($solicitacao_conta_id);
if (get_class((Object) $solicitacao) != "Solicitacao_Conta") {
	echo $solicitacao->get_json();
	exit;
}

$bar = $banco->recupera_bar_pela_mesa($solicitacao->get_mesa_id());
if (get_class((Object) $bar) != "Bar") {
	echo $bar->get_json();
	exit;
}
$resposta = $banco->setar_precisa_atualizar_pedidos($solicitacao->get_mesa_id());
if ($resposta != "ok") {
	$erro = new Erro($resposta);
	echo $erro->get_json();
	exit;
}

echo $banco->excluir_solicitacao_conta($solicitacao_conta_id);
?>