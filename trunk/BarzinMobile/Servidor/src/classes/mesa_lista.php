<?php
include_once 'transformavel_json.php';

Class Mesa_lista extends Transformavel_json {
	
	protected $lista;
	
	public function Mesa_lista() {
		$this->lista = array();
	}
	
	public function adicionar($mesa) {
		$this->lista[] = $mesa;
	}
	
	public function get_lista() {
		return $this->lista;
	}
	
}