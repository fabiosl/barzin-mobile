<?php
include_once '../../classes/dao.php';
include_once '../../classes/pedido.php';

$banco = new DAO();

$pessoas = $_REQUEST["pessoas"];

$resumos = array();
$resumos["total"] = 0;
foreach ($pessoas as $pessoa_id) {
	$pedidos = $banco->recupera_pedidos_por_pessoa($pessoa_id);
	foreach ($pedidos as $pedido) {
		$item = $banco->recupera_item($pedido->get_item_id());
		if (get_class($item) == "Item") {
			$quantidade = $pedido->get_quantidade() / count($pedido->get_pessoas());
			$parcela = $quantidade * $item->get_preco();
			if (array_key_exists($item->get_id(), $resumos)) {
				$resumos[$item->get_id()]["quantidade"] += $quantidade;
				$resumos[$item->get_id()]["parcela"] += $parcela;
			}
			else {
				$resumos[$item->get_id()] = array(
												"item" => $item->get_nome(),
												"quantidade" => $quantidade,
												"parcela" => $parcela
												);
			}
			$resumos["total"] += $parcela;
		}
	}
}

echo json_encode($resumos);
?>