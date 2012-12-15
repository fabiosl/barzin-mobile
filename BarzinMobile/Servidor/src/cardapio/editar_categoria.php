<?php
require '../verifica.php';
include_once '../classes/design.php';
include_once '../classes/dao.php';

$design = new Design("..");
$design->imprimir_topo();

$banco = new DAO();

$categoria = $banco->recupera_categoria($_GET["id"]);
$bar = $banco->recupera_bar($categoria->get_bar_id());
?>

<script type="text/javascript" src="../javascripts/categorias.js" charset="utf-8"></script>
<script type="text/javascript" src="../javascripts/itens.js" charset="utf-8"></script>
<script type="text/javascript" src="../javascripts/funcoes.js" charset="utf-8"></script>

<link rel="stylesheet" href="../css/colorbox.css" type="text/css">
<script type="text/javascript" src="../javascripts/jquery-1.6.4.js"></script>
<script type="text/javascript" src="../javascripts/jquery.colorbox.js"></script>
<script>
function abrirJanelaFoto(id, editar) {
	if (editar) {
		$.colorbox({href: 'foto.php?id=' + id,
			width:"70%", 
			height:"70%", 
			iframe: true, 
			top: 100,
			onClosed: function() {depoisDeFechar(id);}});		
	}
	else {
		$.colorbox({href: 'ver_foto.php?id=' + id,
			width:"70%", 
			height:"70%",
			top: 100, 
			iframe: true});
	}	
}

function depoisDeFechar(id) {
	xmlhttp.open("get", "../scripts/servicos_item.php?operacao=pegar_foto&id=" + id + "&pegar_thumb=1", true);
	xmlhttp.onreadystatechange = function () {
		if (xmlhttp.readyState == 4 & xmlhttp.status == 200) {
			$("#thumb_" + id).attr('src', xmlhttp.responseText + '?n=' + Math.random());
			excluirTemp(id);
		}
		else if (xmlhttp.readyState == 2) {
			$("#thumb_" + id).attr('src', '../img/preload.gif');
		}
	};
	xmlhttp.send(null);
}

function excluirTemp(id) {
	xmlhttp.open("get", "../scripts/servicos_item.php?operacao=excluir_temp&id=" + id, true);
	xmlhttp.onreadystatechange = function () {};
	xmlhttp.send(null);
}
</script>



<a href="index.php" class="titulo_secao">Controle de Cardápio - Categoria</a><br/><br/><br/>

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

$mae_id = $categoria->get_categoria_mae_id();
$maes = '';
while ($mae_id != null) {
	$mae = $banco->recupera_categoria($mae_id);
	$maes = " > <a href=\"".$_SERVER["PHP_SELF"]."?id=".$mae->get_id()."\">".$mae->get_nome()."</a>".$maes;
	$mae_id = $mae->get_categoria_mae_id();
}
echo "
 > <a href=\"index.php\">Cardápio</a>$maes<br/>
 <div class=\"titulo1\" id=\"nome_categoria\">".$categoria->get_nome()."</div>
";
if ($pode_alterar) {
	echo "
	 <div id=\"links_categoria\" style=\"display: inline-block;\">
	 	<a href=\"javascript: void(0);\" onclick=\"prepararEditarCategoria()\">".$design->get_imagem('lapis.png', 'Editar')."</a> 
	 	<a href=\"javascript: void(0);\" onclick=\"prepararExcluirCategoria(".$categoria->get_id().", '".$categoria->get_nome()."', ".count($categoria->get_subcategorias()).", ".$categoria->get_numero_itens().")\">".$design->get_imagem('excluir.png', 'Excluir')."</a>
	 </div><br/>
	 <div id=\"carregando_categoria\" style=\"display: none;\">
 		Carregando...
	 </div>
	 <form id=\"form_categoria\" action=\"\" onsubmit=\"return editarCategoria();\" style=\"display: none;\">
		Alterar categoria*: <input type=\"text\" size=\"10\" name=\"nome\" class=\"Nome da categoria|Obrig\" value=\"".$categoria->get_nome()."\"/>
		<input type=\"hidden\" name=\"id\" value=\"".$categoria->get_id()."\"/>
		<button type=\"submit\">OK</button>
		<button type=\"button\" onclick=\"cancelarEditarCategoria('".$categoria->get_nome()."');\">Cancelar</button>
 	 </form>
	";
}
echo "
 <br/>
 <br/>
 Subcategorias:<br/>
";
if ($categoria->get_subcategorias() == 0) {
	echo "Essa categoria não possui subcategorias.<br/>";
}
foreach ($categoria->get_subcategorias() as $subcategoria) {
	$subcategoria->imprimir_links_manipulacao(30);
}
if ($pode_alterar) {
	echo "
	 <br/>
	 <div id=\"link_nova_subcategoria\" style=\"display: inline-block;\">
	 	<a href=\"javascript: void(0);\" onclick=\"prepararCriarSubcategoria()\"><img src=\"".$design->get_endereco_imagem("mais.gif")."\" /> Nova subcategoria</a>
	 </div>
	 <div id=\"carregando_nova_subcategoria\" style=\"display: none;\">
	 	Salvando nova subcategoria...
	 </div>
	 <form id=\"form_nova_subcategoria\" action=\"\" onsubmit=\"return criarSubcategoria();\" style=\"display: none;\">
		Nova subcategoria*: <input type=\"text\" size=\"10\" name=\"nome\" class=\"Nome da subcategoria|Obrig\"/>
		<input type=\"hidden\" name=\"id_mae\" value=\"".$categoria->get_id()."\"/>
		<button type=\"submit\">OK</button>
		<button type=\"button\" onclick=\"cancelarCriarSubcategoria()\">Cancelar</button>
	 </form>
	";
}
echo "
 <br/>
 <br/>
 <br/>
 <br/>
 Itens:<br/>
";
if ($pode_alterar) {
	echo "
	 <a href=\"javascript: void(0);\" id=\"link_novo_item\" onclick=\"prepararCriarItem();\"><img src=\"".$design->get_endereco_imagem("mais.gif")."\" /> Novo item</a>
	 <div id=\"novo_item\" style=\"display: none;\">
 		<table class=\"semBorda\">
	 		<form id=\"form_novo_item\" action=\"\" onsubmit=\"return criarItem();\">
 			<input type=\"hidden\" name=\"categoria_id\" value=\"".$categoria->get_id()."\" />
 			<tr>
	 			<td nowrap align=\"right\">Novo item*:</td>
 				<td><input type=\"text\" size=\"20\" name=\"nome\" class=\"Nome do item|Obrig\" /></td>
 			</tr>
 			<tr>
	 			<td nowrap width=\"1\" align=\"right\">Preço*:</td>
 				<td>R$ <input type=\"text\" size=\"5\" name=\"preco\" class=\"Preço do item|Obrig\" onkeyup=\"preencher(this, 'FLOAT', event);\" /></td>
 			</tr>
 			<tr>
				<td nowrap colspan=\"2\">
					<input type=\"checkbox\" name=\"disponivel\" value=\"1\" id=\"disponivel\" checked /> 
					<label for=\"disponivel\">Disponível</label>
				</td>
			</tr>
			<tr valign=\"top\">
				<td nowrap width=\"1\" align=\"right\">Descrição:</td>
				<td><textarea name=\"descricao\" cols=\"30\" rows=\"2\"></textarea></td>
			</tr>
			<tr>
				<td colspan=\"2\" align=\"center\"><button type=\"submit\">OK</button> <button type=\"button\" onclick=\"cancelarCriarItem()\">Cancelar</button>
			</tr>
			</form>
		</table>
		<br/>
	 </div>
	 <div id=\"salvando_novo_item\" style=\"display: none;\">Salvando novo item...</div>
	";
}
if (count($categoria->get_itens()) == 0) {
	echo "<br/>Essa categoria não possui itens.<br/>";
}
else {
	echo "
	 <br/>
	 <table border=\"1\" cellpadding=\"5\">
		<tr bgcolor=\"#f0f0f0\">
			<th>Foto<br/>(Clique)</th>
			<th>Item</th>
			<th width=\"1\">Preço</th>
			<th width=\"1\">Disponível</th>
			<th>Descrição</th>
		</tr>
	";
}
foreach ($categoria->get_itens() as $item) {
	$disponivel = $item->get_disponivel() ? "Sim" : "Não";
	echo "
	 <tr id=\"item_".$item->get_id()."\">
	 	<td width=\"75\" align=\"center\">
	";
	if ($pode_alterar) {
		echo "
	 		<a href=\"javascript: void(0);\" title=\"Editar foto\" id=\"".$item->get_id()."\" onclick=\"abrirJanelaFoto(".$item->get_id().", true);\">
	 			<img id=\"thumb_".$item->get_id()."\" src=\"".$item->get_endereco_thumb()."\" />
	 		</a>
 		";
	}
	else {
		echo "
			<a href=\"javascript: void(0);\" title=\"Ver foto\" id=\"".$item->get_id()."\" onclick=\"abrirJanelaFoto(".$item->get_id().", false);\">
				<img id=\"thumb_".$item->get_id()."\" src=\"".$item->get_endereco_thumb()."\" />
			</a>
		";
	}
	echo "
	 	</td>
	 	<td>".$item->get_nome()."<br/>
	 		<div style=\"display: none;\" id=\"salvando_item_".$item->get_id()."\">
	 			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Salvando...
	 		</div>
	 		<div style=\"display: none;\" id=\"excluindo_item_".$item->get_id()."\">
	 			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Excluindo...
	 		</div>
	 	</td>
	 	<td align=\"center\" nowrap>".$item->get_preco_formatado()."</td>
	 	<td align=\"center\">$disponivel</td>
	 	<td>".$item->get_descricao()."</td>
	";
	if ($pode_alterar) {
		echo " 	
	 	 <td id=\"link_editar_item_".$item->get_id()."\" align=\"center\"><a href=\"javascript: void(0);\" onclick=\"prepararEditarItem(".$item->get_id().")\">".$design->get_imagem('lapis.png', 'Editar')."</a></td>
	 	 <td id=\"link_excluir_item_".$item->get_id()."\" align=\"center\"><a href=\"javascript: void(0);\" onclick=\"prepararExcluirItem(".$item->get_id().", '".$item->get_nome()."', ".$item->get_categoria_id().")\">".$design->get_imagem('excluir.png', 'Editar')."</a></td>
	 	";
	}
	echo "
	 </tr>
	";
	if ($pode_alterar) {
		echo "
	 	 <tr id=\"editar_item_".$item->get_id()."\" style=\"display: none;\">
	 		<form id=\"form_editar_item_".$item->get_id()."\" action=\"\" onsubmit=\"return editarItem(".$item->get_id().");\">
	 		<input type=\"hidden\" name=\"categoria_id\" value=\"".$item->get_categoria_id()."\" />
	 		<td></td>
	 		<td nowrap align=\"center\">* <input type=\"text\" size=\"15\" name=\"nome\" class=\"Nome do item|Obrig\" value=\"".$item->get_nome()."\"/></td>
	 		<td nowrap align=\"center\">* R$ <input type=\"text\" size=\"3\" name=\"preco\" class=\"Preço do item|Obrig\" onkeyup=\"preencher(this, 'FLOAT', event);\" value=\"".$item->get_preco()."\"/></td>
	 		<td align=\"center\"><input type=\"checkbox\" name=\"disponivel\" value=\"1\" ".($disponivel == "Sim" ? "checked" : "")." /></td>
	 		<td align=\"center\"><textarea name=\"descricao\" cols=\"20\" rows=\"2\">".$item->get_descricao()."</textarea></td>
	 		<td colspan=\"2\">
			 	<button type=\"submit\">OK</button>
				<button type=\"button\" onclick=\"cancelarEditarItem(".$item->get_id().")\">Cancelar</button>
	 		</td>
	 		</form>
	 	 </tr>
		";
	}
}
echo "</table>";
?>



<?php
$design->imprimir_fim();
?>