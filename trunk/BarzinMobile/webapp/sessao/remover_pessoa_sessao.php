<?php
include_once "../classes/sessao.php";

$pessoas = Sessao::get("pessoas");

if (!is_array($pessoas)) {
	$pessoas = array();
}

foreach ($pessoas as $indice => $pessoa) {
	if ($pessoa["id"] == $_REQUEST["id_pessoa"]) {
		unset($pessoas[$indice]);
	}
}

Sessao::set("pessoas", $pessoas);

echo "ok";
?>