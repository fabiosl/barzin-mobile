<?php
require '../verifica.php';
include_once '../classes/design.php';
include_once '../classes/dao.php';

$banco = new DAO();

$design = new Design("..");
$design->imprimir_topo();

$login_usuario = $_SESSION["usuario_logado"];
$bar = $banco->recupera_bar_pelo_login($login_usuario);
?>

<script type="text/javascript" src="../javascripts/funcoes.js" charset="utf-8"></script>
<script type="text/javascript" src="../javascripts/mesas.js" charset="utf-8"></script>

<a href="index.php" class="titulo_secao">Controle de Mesas</a><br/><br/><br/>

<?php
if (isset($_REQUEST["erro"])) {
	echo "
	 <div class=\"erro\">
		".$_REQUEST["erro"]."
	 </div>
  ";
}
if (isset($_REQUEST["msg"])) {
	echo "
	 <div class=\"msg\">
		".$_REQUEST["msg"]."
	 </div>
  ";
}

echo "
 <a href=\"javascript: void(0);\" id=\"link_nova_mesa\" onclick=\"prepararCriarMesa();\"><img src=\"".$design->get_endereco_imagem("mais.gif")."\" /> Nova mesa</a>
 <div id=\"nova_mesa\" style=\"display: none;\">
	<table class=\"semBorda\">
		<form id=\"form_nova_mesa\" action=\"\" onsubmit=\"return criarMesa();\">
		<input type=\"hidden\" name=\"bar_id\" value=\"".$bar->get_id()."\" />
		<tr>
 			<td nowrap align=\"right\">Nova mesa*:</td>
			<td><input type=\"text\" size=\"20\" name=\"nome\" class=\"Nome da mesa|Obrig\" /></td>
		</tr>
		<tr>
			<td colspan=\"2\" align=\"center\"><button type=\"submit\">OK</button> <button type=\"button\" onclick=\"cancelarCriarMesa()\">Cancelar</button>
		</tr>
		</form>
	</table>
	<br/>
 </div>
 <div id=\"salvando_nova_mesa\" style=\"display: none;\">Salvando nova mesa...</div>
 <br/><br/>
 <h2>Mesas associadas a algum tablet</h2>
";

$mesas_ocupadas = $banco->recupera_tablets_ocupados($bar->get_id());

foreach ($mesas_ocupadas->get_lista() as $mesa) {
	echo "<a href=\"mesa.php?id=".$mesa->get_id()."\">".$mesa->get_nome()."</a> (";
	if ($banco->consulta_ha_conta_aberta($mesa->get_id())) {
		echo "Conta aberta";
	}
	else {
		echo "Não há conta aberta";
	}
	echo ")<br/>";
}

echo "
 <br/><br/>
 <h2>Mesas não associadas a nenhum tablet</h2>
 Essas mesas estão disponíveis para associação a algum tablet (configuração feita diretamente no mesmo).
 <br/><br/>
";


$mesas_disponiveis = $banco->recupera_tablets_disponiveis($bar->get_id());

foreach ($mesas_disponiveis->get_lista() as $mesa) {
	echo "<a href=\"mesa.php?id=".$mesa->get_id()."\">".$mesa->get_nome()."</a> (";
	if ($banco->consulta_ha_conta_aberta($mesa->get_id())) {
		echo "Conta aberta";
	}
	else {
		echo "Não há conta aberta";
	}
	
	echo ")<br/>";
}
?>



<?php
$design->imprimir_fim();
?>