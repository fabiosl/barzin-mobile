<?php
include_once '../../classes/dao.php';
include_once '../../classes/mensagem.php';

$banco = new DAO();

$id_tablet = $_REQUEST["id_tablet"];
$id_remetente = $_REQUEST["id_remetente"];

echo $banco->consulta_num_msgs_novas_para_tablet_por_remetente($id_tablet, $id_remetente);
?>