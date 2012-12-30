<?php
require 'verifica.php';
include_once 'classes/design.php';
include_once 'classes/dao.php';
include_once 'classes/bar.php';

$design = new Design(".");
$design->imprimir_topo();

$banco = new DAO();

$login_usuario = $_SESSION["usuario_logado"];
$bar = $banco->recupera_bar_pelo_login($login_usuario);
?>

<script type="text/javascript">
function validar_formulario() {
	if ($('#email_valido').val() != "true") {
		alert('Você precisa validar o email antes de submeter a inscrição.');
		return false;
	} 
	return validar('form_cadastro');
}

$(function() {
	$('#estado').val('<?php echo $bar->get_estado(); ?>');
	
	$('#validar_email').click(function() {
		$.post(
			'scripts/cadastro/validar_email.php', 
			{
				email: $('#email').val(),
				id_bar: "<?php echo $bar->get_id(); ?>" 
			},
			function(data) {
				if (data == "ok") {
					$('#validar_email_msg').text("Válido");
					$('#validar_email_msg').css('color', '#009240');
					$('#email_valido').val('true');
				}
				else {
					$('#validar_email_msg').text(data);
					$('#validar_email_msg').css('color', '#ff0000');
					$('#email_valido').val('false');
				}
			} 
		);
	});

	$('#email').change(function() {
		$(this).siblings('span').text('Precisa validar');
		$(this).siblings('span').css('color', '#ff0000');
		$(this).siblings('input:hidden').val('false');
	});

	$('#enviar').click(function() {
		if (validar_formulario()) {
			$.post(
				'scripts/cadastro/alterar_cadastro.php', 
				{
					id_bar: $('#id_bar').val(), 
					nome: $('#nome').val(), 
					email: $('#email').val(),  
					rua: $('#rua').val(),  
					numero: $('#numero').val(),  
					complemento: $('#complemento').val(),  
					bairro: $('#bairro').val(),  
					cidade: $('#cidade').val(),  
					estado: $('#estado').val(), 
					cep: $('#cep').val(),
					telefone1: $('#telefone1').val(),  
					telefone2: $('#telefone2').val()  
				},
				function(retorno) {
					if (!retorno.hasOwnProperty('erro')) {
						window.location = "indexGerente.php?msg=Cadastro alterado com sucesso.";
					}
					else {
						alert("Seu cadastro não foi alterado por causa do seguinte erro: \n\n" + retorno.erro);
					}
				},
				"json"
			);
		}
	});
});
</script>



<div class="titulo_secao">Alterar Informações de Cadastro</div><br/><br/>
* Campos obrigatórios
<form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="POST" id="form_cadastro">
	<input type="hidden" name="id_bar" id="id_bar" value="<?php echo $bar->get_id(); ?>" />
	<table border="0" class="semborda">
		<tr valign="middle">
			<td align="right">Nome*:</td>
			<td align="left">
				<input type="text" size="50" name="nome" class="Nome|Obrig" id="nome" value="<?php echo $bar->get_nome(); ?>"/>
			</td>
		</tr>
		<tr valign="middle">
			<td align="right">E-mail*:</td>
			<td align="left">
				<input type="text" name="email" size=50 class="E-mail|Obrig|Email" onkeyup="preencher(this, 'EMAIL', event);" id="email" value="<?php echo $bar->get_email(); ?>"/>
				<button type="button" id="validar_email">Validar</button>
				<input type="hidden" id="email_valido" value="true" />
				<span id="validar_email_msg" style="color: #009240; display: inline-block; width: 180px;">Válido</span>
			</td>
		</tr>
		<tr valign="middle">
			<td align="right">Rua*:</td>
			<td align="left">
				<input type="text" name="rua" size=50 class="Rua|Obrig" id="rua" value="<?php echo $bar->get_rua(); ?>"/>
			</td>
		</tr>
		<tr valign="middle">
			<td align="right">Número:</td>
			<td align="left">
				<input type="text" name="numero" size=16 class="Numero|Opcional" id="numero" value="<?php echo $bar->get_numero(); ?>"/>
			</td>
		</tr>
		<tr valign="middle">
			<td align="right">Complemento:</td>
			<td align="left">
				<input type="text" name="complemento" size=50 class="Complemento|Opcional" id="complemento" value="<?php echo $bar->get_complemento(); ?>"/>
			</td>
		</tr>
		<tr valign="middle">
			<td align="right">Bairro*:</td>
			<td align="left">
				<input type="text" name="bairro" size=50 class="Bairro|Obrig" id="bairro" value="<?php echo $bar->get_bairro(); ?>"/>
			</td>
		</tr>
		<tr valign="middle">
			<td align="right">Cidade*:</td>
			<td align="left">
				<input type="text" name="cidade" size=50 class="Cidade|Obrig" id="cidade" value="<?php echo $bar->get_cidade(); ?>"/>
			</td>
		</tr>
		<tr valign="middle">
			<td align="right">Estado*:</td>
			<td align="left">
				<select name="estado" id="estado">
					<?php 
					$estados = array(
								"AC" => "Acre",
								"AL" => "Alagoas",
								"AP" => "Amapá",
								"AM" => "Amazonas", 
								"BA" => "Bahia",
								"CE" => "Ceará", 
								"DF" => "Distrito Federal",
								"ES" => "Espírito Santo",
								"GO" => "Goiás", 
								"MA" => "Maranhão", 
								"MT" => "Mato Grosso",
								"MS" => "Mato Grosso do Sul", 
								"MG" => "Minas Gerais", 
								"PA" => "Pará",
								"PB" => "Paraíba", 
								"PR" => "Paraná", 
								"PE" => "Pernambuco", 
								"PI" => "Piauí", 
								"RJ" => "Rio de Janeiro", 
								"RN" => "Rio Grande do Norte", 
								"RS" => "Rio Grande do Sul", 
								"RO" => "Rondônia", 
								"RR" => "Roraima", 
								"SC" => "Santa Catarina", 
								"SP" => "São Paulo", 
								"SE" => "Sergipe", 
								"TO" => "Tocantins");
					foreach ($estados as $sigla => $estado) {
						echo "<option value=\"$sigla\">$estado</option>";
					}
					?>
				</select>
			</td>
		</tr>
		<tr valign="middle">
			<td align="right">CEP*:</td>
			<td align="left">
				<input type="text" name="cep" size=16 class="CEP|Obrig|Mascara|NN.NNN-NNN" onkeyup="preencher(this, 'NN.NNN-NNN', event);" id="cep" value="<?php echo $bar->get_cep(); ?>"/>
				(Formato NN.NNNN-NNN)
			</td>
		</tr>
		<tr valign="middle">
			<td align="right">Telefones*:</td>
			<td align="left">
				<input type="text" name="telefone1" size=16 class="Telefone 1|Obrig|Mascara|(NN) NNNN.NNNN" onkeyup="preencher(this, '(NN) NNNN.NNNN', event);" id="telefone1" value="<?php echo $bar->get_telefone1(); ?>"/> / 
				<input type="text" name="telefone2" size=16 class="Telefone 2|Obrig|Mascara|(NN) NNNN.NNNN" onkeyup="preencher(this, '(NN) NNNN.NNNN', event);" id="telefone2" value="<?php echo $bar->get_telefone2(); ?>"/> 
				(Formato (NN) NNNN.NNNN)
			</td>
		</tr>
		<tr>
			<td colspan="2" align="center" style="padding-top: 20px;">
				<hr/>
				<button type="button" id="enviar">Enviar</button>
			</td>
		</tr>
	</table>
</form>

<?php 
$design->imprimir_fim();
?>
