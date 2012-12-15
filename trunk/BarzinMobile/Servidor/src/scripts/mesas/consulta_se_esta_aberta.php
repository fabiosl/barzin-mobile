<?php
include_once '../../classes/dao.php';

$banco = new DAO();

$id_tablet = $_REQUEST["id_tablet"];

$resposta = $banco->consulta_ha_conta_aberta($id_tablet);

if ($resposta) {
	echo "1";
}
else {
	echo "0";
}
exit;
?>