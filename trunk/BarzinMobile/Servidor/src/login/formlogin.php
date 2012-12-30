<?php
include_once '../classes/design.php';

$design = new Design("..");
$design->imprimir_topo();
?>


<table class="semborda">
	<tr valign="top">
		<td style="padding: 20px; text-align: justify;">
			<?php 
			if (isset($_REQUEST["msg"])) {
				echo "
				 <div class=\"msg\">
				 	".$_REQUEST["msg"]."
	 			 </div>
				";
			}
			?>
			<h1>Barzin - Sistema de Interação em Bares e Restaurantes</h1>
			A ideia do Barzin é promover a interação, em um ambiente de bar ou restaurante, entre clientes e entre os clientes e o estabelecimento. A interação é feita através de dispositivos (tablets) acoplados às mesas. 
			<p/>
			Os clientes podem interagir a partir da comunicação entre os dispositivos das mesas, dando a possibilidade do envio de mensagens entre clientes. 
			<p/>
			Outra característica do sistema é possibilitar a divisão dos pedidos na mesa entre as várias pessoas, calculando o total de cada um durante toda a interação no tablet.
			<p/>
			<iframe width="470" height="269" src="http://www.youtube.com/embed/ALBfKX63Klg" frameborder="0" allowfullscreen></iframe>
			<p/>
			<a href="../comofunciona.php">Clique para entender como funciona</a>
			<p/>
			<a href="../faleconosco/index.php">Entre em contato conosco</a> e saiba como implantar o Barzin no seu estabelecimento!
		</td>
		<td style="border-left: 1px solid #ff6c00; padding: 20px;">
			Entrar no sistema
			<p/>
		
			<?php 
			if (isset($_REQUEST["erro"])) {
				echo "
				 <div class=\"erro\">
				 	".$_REQUEST["erro"]."
	 			 </div>
				";
			}
			?>
			
			<form action="realizarlogin.php" method="post" id="form1">
			    <table class="semborda">
			      <tr valign=middle>
			        <td align=right>Usuário:</td>
			        <td align=left><input type="text" name="login" size=20>
			        </td>
			      </tr>
			      <tr valign=middle>
			        <td align=right>Senha:</td>
			        <td align=left><input type="password" name="senha" size=20>
			        </td>
			      </tr>
			      <tr>
			        <td colspan=2 align=center><button type="submit">OK</button>
			        </td>
			      </tr>
			    </table>
		  </form>		
		</td>
	</tr>
</table>

<?php 
$design->imprimir_fim();
?>
