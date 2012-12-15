<?php

include_once 'bean.php';
include_once 'pedido.php';

class Conta extends Bean {
	
	// $data_hora_abertura e $data_hora_abertura serão timestamps
    protected $id, $tablet_id, $estado, $data_hora_abertura, $data_hora_fechamento, $pedidos, $total;
    
    public function Conta($tablet_id = -1, $estado = "Aberta", $data_hora_abertura = 0, $data_hora_fechamento = 0, $id = -1, $pedidos = array(), $total = 0) {
    	$this->id = $id;
    	$this->tablet_id = $tablet_id;
    	$this->estado = $estado;
    	if ($data_hora_abertura == 0) {
    		$this->data_hora_abertura = time();
    	}
    	else {
    		$this->data_hora_abertura = $data_hora_abertura;
    	}
    	$this->data_hora_fechamento = $data_hora_fechamento;
    	$this->pedidos = $pedidos;
    	$this->total = $total;
    }

    public function adicionar_pedido($pedido) {
    	$this->pedidos[] = $pedido;
    }
    
    public function get_data_hora_abertura_mysql() {
    	return date("Y-m-d H:i:s", $this->data_hora_abertura);
    }
    
    public function get_data_hora_abertura_formatado() {
    	return date("d/m/Y - H:i", $this->data_hora_abertura);
    }

    public function get_hora_abertura() {
    	return date("H:i:s", $this->data_hora_abertura);
    }
    
    public function get_data_hora_fechamento_mysql() {
    	return date("Y-m-d H:i:s", $this->data_hora_fechamento);
    }
    
    public function get_data_hora_fechamento_formatado() {
    	if ($this->data_hora_fechamento <= 10800) {
    		return "-";
    	}
    	return date("d/m/Y - H:i", $this->data_hora_fechamento);
    }
    
    public function get_hora_fechamento() {
    	return date("H:i:s", $this->data_hora_fechamento);
    }
    
    public function set_data_hora_fechamento($data_hora_fechamento) {
    	 $this->data_hora_fechamento = $data_hora_fechamento;
    }
    
    public function get_id() {
    	return $this->id;
    }
    
    public function get_tablet_id() {
    	return $this->tablet_id;
    }
    
    public function get_estado() {
    	return $this->estado;
    }
    
    public function set_estado($estado) {
    	$this->estado = $estado;
    }
    
    public function get_data_hora_abertura() {
    	return $this->data_hora_abertura;
    }
    
    public function get_data_hora_fechamento() {
    	return $this->data_hora_fechamento;
    }
    
    public function set_total($total) {
    	$this->total = $total;
    }
    
    public function get_total_formatado() {
    	return sprintf("R$ %.2f", $this->total);
    }
    
    public function get_pedidos() {
    	return $this->pedidos;
    }
    
}



?>