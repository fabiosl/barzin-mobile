<?php
require '../verifica.php';
include_once '../classes/design.php';
include_once '../classes/dao.php';
include_once '../classes/conta.php';
include_once '../classes/pedido.php';
include_once '../classes/item.php';

$banco = new DAO();

$design = new Design("..");
// $design->imprimir_topo();

// $login_usuario = $_SESSION["usuario_logado"];
// $tipo_usuario = $banco->get_tipo_usuario($login_usuario);

$mesa = $banco->recupera_mesa($_REQUEST["mesa_id"]);
$conta = $banco->recupera_conta_aberta($mesa->get_id());
?>

<html>
<head>
</head>
<body bgcolor="#ffffff" style="background-color: #ffffff; padding: 50px;">

<div style="background-color: #ffffff;">

<div class="titulo1"><?php echo $mesa->get_nome(); ?></div><br/><br/>
Aberta em: <?php echo $conta->get_data_hora_abertura_formatado(); ?>
<p/>
Pedidos atendidos/cancelados:<br/>
<table border="1" cellpadding="5">
	<tr bgcolor="#f0f0f0">
		<th>Hora</th>
		<th>Item</th>
		<th>Quantidade</th>
		<th>Estado</th>
		<th>Preço Unidade</th>
		<th>Preço</th>
	</tr>

<?php
$pedidos_atendidos = $conta->get_pedidos();

if (count($pedidos_atendidos) > 0) {
	foreach ($conta->get_pedidos() as $pedido) {
		$item = $banco->recupera_item($pedido->get_item_id());
		echo "
		 <tr align=\"center\">
		 	<td>".$pedido->get_hora()."</td>
		 	<td>".$item->get_nome()."</td>
		 	<td>".$pedido->get_quantidade()."</td>
		 	<td>".$pedido->get_estado()."</td>
		 	<td>".$item->get_preco_formatado()."</td>
		 	<td>
		";
		if ($pedido->get_estado() == "Atendido") {
			echo sprintf("R$ %.2f", $item->get_preco() * $pedido->get_quantidade());
		}
		else {
			echo "-";
		}
		echo "
			</td>
		 </tr>
		";
	}
}
else {
	echo "
	 <tr align=\"center\">
	 	<td colspan=\"6\">Ainda nenhum pedido foi atendido dessa conta</td>
	 </tr>
	";
}

echo "
	<tr bgcolor=\"#f0f0f0\">
		<th colspan=\"5\" align=\"right\">TOTAL</th>
		<th>".$conta->get_total_formatado()."</th>
	</tr>
 </table>
";

$pedidos_pendentes = $banco->recupera_pedidos_pendentes_da_mesa($mesa->get_id());
if (count($pedidos_pendentes) > 0) {
	echo "
	 <p />
	 Essa mesa ainda tem os seguintes pedidos pendentes:
	 <p />
	";
	foreach ($pedidos_pendentes as $pedido) {
		$item = $banco->recupera_item($pedido->get_item_id());
		echo "
		 Hora do pedido: <b>".$pedido->get_hora()." </b><br/>
		 Item: <b>".$item->get_nome()."</b><br/>
		 Quantidade: <b>".$pedido->get_quantidade()."</b>
		 <p/>
		";
	}
}
?>

</div>

</body>
</html>