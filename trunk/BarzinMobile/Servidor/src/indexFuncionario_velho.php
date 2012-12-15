<?php
require 'verifica.php';
include_once 'classes/design.php';
include_once 'classes/dao.php';
include_once 'classes/bar.php';
include_once 'classes/pedido.php';

$design = new Design(".");
$design->imprimir_topo();

$banco = new DAO();
?>
<script type="text/javascript">setTimeout("location.reload(true);", 20000);</script>

<h1>Controle de Pedidos</h1>

<?php
$login_usuario = $_SESSION["usuario_logado"];
$bar = $banco->recupera_bar_pelo_login($login_usuario);

$pedidos = $banco->recupera_pedidos_pendentes_do_bar($bar->get_id());

if (count($pedidos) == 0) {
	echo "Não há nenhum pedido pendente no momento.";
}
else {
	echo "<table border=\"1\">";
	$contador = 3;
	foreach ($pedidos as $pedido) {
		if ($contador == 3) {
			echo "<tr valign=\"top\">";
			$contador = 0;
		}
		$contador++;
		$item = $banco->recupera_item($pedido->get_item_id());
		$tablet = $banco->recupera_tablet_pela_conta($pedido->get_conta_id());
		echo "
		 <td bgcolor=\"".$pedido->cor_fundo()."\" width=\"33%\" align=\"center\">
		 <b><font size=\"+2\">$tablet</font></b><br/>
		 Item: <b>".$item->get_nome()."</b><br/>
		 Quantidade: <b>".$pedido->get_quantidade()."</b><br/>
		 <b>Há ".$pedido->ha_quanto_tempo_foi_feito()."</b>
		";
		if ($contador == 3) {
			echo "</tr>";
		}
	}
	while ($contador < 3) {
		echo "<td width=\"33%\"></td>";
		$contador++;
	}
	echo "</tr></table>";
}


$design->imprimir_fim();
?>