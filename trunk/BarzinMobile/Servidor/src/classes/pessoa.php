<?php
include_once 'bean.php';

class Pessoa extends Bean {
	protected $id, $nome, $mesa_id;
	
	public function Pessoa($nome = "", $mesa_id = -1, $id = -1) {
		$this->id = $id;
		$this->mesa_id = $mesa_id;
		$this->nome = $nome;
	}
	
	public function get_id() {
		return $this->id;
	}
	
	public function get_mesa_id() {
		return $this->mesa_id;
	}
	
	public function get_nome() {
		return $this->nome;
	}
	
	public function set_mesa_id($mesa_id) {
		$this->mesa_id = $mesa_id;
	}
	
	public function set_nome($nome) {
		$this->nome = $nome;
	}
}
?>