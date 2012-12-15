<?php
include_once '../classes/dao.php';

$banco = new DAO();

$operacao = $_GET["operacao"];

if ($operacao == "alterar" || $operacao == "excluir") {
	$categoria = $banco->recupera_categoria($_GET["id"]);
}
else if ($operacao == "criar") {
	$categoria = new Categoria();
	if (isset($_GET["categoria_mae_id"])) {
		$categoria_mae = $banco->recupera_categoria($_GET["categoria_mae_id"]);
		$categoria->set_bar_id($categoria_mae->get_bar_id());
		$categoria->set_categoria_mae_id($categoria_mae->get_id());
	}
	else {
		$categoria->set_bar_id($_GET["bar_id"]);
	}
}

if ($operacao == "alterar" || $operacao == "criar") {
	$categoria->set_nome($_GET["nome"]);
	echo $banco->salvar_categoria($categoria);
	exit;
}

if ($operacao == "excluir") {
	echo $banco->excluir_categoria($categoria);
	exit;
}
?>