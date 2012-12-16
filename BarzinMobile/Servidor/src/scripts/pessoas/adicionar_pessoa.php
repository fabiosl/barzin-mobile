<?php
include_once '../../classes/dao.php';
include_once '../../classes/pedido.php';

$banco = new DAO();

$nome_pessoa = $_REQUEST["nome_pessoa"];
$codigo_mesa = $_REQUEST["codigo_mesa"];

$mesa = $banco->recupera_mesa_pelo_codigo($codigo_mesa);
if (get_class($mesa) == "Erro") {
	echo $mesa->get_json();
}

$pessoa = new Pessoa($nome_pessoa, $mesa->get_id());

$resultado = $banco->salvar_pessoa($pessoa);

echo $resultado->get_json();
?>