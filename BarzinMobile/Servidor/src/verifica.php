<?php
include_once 'classes/dao.php';

session_name("barzin");
session_start();

if (!isset($_SESSION["usuario_logado"])) {
    session_destroy();
    header("Location: login/formlogin.php");
} else {
    $banco = new DAO(".");
    $tipo_usuario = $banco->get_tipo_usuario($_SESSION["usuario_logado"]); 
    if ($tipo_usuario == "outro") {
        session_destroy();
        header("Location: login/formlogin.php");
    }
}
?>