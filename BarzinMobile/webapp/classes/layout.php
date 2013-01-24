<?php
include_once("requisicoes.php");
include_once("sessao.php");

class Layout {

	private static $transicao = "flip";
	
	public static function imprimir_header($item_ativo) {
		echo "
	 	 <div data-theme=\"b\" data-role=\"header\" data-position=\"fixed\" class=\"header\">
			<div data-role=\"navbar\" data-iconpos=\"none\">
				<ul>
					<li>
						<a href=\"#cardapio\" data-transition=\"".self::$transicao."\" id=\"botao_cardapio_header\" class=\"botao_cardapio_header nao-pessoas".($item_ativo == "cardapio" ? " ui-btn-active ui-state-persist ativo" : "")."\">
		                    <img src=\"img/cardapio_colorido.png\" alt=\"cardapio\">
		                </a>
		            </li>
		            <li>
		                <a href=\"#pessoas\" data-transition=\"".self::$transicao."\" id=\"botao_pessoas_header\" class=\"botao_pessoas_header".($item_ativo == "pessoas" ? " ui-btn-active ui-state-persist ativo" : "")."\">
		                    <img src=\"img/pessoas_colorido.png\" alt=\"cardapio\">
		                </a>
		            </li>
		            <li>
		                <a href=\"#\" id=\"botao_conta_header\" class=\"botao_conta_header nao-pessoas".($item_ativo == "conta" || $item_ativo == "pendentes" ? " ui-btn-active ui-state-persist ativo" : "")."\">
		                	<img src=\"img/small-down-arrow.png\" style=\"position: absolute; bottom: 0px; left: -10px;\">
		                    <img src=\"img/conta_colorido.png\" alt=\"cardapio\">
		                </a>
		            </li>
		            <li>
		                <a href=\"#\" id=\"botao_outros_header\" class=\"botao_outros_header nao-pessoas\">
		                	<img src=\"img/small-down-arrow.png\" style=\"position: absolute; bottom: 0px; left: -10px;\">
		                    <img src=\"img/outros.png\" alt=\"cardapio\">
		                </a>
		            </li>
		        </ul>
		    </div>

		    <div data-role=\"navbar\" data-iconpos=\"none\" class=\"sub_menu_conta\" style=\"display: hidden;\">
				<ul>
					<li>
						<a href=\"#pendentes\" data-theme=\"e\" data-transition=\"".self::$transicao."\" class=\"botao_pendentes_subheader ".($item_ativo == "pendentes" ? " ui-btn-active ui-state-persist sub-menu-ativo" : "")."\">
		                    Pedidos Pendentes
		                </a>
		            </li>
		            <li>
		                <a href=\"#conta\" data-theme=\"e\" data-transition=\"".self::$transicao."\" class=\"botao_conta_subheader ".($item_ativo == "conta" ? " ui-btn-active ui-state-persist sub-menu-ativo" : "")."\">
		                    Conta
		                </a>
		            </li>
		        </ul>
		    </div>

		    <div data-role=\"navbar\" data-iconpos=\"none\" class=\"sub_menu_outros\" style=\"display: none;\">
				<ul>
					<li>
						<a href=\"#\" data-theme=\"e\" class=\"botao_garcom_subheader chamar_garcom\">
		                    Garçom
		                </a>
		            </li>
		            <li>
		                <a href=\"#\" data-theme=\"e\" class=\"pedir_conta\">
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
	     </div>
		";
	}

	// public static function imprimir_footer($item_ativo) {
	// 	echo "
	// 	 <div data-theme=\"b\" data-role=\"footer\" data-position=\"fixed\">
	//         <div data-role=\"navbar\" data-iconpos=\"none\">
	// 	        <ul>
	// 	            <li>
	// 	                <a href=\"#cardapio\" data-transition=\"".self::$transicao."\" class=\"nao-pessoas".($item_ativo == "cardapio" ? " ui-btn-active ui-state-persist" : "")."\">
	// 	                    Cardápio
	// 	                </a>
	// 	            </li>
	// 	            <li>
	// 	                <a href=\"#pessoas\" data-transition=\"".self::$transicao."\"".($item_ativo == "pessoas" ? " class=\"ui-btn-active ui-state-persist\"" : "").">
	// 	                    Pessoas
	// 	                </a>
	// 	            </li>
	// 	            <li>
	// 	                <a href=\"#conta\" id=\"botao_conta_footer\" data-transition=\"".self::$transicao."\" class=\"nao-pessoas".($item_ativo == "conta" ? " ui-btn-active ui-state-persist" : "")."\">
	// 	                    Conta
	// 	                </a>
	// 	            </li>
	// 	        </ul>
	// 	    </div>
	//      </div>
	// 	";
	// }
	
}