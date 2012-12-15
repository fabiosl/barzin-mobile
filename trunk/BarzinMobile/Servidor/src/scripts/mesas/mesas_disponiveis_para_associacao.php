<?php
include_once '../../classes/dao.php';

$banco = new DAO();

$user = $_REQUEST["user"];
$senha = $_REQUEST["senha"];

if (!$banco->login_valido($user, $senha)) {
	$erro = new Erro("Login inválido");
	echo $erro->get_json();
	exit;
}
elseif ($banco->get_tipo_usuario($user) != "admin") {
	$erro = new Erro("O usuário precisa ser do tipo admin para essa operação");
	echo $erro->get_json();
	exit;
}

$bar = $banco->recupera_bar_pelo_login($user);

$mesas_disponiveis = $banco->recupera_tablets_disponiveis($bar->get_id());
echo $mesas_disponiveis->get_json();
exit;
?>