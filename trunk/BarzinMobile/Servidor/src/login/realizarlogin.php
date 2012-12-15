<?php
include_once '../classes/dao.php';

foreach ($_POST as $indice => $valor) {
    $$indice = $valor;
}

$banco = new DAO();

session_name("barzin");
session_start();

if ($banco->login_valido($login, $senha)) {
    $_SESSION["usuario_logado"] = $login;
    $_SESSION["tipo_usuario"] = $banco->get_tipo_usuario($login);
    header("Location: ../index.php");
} else {
	session_destroy();
    header("Location: formlogin.php?erro=Login ou senha inválidos");
    exit;
}

?>