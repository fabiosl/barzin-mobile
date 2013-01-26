<?php
header('Access-Control-Allow-Origin: *');

include_once '../../classes/dao.php';

$banco = new DAO();

$mensagem = $_REQUEST["mensagem"];
$id_bar = $_REQUEST["id_bar"];

echo $banco->enviar_msg_pra_mesas_abertas($id_bar, "Mensagem do gerente: $mensagem");
?>