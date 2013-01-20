<?php
include_once("requisicoes.php");
include_once("sessao.php");

class Layout {

	private static $transicao = "flip";
	
	public static function imprimir_header($nome_bar, $nome_mesa, $titulo) {
		echo "
		 <div data-role=\"header\" data-position=\"fixed\">
			<table border=\"0\" width=\"100%\">
				<tr valign=\"middle\">
					<td style=\"white-space: nowrap; width: 1%; text-align: left; font-size: 0.7em;\">
						<a href=\"#\" data-role=\"button\" data-inline=\"true\" class=\"chamar_garcom\">Chamar<br/>Garçom</a>
					</td>
					<td align=\"center\" width=\"98%\">
						<div style=\"font-size: 0.7em;\">$nome_bar</div>
						$nome_mesa
				        <div style=\"font-size: 0.8em;\">$titulo</div>
					</td>
					<td style=\"white-space: nowrap; width: 1%; text-align: right; font-size: 0.7em;\">
						<a href=\"sair.php\" data-role=\"button\" data-inline=\"true\" data-ajax=\"false\" class=\"sair\">Sair</a>
					</td>
	        	</tr>
        	</table>
		 </div>
		";
	}

	public static function imprimir_footer($item_ativo) {
		echo "
		 <div data-theme=\"b\" data-role=\"footer\" data-position=\"fixed\">
	        <div data-role=\"navbar\" data-iconpos=\"none\">
		        <ul>
		            <li>
		                <a href=\"#cardapio\" data-transition=\"".self::$transicao."\" class=\"nao-pessoas".($item_ativo == "cardapio" ? " ui-btn-active ui-state-persist" : "")."\">
		                    Cardápio
		                </a>
		            </li>
		            <li>
		                <a href=\"#pessoas\" data-transition=\"".self::$transicao."\"".($item_ativo == "pessoas" ? " class=\"ui-btn-active ui-state-persist\"" : "").">
		                    Pessoas
		                </a>
		            </li>
		            <li>
		                <a href=\"#conta\" id=\"botao_conta_footer\" data-transition=\"".self::$transicao."\" class=\"nao-pessoas".($item_ativo == "conta" ? " ui-btn-active ui-state-persist" : "")."\">
		                    Conta
		                </a>
		            </li>
		        </ul>
		    </div>
	     </div>
		";
	}
	
}