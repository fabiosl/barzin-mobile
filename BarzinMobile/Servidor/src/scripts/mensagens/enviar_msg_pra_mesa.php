<?php
header('Access-Control-Allow-Origin: *');

include_once '../../classes/dao.php';

$banco = new DAO();

$mensagem = $_REQUEST["mensagem"];
$id_mesa = $_REQUEST["id_mesa"];

$msg_objeto = new Mensagem();
$msg_objeto->set_mesa_id($id_mesa);
$msg_objeto->set_mensagem("Mensagem do gerente: $mensagem");

echo $banco->salvar_mensagem($msg_objeto);
?>