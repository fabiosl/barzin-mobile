<?php 
class Requisicoes {
	// Online no JPRibeiro.com
	// const raiz_backend = "http://jpribeiro.com/barzin/scripts/", raiz_frontend = "http://jpribeiro.com/barzin/scripts/";
	
	// Local
	const raiz_backend = "http://192.168.1.2:8888/Barzin/BarzinMobile/Servidor/src/scripts/", raiz_frontend = "http://192.168.1.2:8888/Barzin/BarzinMobile/Servidor/src/scripts/";
	
	public static function fazer_requisicao($endereco, $dados = array()) {
		$ch = curl_init();
		// informar URL e outras funções ao CURL
		curl_setopt($ch, CURLOPT_URL, self::raiz_backend . $endereco);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		// Faz um POST
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $dados);
		// Acessar a URL e retornar a saída
		$output = curl_exec($ch);
		// liberar
		curl_close($ch);
		
		return $output;
	}
}
?>