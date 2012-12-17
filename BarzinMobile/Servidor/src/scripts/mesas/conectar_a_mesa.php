<?php
include_once '../../classes/dao.php';

$banco = new DAO();

$codigo_mesa = $_REQUEST["codigo_mesa"];
$id_bar = $_REQUEST["id_bar"];

$resultado = $banco->recupera_mesa_pelo_codigo($codigo_mesa, $id_bar);

echo $resultado->get_json();
?>