<?php
include_once '../../classes/dao.php';

$banco = new DAO();

$codigo_mesa = $_REQUEST["codigo_mesa"];

$resultado = $banco->recupera_mesa_pelo_codigo($codigo_mesa);

echo $resultado->get_json();
?>