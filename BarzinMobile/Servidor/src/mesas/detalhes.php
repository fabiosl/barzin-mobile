<?php
require '../verifica.php';
include_once '../classes/design.php';
include_once '../classes/dao.php';

$banco = new DAO();

$design = new Design("..");
$design->imprimir_topo();

$mesa = $banco->recupera_mesa($_REQUEST["id"]);

$pode_alterar = true;
if ($banco->consulta_ha_conta_aberta($mesa->get_id())) {
	echo "
	 <div class=\"warning\">
		Há conta aberta nessa mesa no momento. Você só pode fazer alterações na mesa quando a conta for fechada.
	 </div>
	";
	$pode_alterar = false;
}
?>



<script type="text/javascript" src="../javascripts/funcoes.js" charset="utf-8"></script>
<script type="text/javascript" src="../javascripts/mesas.js" charset="utf-8"></script>

<a href="index.php" class="titulo_secao">Controle de Mesas</a><br/><br/><br/>

<?php
if (isset($_REQUEST["erro"])) {
	$erro = $_REQUEST["erro"];
}
elseif (get_class($mesa) == "Erro") {
	$erro = $mesa->get_erro();
}
if (isset($erro)) {
	echo "
	 <div class=\"erro\">
		$erro
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
 <div class=\"titulo1\" id=\"nome_mesa\">".$mesa->get_nome()."</div>
";
if ($pode_alterar) {
	echo "
	 <div id=\"links_mesa\" style=\"display: inline-block;\">
 	 <a href=\"javascript: void(0);\" onclick=\"prepararEditarMesa()\">".$design->get_imagem('lapis.png', 'Editar')."</a> 
 	 <a href=\"javascript: void(0);\" onclick=\"prepararExcluirMesa(".$mesa->get_id().", '".$mesa->get_nome()."', ".$banco->consulta_num_contas_na_mesa($mesa->get_id()).")\">".$design->get_imagem('excluir.png', 'Excluir')."</a>
	 </div><br/>
	 <div id=\"carregando_mesa\" style=\"display: none;\">
		Carregando...
	 </div>
	 <form id=\"form_mesa\" action=\"\" onsubmit=\"return editarMesa();\" style=\"display: none;\">
		Alterar mesa*: <input type=\"text\" size=\"20\" name=\"nome\" class=\"Nome da mesa|Obrig\" value=\"".$mesa->get_nome()."\"/>
		<input type=\"hidden\" name=\"id\" value=\"".$mesa->get_id()."\"/>
		<button type=\"submit\">OK</button>
		<button type=\"button\" onclick=\"cancelarEditarMesa('".$mesa->get_nome()."');\">Cancelar</button>
 	 </form>
	";
}
echo "
 <br/>Código: ".$mesa->get_codigo()."
 <br/><br/>
 Contas registradas nessa mesa:<br/>
 <table border=\"1\" cellpadding=\"5\">
 	<tr bgcolor=\"#f0f0f0\">
 		<th>Estado</th>
 		<th>Data/Hora de abertura</th>
 		<th>Data/Hora de fechamento</th>
 		<th>Total</th>
 	</tr>
";

$conta_aberta = $banco->recupera_conta_aberta($mesa->get_id());
$contas_fechadas = $banco->recupera_contas_fechadas($mesa->get_id());

if ($conta_aberta || count($contas_fechadas) > 0) {
	// Só pode haver uma conta aberta
	if ($conta_aberta != null) {
		echo "
		 <tr align=\"center\">
			<td><a href=\"ver_conta.php?id=".$conta_aberta->get_id()."\" class=\"verde\">Aberta</a></td>
			<td><a href=\"ver_conta.php?id=".$conta_aberta->get_id()."\" class=\"verde\">".$conta_aberta->get_data_hora_abertura_formatado()."</a></td>
			<td>-</td>
			<td><a href=\"ver_conta.php?id=".$conta_aberta->get_id()."\" class=\"verde\">".$conta_aberta->get_total_formatado()."</a></td>
		 </tr>
		";
	}
	foreach ($contas_fechadas as $conta) {
		echo "
		 <tr align=\"center\">
			<td><a href=\"ver_conta.php?id=".$conta->get_id()."\">Fechada</a></td>
			<td><a href=\"ver_conta.php?id=".$conta->get_id()."\">".$conta->get_data_hora_abertura_formatado()."</a></td>
			<td><a href=\"ver_conta.php?id=".$conta->get_id()."\">".$conta->get_data_hora_fechamento_formatado()."</a></td>
			<td><a href=\"ver_conta.php?id=".$conta->get_id()."\">".$conta->get_total_formatado()."</a></td>
		 </tr>
		";
	}
}
else {
	echo "
	 <tr align=\"center\">
	 	<td colspan=\"4\">Ainda nenhuma conta foi aberta para essa mesa.</td>
	 </tr>
	";
}

echo "
 </table>
";
?>


<?php
$design->imprimir_fim();
?>