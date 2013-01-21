<?php
include_once 'dao.php';

class Design {
	
	private $raiz, $adicionarBody, $login_usuario;
	
	public function Design($pastaraiz, $adicionarBody = "") {
		$this->raiz = $pastaraiz;
		$this->adicionarBody = $adicionarBody;
		if (isset($_SESSION["usuario_logado"])) {
			$this->login_usuario = $_SESSION["usuario_logado"];
		} 
	}

	public function imprimir_topo() {
		echo "
		 <html>
		 <head>
		 <meta http-equiv=\"X-UA-Compatible\" content=\"IE=8\" />
		 <meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\">
		 <title>Barzin - Interação em Bares e Restaurantes!</title>
		 <link rel=\"stylesheet\" href=\"".$this->raiz."/css/colorbox.css\" type=\"text/css\">
		 <link rel=\"stylesheet\" href=\"".$this->raiz."/css/estilo.css\" type=\"text/css\">
 		 <script type=\"text/javascript\" src=\"".$this->raiz."/javascripts/jquery-1.8.3.min.js\" charset=\"utf-8\"></script>
		 <script type=\"text/javascript\" src=\"".$this->raiz."/javascripts/funcoes.js\" charset=\"utf-8\"></script>
		 <script type=\"text/javascript\">

			var _gaq = _gaq || [];
			_gaq.push(['_setAccount', 'UA-31024939-2']);
			_gaq.push(['_trackPageview']);

			(function() {
				var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
				ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
				var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
			})();

		</script>
		 </head>
		 <body ".$this->adicionarBody.">
		 <div class=\"principal\">
		";
		if (isset($this->login_usuario)) {
			$banco = new DAO();
			$bar = $banco->recupera_bar_pelo_login($this->login_usuario);
			echo "
		 	 <div class=\"logado\">
		 	 	<div style=\"font-size: 30px; font-weight: bold; text-align: left;\">".$bar->get_nome()."</div>
			 	Logado como <b>".$this->login_usuario."</b><br>
			 	<a href=\"".$this->raiz."/login/alterar_senha.php\" class=\"link_logado\">Alterar senha</a> &nbsp;&nbsp;|&nbsp;&nbsp; 
			 	<a href=\"".$this->raiz."/login/deslogar.php\" class=\"link_logado\">Deslogar</a> 
			 </div>
			";
		}
		echo "
		 <div class=\"topo\" align=\"right\">
		 	<div style=\"position: relative; width: 100%; height: 200px; background: url(".$this->raiz."/img/degrade.png) repeat-x; z-index: 0;\">
		 		<a href=\"".$this->raiz."/index.php\"><img src=\"".$this->raiz."/img/topo.png\" /></a>
		 	</div>
		 </div>
		 <div class=\"conteudo\">
		 	<div style=\"position: fixed; top: 200px; margin-left: -230px; width: 200px; text-align: right;\">
	 	 		<div id=\"textoMenu\" style=\"font-size: 14px; height: 15px; color: #ffffff; padding-bottom: 10px;\"></div>
	 	";
		if (isset($this->login_usuario)) {
			$tipo_usuario = $banco->get_tipo_usuario($this->login_usuario);
			if ($tipo_usuario == "admin") {
				$this->imprimir_menu_gerente();
			}
			else {
				$this->imprimir_menu_funcionario();
			}
		} 
		else {
			echo "
			 <a class=\"link_menu\" href=\"".$this->raiz."/index.php\"><img src=\"".$this->raiz."/img/home.png\" onMouseOver=\"menu('Página inicial')\" onMouseOut=\"menu('')\" /></a>
			 <a class=\"link_menu\" href=\"".$this->raiz."/cadastro.php\"><img src=\"".$this->raiz."/img/cadastrar.png\" onMouseOver=\"menu('Cadastrar seu bar')\" onMouseOut=\"menu('')\" /></a>
			 <a class=\"link_menu\" href=\"".$this->raiz."/faleconosco/index.php\"><img src=\"".$this->raiz."/img/contato.png\" onMouseOver=\"menu('Fale conosco')\" onMouseOut=\"menu('')\" /></a>
			 <a class=\"link_menu\" href=\"".$this->raiz."/comofunciona.php\"><img src=\"".$this->raiz."/img/ajuda.png\" onMouseOver=\"menu('Como funciona')\" onMouseOut=\"menu('')\" /></a>
			";
		}
	 	echo "
	 	 </div>
		"; 
	}
	
	public function imprimir_menu_gerente() {
		echo "
		 <a class=\"link_menu\" href=\"".$this->raiz."/index.php\"><img src=\"".$this->raiz."/img/home.png\" onMouseOver=\"menu('Página inicial')\" onMouseOut=\"menu('')\" /></a>
		 <a class=\"link_menu\" href=\"".$this->raiz."/cardapio/index.php\"><img src=\"".$this->raiz."/img/cardapio.png\" onMouseOver=\"menu('Controle do Cardápio')\" onMouseOut=\"menu('')\" /></a>
		 <a class=\"link_menu\" href=\"".$this->raiz."/mesas/index.php\"><img src=\"".$this->raiz."/img/mesa.png\" onMouseOver=\"menu('Controle de Mesas')\" onMouseOut=\"menu('')\" /></a>
		 <a class=\"link_menu\" href=\"".$this->raiz."/alterarCadastro.php\"><img src=\"".$this->raiz."/img/cadastrar.png\" onMouseOver=\"menu('Alterar informações do bar')\" onMouseOut=\"menu('')\" /></a>
		 <a class=\"link_menu\" href=\"".$this->raiz."/faleconosco/index.php\"><img src=\"".$this->raiz."/img/contato.png\" onMouseOver=\"menu('Fale conosco')\" onMouseOut=\"menu('')\" /></a>
		 <a class=\"link_menu\" href=\"".$this->raiz."/ajudaGerente.php\"><img src=\"".$this->raiz."/img/ajuda.png\" onMouseOver=\"menu('Ajuda')\" onMouseOut=\"menu('')\" /></a>
		";
	}
	
	public function imprimir_menu_funcionario() {
		echo "
		 <a class=\"link_menu\" href=\"".$this->raiz."/index.php\"><img src=\"".$this->raiz."/img/garcom.png\" onMouseOver=\"menu('Interação com Clientes')\" onMouseOut=\"menu('')\" /></a>
		 <a class=\"link_menu\" href=\"".$this->raiz."/mesas/index.php\"><img src=\"".$this->raiz."/img/mesa.png\" onMouseOver=\"menu('Controle de Mesas')\" onMouseOut=\"menu('')\" /></a>
		 <a class=\"link_menu\" href=\"".$this->raiz."/ajudaFuncionario.php\"><img src=\"".$this->raiz."/img/ajuda.png\" onMouseOver=\"menu('Ajuda')\" onMouseOut=\"menu('')\" /></a>
		";
	}
	
	public function imprimir_fim() {
		echo "
		 </div>
		 <div class=\"rodape\">
		 	<div style=\"display: inline-block;\">
		 		<img src=\"".$this->raiz."/img/drink.png\" />
		 	</div>
		 	<div style=\"display: inline-block;\">
		 		<b>Barzin - Sistema de Interação para Bares e Restaurantes - <a href=\"".$this->raiz."/faleconosco/index.php\">Entre em contato</a></b><br/>
		 		Desenvolvido por <a href=\"http://jpribeiro.com\" target=\"_blank\">João Paulo Ribeiro</a>, <a href=\"http://dsc.ufcg.edu.br/~laerte\" target=\"_blank\">Laerte Xavier</a>, <a href=\"http://raissasarmento.com\" target=\"_blank\">Raíssa Sarmento</a> e <a href=\"http://raquelguimaraes.com\" target=\"_blank\">Raquel Guimarães</a>
		 	</div>
		 </div>
		 </div>
		 </body>
		 </html>
		";
	}
	
	function imprimir_tabela($array_cabeca, $array_linhas, $array_larguras = array(), $array_alinhamentos = array()) {
		echo "<table border=1 bordercolor=\"#cccccc\" cellpadding=5 class=\"zebra\">";
		if (count($array_cabeca) > 0) {
			echo "<tr bgcolor=\"#2071c2\">";
			foreach ($array_cabeca as $cabeca)
				echo "<th><font color=\"#ffffff\">$cabeca</font></th>";
			echo "</tr>";
		}
		
		foreach ($array_linhas as $linha) {
			echo "<tr>";
			foreach($linha as $i => $elemento) {
				$largura = array_key_exists($i, $array_larguras) ? "width=\"".$array_larguras[$i]."\"" : "";
				$alinhamento = array_key_exists($i, $array_alinhamentos) ? "align=\"".$array_alinhamentos[$i]."\"" : "";
				echo "<td $largura $alinhamento>$elemento</td>";
			}
			echo "</tr>";
		}
		echo "
		 </table>
		";
	}
	
	public function get_imagem($nome_arquivo, $alt = '') {
		return "<img src=\"".$this->get_endereco_imagem($nome_arquivo)."\" alt=\"$alt\"/>"; 
	}
	
	public function get_endereco_imagem($nome_arquivo) {
		return $this->raiz."/img/".$nome_arquivo;
	}
	
}