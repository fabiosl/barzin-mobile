<?php
include_once '../../classes/dao.php';
include_once '../../classes/mensagem.php';
include_once '../../classes/mensagem_lista.php';

$banco = new DAO();

$id_tablet = $_REQUEST["id_tablet"];
$id_remetente = $_REQUEST["id_remetente"];

$lista_msgs = $banco->recupera_msgs_novas_para_tablet_por_remetente($id_tablet, $id_remetente);

echo $lista_msgs->get_json();
?>