<?php
include_once 'classes/design.php';

$design = new Design(".");
$design->imprimir_topo();
?>

<div class="titulo_secao">Como Funciona</div><br/><br/>

<table border="0" cellpadding="5" class="semborda" style="width: 70%;" align="center">
	<tr>
		<td align="center"><?php echo $design->get_imagem("comofunciona1.png"); ?></td>
		<td>
			O gerente tem acesso ao sistema através de usuário e senha. Ele tem poder
			de cadastrar todo o cardápio de seu estabelecimento, classificando os itens
			por categorias (e subcategorias), definindo seu preço e salvando uma foto.
		</td>
	</tr>
	<tr>
		<td align="center"><?php echo $design->get_imagem("comofunciona2.png"); ?></td>
		<td>
			Os clientes têm acesso ao cardápio nos tablets colocados nas mesas, tendo
			a possibilidade de fazer pedidos através dos mesmos.
		</td>
	</tr>
	<tr>
		<td align="center"><?php echo $design->get_imagem("comofunciona3.png"); ?></td>
		<td>
			O garçom percebe o pedido feito através de um computador no balcão, onde aparecem
			os pedidos de todas as mesas. Ele atende o pedido e "marca-o" como atendido pelo
			tablet da mesa.
		</td>
	</tr>
	<tr>
		<td align="center"><?php echo $design->get_imagem("comofunciona4.png"); ?></td>
		<td>
			A qualquer momento pode-se acompanhar a conta pelo tablet, ficando claramente
			dividida a conta por todas as pessoas da mesa que participaram de cada pedido.
		</td>
	</tr>
	<tr>
		<td align="center"><?php echo $design->get_imagem("comofunciona5.png"); ?></td>
		<td>
			Em meio a isso, o cliente também poderá trocar mensagens com outros tablets do
			mesmo bar.
		</td>
	</tr>
</table>

<?php 
$design->imprimir_fim();
?>
