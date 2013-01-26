<?php
require '../verifica.php';
include_once '../classes/design.php';
include_once '../classes/dao.php';

$banco = new DAO();

$design = new Design("..");
$design->imprimir_topo();

$login_usuario = $_SESSION["usuario_logado"];
$tipo_usuario = $banco->get_tipo_usuario($login_usuario);

$mesa = $banco->recupera_mesa($_REQUEST["id"]);

if ($tipo_usuario != "admin") {
	$pode_alterar = false;
}
else {
	$pode_alterar = true;
	if ($banco->consulta_ha_conta_aberta($mesa->get_id())) {
		echo "
		 <div class=\"warning\">
			Há conta aberta nessa mesa no momento. Você só pode fazer alterações na mesa quando a conta for fechada.
		 </div>
		";
		$pode_alterar = false;
	}
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
 	 <span style=\"display: none\" id=\"nomeMesa\">".$mesa->get_nome()."</span> 
 	 <a href=\"javascript: void(0);\" onclick=\"prepararExcluirMesa(".$mesa->get_id().", ".$banco->consulta_num_contas_na_mesa($mesa->get_id()).")\">".$design->get_imagem('excluir.png', 'Excluir')."</a>
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
 <br/>
 Código: ".$mesa->get_codigo()."
 <br/>
";

$conta_aberta = $banco->recupera_conta_aberta($mesa->get_id());

if ($conta_aberta && $tipo_usuario == "admin") {
	echo "
	 <h2>Mensagem</h2>
	 Preencha abaixo para enviar uma mensagem para todas as pessoas dessa mesa (no mínimo 3 caracteres):
	 <p/>
	 <textarea maxlength=\"100\" id=\"mensagem\" style=\"width: 500px;\"></textarea><br/>
	 <button type=\"button\" id=\"botao_mensagem\" disabled=\"disabled\">Enviar</button>
	 <br/>
	";
}
echo "
 <br/>
 <h2>Contas registradas nessa mesa:</h2>
 <table border=\"1\" cellpadding=\"5\">
 	<tr bgcolor=\"#f0f0f0\">
 		<th>Estado</th>
 		<th>Data/Hora de abertura</th>
 		<th>Data/Hora de fechamento</th>
 		<th>Total</th>
 	</tr>
";

$contas_fechadas = $banco->recupera_contas_fechadas($mesa->get_id());

if ($conta_aberta || count($contas_fechadas) > 0) {
	// Só pode haver uma conta aberta
	if ($conta_aberta != null) {
		echo "
		 <tr align=\"center\">
			<td>
				<a href=\"ver_conta.php?id=".$conta_aberta->get_id()."\" class=\"verde\">
					Aberta<br/>
					(".count($mesa->get_pessoas())." pessoa".(count($mesa->get_pessoas()) > 1 ? "s" : "").")
				</a>
			</td>
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

<script type="text/javascript">
$('#mensagem').live("keyup", function () {
	if ($(this).val().length >= 3) {
		$('#botao_mensagem').removeAttr('disabled');
	}
	else {
		$('#botao_mensagem').attr('disabled', true);
	}
});

$('#botao_mensagem').live("click", function () {
	var texto_botao_anterior = $(this).text();
	$(this).attr('disabled', true);
	$(this).text("Enviando...");
	var elemento = $(this);
	$.post(
		"../scripts/mensagens/enviar_msg_pra_mesa.php", 
		{
			"id_mesa": <?php echo $mesa->get_id(); ?>, 
			"mensagem": $('#mensagem').val()
		}, 
		function (retorno) {
			if (retorno.hasOwnProperty("erro")) {
				alert("Erro: " + retorno.erro);
			}
			else {
				alert("Mensagem enviada com sucesso.");
			}
			$(elemento).text(texto_botao_anterior);
			$('#mensagem').val('');
		}, 
		"json"
	);
});
</script>

<?php
$design->imprimir_fim();
?>