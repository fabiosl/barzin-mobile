<?php 
include_once 'transformavel_json.php';

class Erro extends transformavel_json {
	protected $erro;
	
	public function Erro($erro = "Erro") {
		$this->erro = $erro;
	}
	
	public function get_erro() {
		return $this->erro;
	}
}

?>