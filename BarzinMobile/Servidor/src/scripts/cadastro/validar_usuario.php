<?php
header('Access-Control-Allow-Origin: *');

include_once '../../classes/dao.php';

$banco = new DAO();

$usuario = $_REQUEST["usuario"];

if (strlen($usuario) < 3) {
	echo "O nome de usuário deve ter no mínimo 3 caracteres.";
	exit;
}

if ($banco->consulta_existe_usuario($usuario)) {
	echo "Já existe usuário $usuario";
	exit;
}

echo "ok";

?>