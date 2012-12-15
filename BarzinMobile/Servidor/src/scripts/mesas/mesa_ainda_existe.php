<?php
include_once '../../classes/dao.php';

$banco = new DAO();

$id_tablet = $_REQUEST["id_tablet"];

$tablet = $banco->recupera_tablet($id_tablet);

if (get_class($tablet) == "Tablet") {
	echo "1";
}
else {
	echo "0";
}
exit;
?>