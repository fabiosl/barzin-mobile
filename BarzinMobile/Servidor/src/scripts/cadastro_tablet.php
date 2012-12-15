<?php
include_once '../classes/dao.php';
include_once '../classes/tablet.php';
include_once '../classes/bar.php';
include_once '../classes/erro.php';

$banco = new DAO();

$login = $_REQUEST["usuario"];
$senha = $_REQUEST["senha"];

if ($banco->login_valido($login, $senha)) {
	if ($banco->get_tipo_usuario($login) != "admin") {
		echo "ERRO: Usuário deve ser o admin!";
		exit;
	}
}
else {
	echo "ERRO: Login/Senha inválidos!";
	exit;
}

$bar = $banco->recupera_bar_pelo_login($login);

$nome_tablet = $_REQUEST["nome_tablet"];

if (isset($_REQUEST["id_tablet"])) {
	$id_tablet = $_REQUEST["id_tablet"];
	$tablet = $banco->recupera_tablet($id_tablet);
}
else {
	$tablet = new Tablet();
}

$tablet->set_nome($nome_tablet);
$tablet->set_bar_id($bar->get_id());

$resposta = $banco->salvar_tablet($tablet);

if (is_object($resposta) && get_class($resposta) == "Erro") {
	echo $resposta->get_erro();
	exit;
}

echo "{\"id_tablet\": \"".$resposta."\",
\"nome_bar\": \"".$bar->get_nome()."\"}";

?>