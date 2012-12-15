<?php
include_once 'transformavel_json.php';

Class Resumo_lista extends Transformavel_json {
	
	protected $lista;
	
	public function Resumo_lista() {
		$this->lista = array();
	}
	
	public function adicionar($resumo) {
		$this->lista[] = $resumo;
	}
	
}