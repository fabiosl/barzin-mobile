<?php
include_once '../../classes/dao.php';
include_once '../../classes/mensagem.php';

$banco = new DAO();

$id_tablet = $_REQUEST["id_tablet"];

$bar = $banco->recupera_bar_pelo_tablet($id_tablet);

$outras_mesas = $banco->recupera_tablets_ocupados($bar->get_id());

echo "{\"lista\":[";
$separador = "";
foreach ($outras_mesas->get_lista() as $mesa) {
	$id_remetente = $mesa->get_id();
	if ($id_remetente != $id_tablet) {
		$quantidade = $banco->consulta_num_msgs_novas_para_tablet_por_remetente($id_tablet, $id_remetente);
		echo $separador."{\"id_remetente\":\"".$id_remetente."\",\"nome_remetente\":\"".$mesa->get_nome()."\",\"quantidade\":\"".$quantidade."\"}";
		$separador = ",";
	}
}
echo "]}";
?>