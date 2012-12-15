<?php
include_once '../../classes/dao.php';

$banco = new DAO();

$conta_id = $_REQUEST["conta_id"];

$lista_resumos = $banco->recupera_resumos_da_conta($conta_id);

echo $lista_resumos->get_json();
?>