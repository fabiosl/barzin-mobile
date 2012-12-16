<?php
include_once '../../classes/dao.php';
include_once '../../classes/pedido.php';

$banco = new DAO();

$id_pessoa = $_REQUEST["id_pessoa"];
$nome_pessoa = $_REQUEST["nome_pessoa"];

$pessoa = $banco->recupera_pessoa($id_pessoa);
if (get_class($pessoa) == "Erro") {
	echo $pessoa->get_json();
}
else {
	$pessoa->set_nome($nome_pessoa);
	$resultado = $banco->salvar_pessoa($pessoa);
	echo $resultado->get_json();
}
?>