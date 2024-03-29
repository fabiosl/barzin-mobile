<?php

include_once 'bean.php';

class Pedido extends Bean {
	
	// $data_hora será um timestamp
    protected $id, $item_id, $conta_id, $quantidade, $data_hora, $estado, $hora_formatado, $pessoas, $data_hora_solicitacao_cancelamento;
    
    public function Pedido($item_id = -1, $quantidade = 0, $comentario = "", $pessoas = array(), $estado = "Pendente", $id = -1, $conta_id = -1, $data_hora = 0) {
    	$this->id = $id;
    	$this->item_id = $item_id;
    	$this->conta_id = $conta_id;
    	$this->quantidade = $quantidade;
    	if ($data_hora == 0) {
    		$this->data_hora = time();
    	}
    	else {
    		$this->data_hora = $data_hora;
    	}
    	$this->hora_formatado = date("H:i:s", $this->data_hora);
    	$this->estado = $estado;
    	$this->pessoas = $pessoas;
    	$this->comentario = $comentario;
    }
    
    public function adiciona_pessoa($nova_pessoa) {
    	$this->pessoas[] = $nova_pessoa;
    }
    
    public function get_comentario() {
    	return $this->comentario;
    }
    
    public function get_conta_id() {
    	return $this->conta_id;
    }
    
    public function get_data_hora() {
    	return $this->data_hora;
    }
    
    public function get_data_hora_formatado() {
    	return date("d/m/Y H:i:s", $this->data_hora);
    }

    public function get_data_hora_mysql() {
    	return date("Y-m-d H:i:s", $this->data_hora);
    }
    
    public function get_data_hora_solicitacao_cancelamento() {
    	return $this->data_hora_solicitacao_cancelamento;
    }
    
    public function get_data_hora_solicitacao_cancelamento_mysql() {
    	return date("Y-m-d H:i:s", $this->data_hora_solicitacao_cancelamento);
    }
    
    public function get_estado() {
    	return $this->estado;
    }
    
    public function get_hora() {
    	return date("H:i:s", $this->data_hora);
    }

    public function get_id() {
    	return $this->id;
    }
    
    public function get_item_id() {
    	return $this->item_id;
    }
    
    public function get_quantidade() {
    	return $this->quantidade;
    }
    
    public function get_pessoas() {
    	return $this->pessoas;
    }
    
    public function set_comentario($comentario) {
    	$this->comentario = $comentario;
    }
    
    public function set_conta_id($conta_id) {
    	$this->conta_id = $conta_id;
    }
    
    public function set_data_hora($data_hora) {
    	$this->data_hora = $data_hora;
    }
    
    public function set_data_hora_solicitacao_cancelamento($data_hora_solicitacao_cancelamento) {
    	$this->data_hora_solicitacao_cancelamento = $data_hora_solicitacao_cancelamento;
    }
    
    public function set_estado($estado) {
    	$this->estado = $estado;
    }
    
    public function set_hora_formatado() {
    	$this->hora_formatado = date("H:i:s", $this->data_hora);
    }
    
    public function set_id($id) {
    	$this->id = $id;
    }
    
    public function set_quantidade($quantidade) {
    	$this->quantidade = $quantidade;
    }
    
    public function set_pessoas($pessoas) {
    	$this->pessoas = $pessoas;
    }
    
}



?>