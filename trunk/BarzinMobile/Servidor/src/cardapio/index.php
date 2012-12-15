<?php
require '../verifica.php';
include_once '../classes/design.php';

$banco = new DAO();

$design = new Design("..");
$design->imprimir_topo();

$login_usuario = $_SESSION["usuario_logado"];
$bar = $banco->recupera_bar_pelo_login($login_usuario);
?>

<script type="text/javascript" src="../javascripts/categorias.js" charset="utf-8"></script>

<a href="index.php" class="titulo_secao">Controle de Cardápio</a><br/><br/><br/>

<?php
$pode_alterar = true;
if ($banco->consulta_ha_mesas_abertas($bar->get_id())) {
	echo "
	 <div class=\"warning\">
		Há contas abertas no momento. Você só pode fazer alterações no cardápio quando todas as contas estiverem fechadas.
	 </div>
	";
	$pode_alterar = false;
}

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

if ($pode_alterar) {
	echo "
	 <br/>
 	 <div id=\"link_nova_categoria\" style=\"display: inline-block;\">
	 	<a href=\"javascript: void(0);\" onclick=\"prepararCriarCategoria();\"><img src=\"".$design->get_endereco_imagem("mais.gif")."\" /> Nova categoria</a>
 	 </div>
 	 <div id=\"carregando_nova_categoria\" style=\"display: none;\">
 		Salvando nova categoria...
 	 </div>
 	 <form id=\"form_nova_categoria\" action=\"\" onsubmit=\"return criarCategoria();\" style=\"display: none;\">
		Nova categoria*: <input type=\"text\" size=\"10\" name=\"nome\" class=\"Nome da categoria|Obrig\"/>
		<input type=\"hidden\" name=\"bar_id\" value=\"".$bar->get_id()."\"/>
		<button type=\"submit\">OK</button>
		<button type=\"button\" onclick=\"cancelarCriarCategoria()\">Cancelar</button>
 	 </form>
 	 <br/><br/>
	";
}

$cardapio = $banco->recupera_cardapio($bar);

$cardapio->imprimir_links_categorias();
?>



<?php
$design->imprimir_fim();
?>