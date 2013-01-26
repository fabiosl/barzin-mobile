<?php
require 'verifica.php';
include_once 'classes/design.php';

$design = new Design(".");
$design->imprimir_topo();

$bar = $banco->recupera_bar_pelo_login($_SESSION["usuario_logado"]);

echo "
 <div class=\"titulo_secao\">Gerência ".$bar->get_nome()."</div><br/><br/>
";

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

$hora = date("H");
if ($hora >= 4 && $hora < 12) {
	$cumprimento = "Bom dia";
}
else if ($hora >= 12 && $hora < 18) {
	$cumprimento = "Boa tarde";
}
else {
	$cumprimento = "Boa noite";
}

$numero_mesas = count($banco->recupera_mesas($bar->get_id()));
$numero_itens = $banco->consulta_num_itens_do_bar($bar->get_id());
$numero_categorias = $banco->consulta_num_categorias_do_bar($bar->get_id());
$numero_mesas_abertas = $banco->consulta_num_mesas_abertas($bar->get_id());

echo "
 $cumprimento! Utilize o menu à esquerda para navegar nas áreas de administração do estabelecimento <b>".$bar->get_nome()."</b>.
 <h2>Informações</h2>
 - Seu estabelecimento possui <b>$numero_mesas mesa".($numero_mesas != 1 ? "s" : "")."</b>.<br/>
 - No total, estão cadastrados <b>$numero_itens ite".($numero_itens == 1 ? "m" : "ns")."</b>, em <b>$numero_categorias categoria".($numero_categorias != 1 ? "s" : "")."</b>, em seu cardápio;<br/>
 - No momento, 
";
if ($numero_mesas_abertas == 0) {
	echo "não há <b>nenhuma mesa</b> com conta aberta.";
}
else {
	echo "há <b>$numero_mesas_abertas mesa".($numero_mesas_abertas != 1 ? "s</b> com contas abertas" : "</b> com conta aberta").".";
	echo "
	 <br/><br/>
	 <h2>Mensagem</h2>
	 Preencha abaixo para enviar uma mensagem para todas as mesas do bar (no mínimo 3 caracteres):
	 <p/>
	 <textarea maxlength=\"100\" id=\"mensagem\" style=\"width: 500px;\"></textarea><br/>
	 <button type=\"button\" id=\"botao_mensagem\" disabled=\"disabled\">Enviar</button>
	";
}
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
		"scripts/mensagens/enviar_para_todas.php", 
		{
			"id_bar": <?php echo $bar->get_id(); ?>, 
			"mensagem": $('#mensagem').val()
		}, 
		function (retorno) {
			if (retorno == "ok") {
				alert("Mensagem enviada com sucesso!");
			}
			else {
				alert("Sua mensagem não pôde ser enviada.");
			}
			$(elemento).text(texto_botao_anterior);
			$('#mensagem').val('');
		}
	);
});
</script>

<?php
$design->imprimir_fim();
?>