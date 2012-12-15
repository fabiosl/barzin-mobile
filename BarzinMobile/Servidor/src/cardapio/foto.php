<?php
require '../verifica.php';
include_once '../classes/design.php';
include_once '../classes/dao.php';

$design = new Design("..");

$banco = new DAO();

if (isset($_GET["id"])) {
	$id = $_GET["id"];
}

$item = $banco->recupera_item($id);
?>

<html>
<head>
<link rel="stylesheet" href="../css/simples.css" type="text/css">
<script src="../javascripts/funcoes.js" type="text/javascript"></script>
<script src="../javascripts/jquery-1.6.4.js" type="text/javascript"></script>
<script src="../javascripts/ajaxupload.js" type="text/javascript"></script>

<script type="text/javascript">
$(function() {
	var fileUpload = $('#arquivo_imagem');
	new AjaxUpload(fileUpload, {
		action: '../scripts/servicos_item.php',
		name: 'arquivo_imagem',
		data: {
			id: <?php echo $item->get_id(); ?>,
			operacao: 'foto_temp'
		},
		onSubmit: function(file, ext) {
			$('#preview_imagem').attr('src', "<?php echo $design->get_endereco_imagem('preload.gif'); ?>");
		},
		onComplete: function(file, response){
			$('#salvar').css('display', 'block');
			$('#cancelar').css('display', 'block');
			$('#preview_imagem').attr('src', response);
		}
	});
});

function salvar_foto() {
	var id = $('#id').val();
	var url = "../scripts/servicos_item.php?operacao=salvar_foto&id=" + id;
	xmlhttp.open("get", url, true);
	xmlhttp.onreadystatechange = function () {
		if (xmlhttp.readyState == 4 & xmlhttp.status == 200) {
			if (xmlhttp.responseText == "ok") {
				parent.$.colorbox.close();
			}
		}
		else {
			$('#carregando').html('Carregando...');
			$('#cancelar').css('display', 'none');
			$('#salvar').css('display', 'none');
			$('#excluir').css('display', 'none');
		}
	};
	xmlhttp.send(null);
}

function excluir_foto() {
	var id = $('#id').val();
	var url = "../scripts/servicos_item.php?operacao=excluir_foto&id=" + id;
	xmlhttp.open("get", url, true);
	xmlhttp.onreadystatechange = function () {
		if (xmlhttp.readyState == 4 & xmlhttp.status == 200) {
			if (xmlhttp.responseText == "ok") {
				parent.$.colorbox.close();
			}
		}
		else {
			$('#carregando').html('Carregando...');
			$('#cancelar').css('display', 'none');
			$('#salvar').css('display', 'none');
			$('#excluir').css('display', 'none');
		}
	};
	xmlhttp.send(null);
}
</script>

</head>
<body bgcolor="#ffffff">

<div style="background-color: #ffffff;">
<table border="0">
<tr valign="top">


<?php
echo "
 <td>
	<img src=\"".$item->get_endereco_imagem()."?n=".time()."\" id=\"preview_imagem\" />
 </td><td>
 	Item: <b>".$item->get_nome()."</b>
 	<p/>
 	<form id=\"form_imagem\" enctype=\"multipart/form-data\" method=\"post\">
 	<input type=\"hidden\" name=\"id\" id=\"id\" value=\"".$item->get_id()."\" />
 	".$design->get_imagem('lapis.png', 'Alterar foto')." 
";
if ($item->tem_imagem()) {
	echo "
	 Alterar foto:
	";
}
else {
	echo "
	 Nova foto:
	";
}
echo "
 <input type=\"button\" name=\"arquivo_imagem\" id=\"arquivo_imagem\" value=\"Selecionar arquivo\" /><br/>
 <button type=\"button\" id=\"salvar\" style=\"display: none;\" onclick=\"salvar_foto();\">Salvar</button>
 </form>
";
if ($item->tem_imagem()) {
	echo "
	 <a href=\"javascript: void(0);\" onclick=\"excluir_foto();\" style=\"margin-top: 50px;\" id=\"excluir\">
	 ".$design->get_imagem('excluir.png', 'Excluir foto')." Excluir foto	
	 </a><br/>
	";
}
echo "
 <button style=\"margin-top: 50px;\" type=\"button\" id=\"cancelar\" onclick=\"parent.$.colorbox.close();\">Cancelar</button>
 <div id=\"carregando\"></div>
";
?>
</td>
</tr>
</table>
</div>

</body>
</html>
