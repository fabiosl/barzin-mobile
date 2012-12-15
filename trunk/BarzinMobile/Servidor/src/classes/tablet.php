<?php
include_once 'bean.php';

class Tablet extends Bean {
    
	protected $id, $bar_id, $nome, $disponivel;
	
	public function Tablet($nome = "", $bar_id = 0, $id = 0, $disponivel = 1) {
		$this->nome = $nome;
		$this->bar_id = $bar_id;
		$this->id = $id;
		$this->disponivel = $disponivel;
	}
	
	public function get_id() {
		return $this->id;
	}
	
	public function get_bar_id() {
		return $this->bar_id;
	}
	
	public function set_bar_id($bar_id) {
		$this->bar_id = $bar_id;
	}
	
	public function get_nome() {
		return $this->nome;
	}
	
	public function set_nome($nome) {
		$this->nome = $nome;
	}
	
	public function set_disponivel($disponivel) {
		$this->disponivel = $disponivel;
	}
	
	public function get_disponivel() {
		return $this->disponivel;
	}
}
	
?>