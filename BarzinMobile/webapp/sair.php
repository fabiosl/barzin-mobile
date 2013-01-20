<?php
include_once "classes/sessao.php";

Sessao::destruir();

header('Location: conectar.php');
exit;
?>