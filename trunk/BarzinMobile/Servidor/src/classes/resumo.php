<?php

class Resumo {
	
	public $item, $lista_pedidos;
	
	public function Resumo() {
		$this->lista_pedidos = array();
	}
	
	public function get_item() {
		return $this->item;
	}
	
	public function set_item($item) {
		$this->item = $item;
	}
	
	public function get_lista_pedidos() {
		return $this->lista_pedidos;
	}
	
	public function adicionar_pedido($pedido) {
		$this->lista_pedidos[] = $pedido;
	}
	
}