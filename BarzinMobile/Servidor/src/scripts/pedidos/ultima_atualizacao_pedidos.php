<?php
header('Access-Control-Allow-Origin: *');

include_once '../../classes/dao.php';
include_once '../../classes/pedido.php';

$banco = new DAO();

echo $banco->recupera_ultima_atualizacao_pedidos($_REQUEST["bar_id"]);
?>