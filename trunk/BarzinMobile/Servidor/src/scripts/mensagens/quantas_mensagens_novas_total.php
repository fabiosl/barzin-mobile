<?php
include_once '../../classes/dao.php';
include_once '../../classes/mensagem.php';

$banco = new DAO();

$id_tablet = $_REQUEST["id_tablet"];

$numero_novas = $banco->consulta_num_msgs_novas_para_tablet($id_tablet);
$remetentes = $banco->consulta_quem_mandou_msgs_novas_para_tablet($id_tablet);

$ids_remetentes = Array();
foreach($remetentes as $remetente) {
	$ids_remetentes[] = $remetente->get_id();
}

$resposta = Array("quantidade" => $numero_novas, "remetentes" => $ids_remetentes);

echo json_encode($resposta);
?>