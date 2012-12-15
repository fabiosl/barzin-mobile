<?php
require 'verifica.php';
include_once 'classes/dao.php';

$banco = new DAO();

$tipo_usuario = $_SESSION["tipo_usuario"];

if (isset($_REQUEST["msg"])) {
	$msg = "?msg=".$_REQUEST["msg"];
}
else {
	$msg = "";
}

if ($tipo_usuario == "admin") {
    header("Location: indexGerente.php$msg");
} else if ($tipo_usuario == "funcionario") {
    header("Location: indexFuncionario.php$msg");
} else {
    echo "EPIC FAIL!";
}

?>