<?php
header('Access-Control-Allow-Origin: *');

include_once '../../classes/dao.php';
include_once '../../classes/pedido.php';

$banco = new DAO();

$codigo_mesa = $_REQUEST["codigo_mesa"];
$ultima_atualizacao_pessoas = $_REQUEST["ultima_atualizacao_pessoas"];

$mesa = $banco->recupera_mesa_pelo_codigo($codigo_mesa);
if (get_class($mesa) == "Erro") {
	echo $mesa->get_erro();
	exit;
}

$ultima_atualizacao_banco = $banco->recupera_ultima_atualizacao_pessoas($mesa->get_id());

if (intval($ultima_atualizacao_pessoas) < $ultima_atualizacao_banco) {
	$pessoas = $banco->recupera_pessoas_por_mesa($mesa->get_id());
	$retorno = array();
	foreach ($pessoas as $pessoa) {
		$retorno["pessoas"][] = array(
									"id" => $pessoa->get_id(), 
									"nome" => $pessoa->get_nome());
	}
	$retorno["ultima_atualizacao_pessoas"] = $ultima_atualizacao_banco;
	echo json_encode($retorno);
	exit;
}
else {
	echo "0";
}
?>