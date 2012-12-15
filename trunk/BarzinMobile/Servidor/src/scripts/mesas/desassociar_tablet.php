<?php
include_once '../../classes/dao.php';

$banco = new DAO();

$id_tablet = $_REQUEST["id_tablet"];
$user = $_REQUEST["user"];
$senha = $_REQUEST["senha"];

if (!$banco->login_valido($user, $senha)) {
	$erro = new Erro("Login inválido");
	echo $erro->get_json();
	exit;
}

$tablet = $banco->recupera_tablet($id_tablet);
if (get_class($tablet) == "Erro") {
	echo $tablet->get_json();
	exit;
}

if ($banco->recupera_bar_pelo_login($user) != $banco->recupera_bar_pelo_tablet($id_tablet)) {
	$erro = new Erro("Login inválido");
	echo $erro->get_json();
	exit;
}

if ($tablet->get_disponivel()) {
	echo "A mesa escolhida não está atualmente associada a nenhum tablet.";
	exit;
}

if ($banco->consulta_ha_conta_aberta($id_tablet)) {
	echo "Ainda há conta aberta na mesa escolhida. O tablet só poderá ser desassociado quando não houver conta aberta nessa mesa.";
	exit;
}

$tablet->set_disponivel(1);

$resultado = $banco->salvar_tablet($tablet);

if ($resultado == "ok") {
	$marcar_pra_apagar = $banco->marcar_pra_apagar_localmente($id_tablet);
	if ($marcar_pra_apagar != "ok") {
		echo $marcar_pra_apagar;
		exit;
	}
	echo $banco->excluir_msgs_para_tablet($id_tablet);
	exit;
}
else {
	echo $resultado;
	exit;
}
?>