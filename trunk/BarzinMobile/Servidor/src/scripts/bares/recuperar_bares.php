<?php
header('Access-Control-Allow-Origin: *');

include_once '../../classes/dao.php';

$banco = new DAO();

$bares = $banco->recupera_bares_todos();

$ids_e_nomes = array();
foreach ($bares as $bar) {
	$ids_e_nomes[] = array("id" => $bar->get_id(), 
							"nome" => $bar->get_nome());
}

echo json_encode($ids_e_nomes);
?>