<?php
header('Access-Control-Allow-Origin: *');

include_once '../../classes/dao.php';
include_once '../../classes/pedido.php';

$banco = new DAO();

$nome_pessoa = $_REQUEST["nome_pessoa"];
$codigo_mesa = $_REQUEST["codigo_mesa"];

$mesa = $banco->recupera_mesa_pelo_codigo($codigo_mesa);
if (get_class($mesa) == "Erro") {
	echo $mesa->get_json();
	exit;
}

$pessoa = new Pessoa($nome_pessoa, $mesa->get_id());

$pessoa_salva = $banco->salvar_pessoa($pessoa);
if (get_class($pessoa_salva) == "Erro") {
	echo $pessoa_salva->get_json();
	exit;
}

$ultima_atualizacao_pessoas = $banco->recupera_ultima_atualizacao_pessoas($mesa->get_id());

echo "{\"pessoa\": ".$pessoa_salva->get_json().", \"ultima_atualizacao_pessoas\": ".$ultima_atualizacao_pessoas."}";
?>