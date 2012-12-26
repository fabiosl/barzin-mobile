<?php
require 'verifica.php';
include_once 'classes/design.php';
include_once 'classes/dao.php';
include_once 'classes/bar.php';
include_once 'classes/pedido.php';

$design = new Design(".");
$design->imprimir_topo();

$banco = new DAO();

$login_usuario = $_SESSION["usuario_logado"];
$bar = $banco->recupera_bar_pelo_login($login_usuario);

$ultima_atualizacao = $banco->recupera_ultima_atualizacao_pedidos($bar->get_id());

echo "
	<script type=\"text/javascript\">
		var inicio_javascript = Math.round((new Date()).getTime() / 1000);	
	
		function atualizarTudo() {
			fazerARequisicao();
			reconstruirBlocos();
		}
		window.setInterval(\"atualizarTudo()\", 10000);

		function fazerARequisicao() {
			xmlhttp.open(\"GET\", \"scripts/pedidos/ultima_atualizacao_pedidos.php?bar_id=".$bar->get_id()."&dummy=\" + new Date().getTime(), true);
			xmlhttp.onreadystatechange = function () {
				if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
					if (xmlhttp.responseText != \"".$ultima_atualizacao."\") {
						window.location.reload();
					}
				}
			};
			xmlhttp.send(null);
			return false;
		}
		
		function reconstruirBlocos() {
			$('.bloco').each(function() {
				var diferenca_servidor = ".time()." - $(this).children('.data_hora').text();
				var diferenca_javascript = Math.round((new Date()).getTime() / 1000) - inicio_javascript;
				var diferenca_em_segundos = diferenca_servidor + diferenca_javascript;
				
				var tempo_total = 30 * 60; // 30 minutos
				
				var verde = 0;
				var vermelho = 0;
				var azul = 0;
				if (diferenca_em_segundos <= tempo_total / 2) {
					// começa com verde claro (rgb(162, 255, 164))
					// vai variando até laranja +- claro (rgb(255, 180, 73))
					vermelho = 162 + Math.round((93 * diferenca_em_segundos) / (tempo_total / 2));
					if (vermelho > 255) {
						vermelho = 255;
					}
					verde = 255 - Math.round((75 * diferenca_em_segundos) / (tempo_total / 2));
					azul = 164 - Math.round((91 * diferenca_em_segundos) / (tempo_total / 2));
				}
				else {
					// começa com laranja +- claro (rgb(255, 180, 73))
					// vai variando até vermelho (rgb(255, 0, 0))
					vermelho = 255;
					verde = Math.round((180 * (tempo_total - diferenca_em_segundos)) / (tempo_total / 2));
					if (verde < 0) {
						verde = 0;
					}
					azul = Math.round((73 * (tempo_total - diferenca_em_segundos)) / (tempo_total / 2));
					if (azul < 0) {
						azul = 0;
					}
				}
				
				var nova_cor = colorToHex('rgb(' + vermelho + ', ' + verde + ', ' + azul + ')');
				
				$(this).attr('bgcolor', nova_cor);
				$(this).children('.quanto_tempo').html('<b>Há ' + quanto_tempo(diferenca_em_segundos) + '</b>');
			});
		}
		
		function quanto_tempo(segundos) {
			var minutos = Math.floor(segundos / 60);
   			var segundos = segundos % 60;
			var horas = Math.floor(minutos / 60);
			var minutos = minutos % 60;
			var retorno = \"\";
			var separador = \"\";
			if (horas > 0) {
				retorno = separador + horas + \" h\";
				separador = \", \";
			}
			if (minutos > 0) {
				retorno = retorno + separador + minutos + \" min\";
				separador = \", \";
			}
			if (segundos > 0) {
				retorno = retorno + separador + segundos + \" seg\";
			}
			return retorno;
		}
		
		function colorToHex(color) {
		    if (color.substr(0, 1) === '#') {
		        return color;
		    }
		    var digits = /(.*?)rgb\((\d+), (\d+), (\d+)\)/.exec(color);
		    
		    var red = parseInt(digits[2]);
		    var green = parseInt(digits[3]);
		    var blue = parseInt(digits[4]);
		    
		    var rgb = blue | (green << 8) | (red << 16);
		    return digits[1] + '#' + rgb.toString(16);
		};
		
		$(function() {
			$('.marcar_atendido').click(function() {
				$.post(
					'scripts/pedidos/atender_pedido.php', 
					{pedido_id: $(this).children('div').text()},
					function() {
						window.location.reload();
					} 
				);
			});
			
			$('.confirmar_cancelamento').click(function() {
				$.post(
					'scripts/pedidos/confirmar_cancelamento.php', 
					{pedido_id: $(this).children('div').text()},
					function() {
						window.location.reload();
					} 
				);
			});
			
			$('.negar_cancelamento').click(function() {
				$.post(
					'scripts/pedidos/negar_cancelamento.php', 
					{pedido_id: $(this).children('div').text()},
					function() {
						window.location.reload();
					} 
				);
			});
			
			reconstruirBlocos();
		});
	</script>
";

$cancelamentos_solicitados = $banco->recupera_pedidos_com_cancelamento_solicitado($bar->get_id());

if (count($cancelamentos_solicitados) > 0) {
	echo "<h1>Cancelamentos Soliticados</h1>";
	echo "<table border=\"1\">";
	$contador = 3;
	foreach ($cancelamentos_solicitados as $pedido) {
		if ($contador == 3) {
			echo "<tr valign=\"top\">";
			$contador = 0;
		}
		$contador++;
		$item = $banco->recupera_item($pedido->get_item_id());
		$mesa = $banco->recupera_mesa_pela_conta($pedido->get_conta_id());
		echo "
				 <td width=\"33%\" align=\"center\" valign=\"middle\" style=\"color: #ffffff; background-color: #000000;\" class=\"bloco\">
					 <b><font size=\"+2\">$mesa</font></b><br/>
					 Item: <b>".$item->get_nome()."</b><br/>
					 Quantidade: <b>".$pedido->get_quantidade()."</b><br/>
					 <span class=\"quanto_tempo\"></span><br/>
				";
		if ($pedido->get_comentario() != "") {
		echo "Comentário: <b>".$pedido->get_comentario()."</b><br/>";
		}
		echo "
					 <div style=\"display: none;\" class=\"data_hora\">".$pedido->get_data_hora_solicitacao_cancelamento()."</div>
					 <button type=\"button\" class=\"confirmar_cancelamento\"><font color=\"#009240\">Confirmar Cancelamento</font><div style=\"display: none\">".$pedido->get_id()."</div></button>
					 <button type=\"button\" class=\"negar_cancelamento\"><font color=\"#ff0000\">Negar Cancelamento</font><div style=\"display: none\">".$pedido->get_id()."</div></button>
				 </td>
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

echo "<h1>Controle de Pedidos</h1>";

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
		$mesa = $banco->recupera_mesa_pela_conta($pedido->get_conta_id());
		echo "
		 <td width=\"33%\" align=\"center\" valign=\"middle\" class=\"bloco\">
			 <b><font size=\"+2\">$mesa</font></b><br/>
			 Item: <b>".$item->get_nome()."</b><br/>
			 Quantidade: <b>".$pedido->get_quantidade()."</b><br/>
			 <span class=\"quanto_tempo\"></span><br/>
		";
		if ($pedido->get_comentario() != "") {
			echo "Comentário: <b>".$pedido->get_comentario()."</b><br/>";
		}
		echo "
			 <div style=\"display: none;\" class=\"data_hora\">".$pedido->get_data_hora()."</div>
			 <button type=\"button\" class=\"marcar_atendido\">Marcar atendido<div style=\"display: none\">".$pedido->get_id()."</div></button>
		 </td>
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