<?php
header('Access-Control-Allow-Origin: *');

include_once '../../classes/dao.php';
include_once '../../classes/item.php';
include_once '../../classes/SimpleImage.php';

$banco = new DAO();

$id_item = $_REQUEST["id_item"];

$item = $banco->recupera_item($id_item, "../..");

if (get_class($item) == 'Erro') {
	echo $item->get_erro();
	exit;
}

$imagem = new SimpleImage();
$imagem->load($item->get_endereco_thumb());
$imagem->output();
exit;

?>