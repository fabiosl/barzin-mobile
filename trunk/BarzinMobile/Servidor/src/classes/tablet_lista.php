<?php
include_once 'transformavel_json.php';

Class Tablet_lista extends Transformavel_json {
	
	protected $lista;
	
	public function Tablet_lista() {
		$this->lista = array();
	}
	
	public function adicionar($tablet) {
		$this->lista[] = $tablet;
	}
	
	public function get_lista() {
		return $this->lista;
	}
	
}