<?php
header('Access-Control-Allow-Origin: *');

include_once '../../classes/dao.php';
include_once '../../classes/mensagem.php';

$banco = new DAO();

$codigo_mesa = $_REQUEST["codigo_mesa"];
$ultima_hora_mensagem = $_REQUEST["ultima_hora_mensagem"];

$mesa = $banco->recupera_mesa_pelo_codigo($codigo_mesa);
if (get_class($mesa) != "Mesa") {
	echo $mesa->get_json();
	exit;
}

if (!$banco->consulta_ha_conta_aberta($mesa->get_id())) {
	$erro = new Erro("Não há conta aberta na mesa.");
	echo $erro->get_json();
	exit;
}

$mensagens = $banco->recupera_mensagens_pra_mesa_depois_de($mesa->get_id(), $ultima_hora_mensagem);

$mensagens_json = array();
foreach ($mensagens as $mensagem) {
	$mensagens_json[] = $mensagem->get_mensagem();
}
$mensagens_json = json_encode($mensagens_json);

if (count($mensagens) > 0) {
	$ultima_hora_mensagem = $mensagens[count($mensagens) - 1]->get_data_hora();
}

echo "{\"mensagens\": $mensagens_json, \"ultima_hora_mensagem\": $ultima_hora_mensagem}";

?>