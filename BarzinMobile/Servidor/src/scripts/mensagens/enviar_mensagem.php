<?php
include_once '../../classes/dao.php';
include_once '../../classes/mensagem.php';

$banco = new DAO();

$id_remetente = $_REQUEST["id_remetente"];
$id_destinatario = $_REQUEST["id_destinatario"];
$mensagem = $_REQUEST["mensagem"];

$mensagem = new Mensagem($id_remetente, $id_destinatario, $mensagem);

echo $banco->salvar_msg($mensagem);
?>