<?php
include_once "../classes/sessao.php";

foreach ($_REQUEST as $indice => $valor) {
	Sessao::set($indice, $valor);
}

echo "ok";
?>