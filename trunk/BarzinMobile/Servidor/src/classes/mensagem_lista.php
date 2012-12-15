<?php
include_once 'transformavel_json.php';

Class Mensagem_lista extends Transformavel_json {
	
	protected $lista;
	
	public function Mensagem_lista() {
		$this->lista = array();
	}
	
	public function adicionar($msg) {
		$this->lista[] = $msg;
	}
	
}