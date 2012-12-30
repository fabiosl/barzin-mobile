<?php
include_once 'classes/design.php';

$design = new Design(".");
$design->imprimir_topo();
?>

<script type="text/javascript">
function validar_formulario() {
	if ($('#gerente_valido').val() != "true" || $('#garcom_valido').val() != "true"  || $('#email_valido').val() != "true") {
		alert('Você precisa validar o email e os usuários gerente e garçom antes de submeter a inscrição.');
		return false;
	} 
	return validar('form_cadastro');
}

$(function() {
	$('#validar_gerente').click(function() {
		if ($('#usuario_gerente').val() == $('#usuario_garcom').val()) {
			$('#validar_gerente_msg').text("Usuários gerente e garçom devem ser diferentes");
			$('#validar_gerente_msg').css('color', '#ff0000');
			$('#gerente_valido').val('false');
			return;
		}
		$.post(
			'scripts/cadastro/validar_usuario.php', 
			{usuario: $('#usuario_gerente').val()},
			function(data) {
				if (data == "ok") {
					$('#validar_gerente_msg').text("Válido");
					$('#validar_gerente_msg').css('color', '#009240');
					$('#gerente_valido').val('true');
				}
				else {
					$('#validar_gerente_msg').text(data);
					$('#validar_gerente_msg').css('color', '#ff0000');
					$('#gerente_valido').val('false');
				}
			} 
		);
	});
	
	$('#validar_garcom').click(function() {
		if ($('#usuario_gerente').val() == $('#usuario_garcom').val()) {
			$('#validar_garcom_msg').text("Usuários gerente e garçom devem ser diferentes");
			$('#validar_garcom_msg').css('color', '#ff0000');
			$('#garcom_valido').val('false');
			return;
		}
		$.post(
			'scripts/cadastro/validar_usuario.php', 
			{usuario: $('#usuario_garcom').val()},
			function(data) {
				if (data == "ok") {
					$('#validar_garcom_msg').text("Válido");
					$('#validar_garcom_msg').css('color', '#009240');
					$('#garcom_valido').val('true');
				}
				else {
					$('#validar_garcom_msg').text(data);
					$('#validar_garcom_msg').css('color', '#ff0000');
					$('#garcom_valido').val('false');
				}
			} 
		);
	});

	$('#validar_email').click(function() {
		$.post(
			'scripts/cadastro/validar_email.php', 
			{email: $('#email').val()},
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

	$('#usuario_gerente, #usuario_garcom, #email').change(function() {
		$(this).siblings('span').text('Precisa validar');
		$(this).siblings('span').css('color', '#ff0000');
		$(this).siblings('input:hidden').val('false');
	});

	$('#enviar').click(function() {
		if (validar_formulario()) {
			$.post(
				'scripts/cadastro/cadastrar.php', 
				{
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
					telefone2: $('#telefone2').val(),  
					admin_login: $('#usuario_gerente').val(), 
					admin_senha: $('#senha_gerente').val(),  
					func_login: $('#usuario_garcom').val(),  
					func_senha: $('#senha_garcom').val()
				},
				function(retorno) {
					if (!retorno.hasOwnProperty('erro')) {
						window.location = "login/formlogin.php?msg=Cadastro realizado com sucesso. Você já pode entrar no sistema.";
					}
					else {
						alert("Seu cadastro não foi realizado por causa do seguinte erro: \n\n" + retorno.erro);
					}
				}, 
				"json"
			);
		}
	});
});
</script>



<div class="titulo_secao">Cadastre-se</div><br/><br/>

O cadastro é gratuito. Depois de cadastrado, você terá dois usuários - um para gerente do bar e outro para os garçons. O usuário gerente é o que poderá fazer atividades de gerência, como editar cardápio e fechar contas. O usuário garçom receberá os pedidos na tela.
<p/>

* Campos obrigatórios
<form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="POST" id="form_cadastro">
	<table border="0" class="semborda">
		<tr valign="middle">
			<td align="right">Nome*:</td>
			<td align="left">
				<input type="text" size="50" name="nome" class="Nome|Obrig" id="nome"/>
			</td>
		</tr>
		<tr valign="middle">
			<td align="right">E-mail*:</td>
			<td align="left">
				<input type="text" name="email" size=50 class="E-mail|Obrig|Email" onkeyup="preencher(this, 'EMAIL', event);" id="email" />
				<button type="button" id="validar_email">Validar</button>
				<input type="hidden" id="email_valido" value="false" />
				<span id="validar_email_msg" style="color: #ff0000; display: inline-block; width: 180px;">Precisa validar</span>
			</td>
		</tr>
		<tr valign="middle">
			<td align="right">Rua*:</td>
			<td align="left">
				<input type="text" name="rua" size=50 class="Rua|Obrig" id="rua"/>
			</td>
		</tr>
		<tr valign="middle">
			<td align="right">Número:</td>
			<td align="left">
				<input type="text" name="numero" size=16 class="Numero|Opcional" id="numero"/>
			</td>
		</tr>
		<tr valign="middle">
			<td align="right">Complemento:</td>
			<td align="left">
				<input type="text" name="complemento" size=50 class="Complemento|Opcional" id="complemento"/>
			</td>
		</tr>
		<tr valign="middle">
			<td align="right">Bairro*:</td>
			<td align="left">
				<input type="text" name="bairro" size=50 class="Bairro|Obrig" id="bairro"/>
			</td>
		</tr>
		<tr valign="middle">
			<td align="right">Cidade*:</td>
			<td align="left">
				<input type="text" name="cidade" size=50 class="Cidade|Obrig" id="cidade"/>
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
				<input type="text" name="cep" size=16 class="CEP|Obrig|Mascara|NN.NNN-NNN" onkeyup="preencher(this, 'NN.NNN-NNN', event);" id="cep"/>
				(Formato NN.NNNN-NNN)
			</td>
		</tr>
		<tr valign="middle">
			<td align="right">Telefones*:</td>
			<td align="left">
				<input type="text" name="telefone1" size=16 class="Telefone 1|Obrig|Mascara|(NN) NNNN.NNNN" onkeyup="preencher(this, '(NN) NNNN.NNNN', event);" id="telefone1"/> / 
				<input type="text" name="telefone2" size=16 class="Telefone 2|Obrig|Mascara|(NN) NNNN.NNNN" onkeyup="preencher(this, '(NN) NNNN.NNNN', event);" id="telefone2"/> 
				(Formato (NN) NNNN.NNNN)
			</td>
		</tr>
		<tr>
			<td colspan="2" style="padding-top: 20px;">
				<hr/>
				<b>Usuário gerente</b><br/>
				Preencha e clique em "Validar"
			</td>
		</tr>
		<tr valign="middle">
			<td align="right">Nome de usuário*:</td>
			<td align="left">
				<input type="text" name="usuario_gerente" size=16 class="Nome de Usuário Gerente|Opcional|Usuario" id="usuario_gerente" /> 
				<button type="button" id="validar_gerente">Validar</button>
				<input type="hidden" id="gerente_valido" value="false" />
				<span id="validar_gerente_msg" style="color: #ff0000;">Precisa validar</span>
			</td>
		</tr>
		<tr valign="middle">
			<td align="right">Senha*:</td>
			<td align="left">
				<input type="password" name="senha_gerente" size=16 class="Senha de Gerente|Obrig|Senha" id="senha_gerente"/> 
			</td>
		</tr>
		<tr>
			<td colspan="2" style="padding-top: 20px;">
				<hr/>
				<b>Usuário garçom</b><br/>
				Preencha e clique em "Validar"
			</td>
		</tr>
		<tr valign="middle">
			<td align="right">Nome de usuário*:</td>
			<td align="left">
				<input type="text" name="usuario_garcom" size=16 class="Nome de Usuário Garçom|Obrig|Usuario" id="usuario_garcom" />
				<button type="button" id="validar_garcom">Validar</button>
				<input type="hidden" id="garcom_valido" value="false" />
				<span id="validar_garcom_msg" style="color: #ff0000;">Precisa validar</span> 
			</td>
		</tr>
		<tr valign="middle">
			<td align="right">Senha*:</td>
			<td align="left">
				<input type="password" name="senha_garcom" size=16 class="Senha de Garçom|Obrig|Senha" id="senha_garcom"/> 
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
