<?php
include_once '../../classes/dao.php';
include_once '../../classes/pedido.php';

$banco = new DAO();

$id_pessoa = $_REQUEST["id_pessoa"];

echo $banco->excluir_pessoa($id_pessoa);
?>