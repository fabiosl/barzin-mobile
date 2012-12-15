<?php
include_once '../../classes/dao.php';
include_once '../../classes/mensagem.php';

$banco = new DAO();

$id_tablet = $_REQUEST["id_tablet"];

$precisa_apagar = $banco->recupera_precisa_apagar($id_tablet);
$excluir = $banco->excluir_precisa_apagar($id_tablet);
if ($excluir != "ok") {
	$erro = new Erro("Não conseguiu apagar as informações de precisa_apagar no banco remoto");
	echo $erro->get_json();
	exit;
}

$resposta = Array("ids_de_quem_precisa_apagar" => $precisa_apagar);
echo json_encode($resposta);
?>