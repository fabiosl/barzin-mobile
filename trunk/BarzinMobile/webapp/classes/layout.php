<?php
include_once("requisicoes.php");
include_once("sessao.php");

class Layout {

	private static $transicao = "flip";
	
	public static function imprimir_header($titulo, $nome_mesa, $nome_bar, $codigo_mesa) {
		echo "
		 <div data-role=\"header\" data-position=\"fixed\">
			<table border=\"0\" width=\"100%\">
				<tr valign=\"middle\">
					<td style=\"white-space: nowrap; width: 1%; text-align: left; font-size: 0.7em; padding-left: 10px;\">
						<div style=\"font-size: 0.8em;\">$nome_bar</div>
						$nome_mesa
				        <div style=\"font-size: 0.8em;\">Código: $codigo_mesa</div>
					</td>
					<td align=\"center\" width=\"98%\">
				        <div style=\"font-size: 1.2em; line-height: 0.8em;\">$titulo</font>
					</td>
					<td style=\"text-align: right; padding-right: 10px;\">
						<a href=\"http://barzin.me\" target=\"_blank\">
							<img src=\"img/logo2.png\"/>
						</a>
					</td>
	        	</tr>
        	</table>
		 </div>
		";
	}

	public static function imprimir_footer($item_ativo) {
		echo "
	 	 <div data-theme=\"b\" data-role=\"footer\" data-position=\"fixed\" class=\"footer\">
	 	 	<div data-role=\"navbar\" data-iconpos=\"none\" class=\"sub_menu_conta\" style=\"display: hidden;\">
				<ul>
					<li>
						<a href=\"#pendentes\" data-theme=\"e\" data-transition=\"".self::$transicao."\" class=\"botao_pendentes_subfooter ".($item_ativo == "pendentes" ? " ui-btn-active ui-state-persist sub-menu-ativo" : "")."\">
		                    Pedidos Pendentes
		                </a>
		            </li>
		            <li>
		                <a href=\"#conta\" data-theme=\"e\" data-transition=\"".self::$transicao."\" class=\"botao_conta_subfooter ".($item_ativo == "conta" ? " ui-btn-active ui-state-persist sub-menu-ativo" : "")."\">
		                    Conta
		                </a>
		            </li>
		        </ul>
		    </div>

		    <div data-role=\"navbar\" data-iconpos=\"none\" class=\"sub_menu_outros\" style=\"display: none;\">
				<ul>
					<li>
						<a href=\"#\" data-theme=\"e\" class=\"botao_garcom_subfooter chamar_garcom\">
		                    Garçom
		                </a>
		            </li>
		            <li>
		                <a href=\"#\" data-theme=\"e\" class=\"pedir_conta nao-pessoas\">
		                    Pedir Conta
		                </a>
		            </li>
		            <li>
		                <a href=\"sair.php\" data-theme=\"e\" data-inline=\"true\" data-ajax=\"false\" class=\"sair\">
		                    Sair
		                </a>
		            </li>
		        </ul>
		    </div>

			<div data-role=\"navbar\" data-iconpos=\"none\">
				<ul>
					<li>
						<a href=\"#cardapio\" data-transition=\"".self::$transicao."\" id=\"botao_cardapio_footer\" class=\"botao_cardapio_footer nao-pessoas".($item_ativo == "cardapio" ? " ui-btn-active ui-state-persist ativo" : "")."\">
		                    <img src=\"img/cardapio_colorido.png\" alt=\"cardapio\">
		                </a>
		            </li>
		            <li>
		                <a href=\"#pessoas\" data-transition=\"".self::$transicao."\" id=\"botao_pessoas_footer\" class=\"botao_pessoas_footer".($item_ativo == "pessoas" ? " ui-btn-active ui-state-persist ativo" : "")."\">
		                    <img src=\"img/pessoas_colorido.png\" alt=\"cardapio\">
		                </a>
		            </li>
		            <li>
		                <a href=\"#\" id=\"botao_conta_footer\" class=\"botao_conta_footer nao-pessoas".($item_ativo == "conta" || $item_ativo == "pendentes" ? " ui-btn-active ui-state-persist ativo" : "")."\">
		                	<img src=\"img/small-up-arrow.png\" style=\"position: absolute; top: -10px; left: -10px;\">
		                    <img src=\"img/conta_colorido.png\" alt=\"cardapio\">
		                </a>
		            </li>
		            <li>
		                <a href=\"#\" id=\"botao_outros_footer\" class=\"botao_outros_footer\">
		                <img src=\"img/small-up-arrow.png\" style=\"position: absolute; top: -10px; left: -10px;\">
		                    <img src=\"img/outros.png\" alt=\"cardapio\">
		                </a>
		            </li>
		        </ul>
		    </div>
	     </div>
		";
	}
	
}