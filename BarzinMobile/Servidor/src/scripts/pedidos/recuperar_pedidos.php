<?php
header('Access-Control-Allow-Origin: *');

include_once '../../classes/dao.php';

$banco = new DAO();

$codigo_mesa = $_REQUEST["codigo_mesa"];
$ultima_atualizacao_pedidos = $_REQUEST["ultima_atualizacao_pedidos"];

$mesa = $banco->recupera_mesa_pelo_codigo($codigo_mesa);
if (get_class($mesa) == "Erro") {
	echo $mesa->get_json();
	exit;
}

$ultima_atualizacao_banco = $banco->recupera_ultima_atualizacao_pedidos($mesa->get_bar_id());

if (intval($ultima_atualizacao_pedidos) < $ultima_atualizacao_banco) {
	$retorno = array();
	$retorno["pedidos"] = array();
	foreach ($banco->recupera_pedidos_da_mesa($mesa->get_id()) as $pedido) {
		$item = $banco->recupera_item($pedido->get_item_id());
		$pessoas = array();
		foreach ($pedido->get_pessoas() as $pessoa) {
			$pessoas[] = array(
							"id" => $pessoa->get_id(), 
							"nome" => $pessoa->get_nome());
		}

		$cancelamento_solicitado = $pedido->get_data_hora_solicitacao_cancelamento() != "0";

		$retorno["pedidos"][] = array(
									"id" => $pedido->get_id(), 
									"item" => $item->get_nome(), 
									"preco_item" => $item->get_preco(),
									"id_item" => $item->get_id(),
									"estado" => $pedido->get_estado(), 
									"quantidade" => $pedido->get_quantidade(), 
									"pessoas" => $pessoas, 
									"hora" => $pedido->get_hora(), 
									"cancelamento_solicitado" => $cancelamento_solicitado);
	}
	$retorno["ultima_atualizacao_pedidos"] = $ultima_atualizacao_banco;
	echo json_encode($retorno);
	exit;
}
else {
	echo "0";
}
?>