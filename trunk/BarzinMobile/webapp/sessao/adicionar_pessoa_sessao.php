<?php
include_once "../classes/sessao.php";

$pessoas = Sessao::get("pessoas");

if (!is_array($pessoas)) {
	$pessoas = array();
}

$pessoas[] = array(
					"id" => $_REQUEST["id_pessoa"], 
					"nome" => $_REQUEST["nome_pessoa"]
					);

Sessao::set("pessoas", $pessoas);

echo "ok";
?>