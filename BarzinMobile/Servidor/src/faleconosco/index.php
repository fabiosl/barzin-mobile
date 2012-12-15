<?php
include_once '../classes/design.php';
include_once '../classes/class.phpmailer.php';

session_name("barzin");
session_start();

$design = new Design("..");
$design->imprimir_topo();
?>

<div class="titulo_secao">Fale conosco</div><br/><br/>

<?php 
if (isset($_REQUEST["enviar"])) {
	$msg = "Enviado em: ".date("d/m/Y - H:i")."
	
Nome: ".$_REQUEST["nome"]."
E-mail: ".$_REQUEST["email"]."
Telefone: ".$_REQUEST["telefone"]."

Mensagem: 
".$_REQUEST["mensagem"];

	$mail = new PHPMailer();

	$mail->IsSMTP();
	$mail->SMTPAuth = true;                  // enable SMTP authentication
	$mail->SMTPSecure = "ssl";                 // sets the prefix to the servier
	$mail->Host = "smtp.gmail.com";      // sets GMAIL as the SMTP server
	$mail->Port = 465;                   // set the SMTP port

	$mail->Username = "barzin.sistema@gmail.com";  // GMAIL username
	$mail->Password = "theruraljuror";            // GMAIL password

	$mail->From = $_REQUEST["email"];
	$mail->FromName = "Contato Barzin";
	$mail->Subject = "Contato Barzin - Enviado pelo site";
	$mail->Body = $msg; //Text Body
// 	$mail->WordWrap   = 50; // set word wrap

	$mail->AddReplyTo($_REQUEST["email"],"Contato Barzin");

	$mail->AddAddress("barzin.sistema@gmail.com","Barzin");

	$enviado = $mail->Send();
	
	// Exibe uma mensagem de resultado
	if ($enviado) {
		echo "
		 <div class=\"msg\">
		 	Mensagem enviada com sucesso. Obrigado por entrar em contato conosco!
		 </div>
		";
	} else {
		echo "
		 <div class=\"erro\">
		 	Não foi possível enviar a mensagem devido ao seguinte erro: ".$mail->ErrorInfo."
		 </div>
		";
	}
}
else {
?>


* Campos obrigatórios
<form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="POST" id="form_contato" onsubmit="return validar('form_contato');">
	<input type="hidden" name="enviar" value="1" />
	<table border="0" class="semborda">
		<tr valign="top">
			<td align="right">Nome*:</td>
			<td align="left">
				<input type="text" size="50" name="nome" class="Nome|Obrig" />
			</td>
		</tr>
		<tr valign="top">
			<td align="right">E-mail*:</td>
			<td align="left">
				<input type="text" name="email" size=50 class="E-mail|Obrig|Email" onkeyup="preencher(this, 'EMAIL', event);" />
			</td>
		</tr>
		<tr valign="top">
			<td align="right">Telefone:</td>
			<td align="left">
				<input type="text" name="telefone" size=16 class="Telefone|Opcional|Mascara|(NN) NNNN.NNNN" onkeyup="preencher(this, '(NN) NNNN.NNNN', event);" />
			</td>
		</tr>
		<tr valign="top">
			<td align="right">Mensagem*:</td>
			<td align="left">
				<textarea name="mensagem" cols=50 rows=5 class="Mensagem|Obrig"></textarea>
			</td>
		</tr>
		<tr>
			<td colspan="2" align="center">
				<button type="submit">Enviar</button>
			</td>
		</tr>
	</table>
</form>
<?php 
}
?>

<?php
$design->imprimir_fim();
?>