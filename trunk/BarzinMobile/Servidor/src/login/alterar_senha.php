<?php
require '../verifica.php';
include_once '../classes/design.php';
include_once '../classes/dao.php';

if (isset($_REQUEST["usuario"])) {
	if ($_REQUEST["nova_senha_1"] != $_REQUEST["nova_senha_2"]) {
		$erro = "As senhas não conferem.";
	}
	else {
		$banco = new DAO();
		if (!$banco->login_valido($_REQUEST["usuario"], $_REQUEST["senha_atual"])) {
			$erro = "Senha atual incorreta.";
		}
		else {
			$resultado = $banco->alterar_senha($_REQUEST["usuario"], $_REQUEST["nova_senha_1"]);
			if ($resultado != "ok") {
				$erro = $resultado;
			}
			else {
				$msg = "Senha alterada com sucesso.";
				header("Location: ../indexGerente.php?msg=$msg");
			}
		}
	}
}



$design = new Design("../");
$design->imprimir_topo();

$usuario = $_SESSION["usuario_logado"];
?>

<h1>Alterar Senha</h1>

<?php 
if (isset($erro)) {
  echo "
   <div class=\"erro\">
   		$erro
   </div>
  ";
}

echo "
 <form action=\"".$_SERVER["PHP_SELF"]."\" method=\"post\">
 <input type=\"hidden\" name=\"usuario\" value=\"$usuario\" />
 <table class=\"semborda\" align=\"center\">
	<tr>
		<td align=\"right\">Usuário:</td>
		<td><b>$usuario</b></td>
	</tr>
	<tr>
		<td align=\"right\">Senha atual:</td>
		<td align=\"left\"><input type=\"password\" name=\"senha_atual\" size=\"20\"></td>
	</tr>
	<tr>
		<td align=\"right\">Nova senha:</td>
		<td align=\"left\"><input type=\"password\" name=\"nova_senha_1\" size=\"20\"></td>
	</tr>
	<tr>
		<td align=\"right\">Confirme a nova senha:</td>
		<td align=\"left\"><input type=\"password\" name=\"nova_senha_2\" size=\"20\"></td>
	</tr>
	<tr>
		<td align=\"center\" colspan=\"2\">
			<button type=\"submit\">OK</button>
		</td>
	</tr>
 </table>
 </form>
";


?>

<?php 
$design->imprimir_fim();
?>
