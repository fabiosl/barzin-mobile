<?php

include_once 'bean.php';

class Mensagem extends Bean {
	
	//$data_hora é um timestamp
    protected $id, $remetente_id, $remetente_nome, $destinatario_id, $mensagem, $data_hora, $entregue, $hora_formatado;
    
    public function Mensagem($remetente_id = 0, $destinatario_id = 0, $mensagem = "", $data_hora = 0, $id = 0, $entregue = 0) {
    	$this->id = $id;
    	$this->remetente_id = $remetente_id;
    	$this->destinatario_id = $destinatario_id;
    	$this->mensagem = $mensagem;
    	if ($data_hora == 0) {
    		$this->data_hora = time();
    	}
    	else {
    		$this->data_hora = $data_hora;
    	}
    	$this->hora_formatado = date("H:i:s", $this->data_hora);
    	$this->entregue = $entregue;
    }
    
    public function get_data_hora_mysql() {
    	return date("Y-m-d H:i:s", $this->data_hora);
    }
    
    public function get_data_hora_formatado() {
    	return date("d/m/Y H:i:s", $this->data_hora);
    }
    
    public function get_data_hora() {
    	return $this->data_hora;
    }
    
    public function get_hora() {
    	return date("H:i:s", $this->data_hora);
    }
    
    public function get_id() {
    	return $this->id;
    }
    
    public function get_remetente_id() {
    	return $this->remetente_id;
    }
    
    public function get_destinatario_id() {
    	return $this->destinatario_id;
    }
    
    public function get_mensagem() {
    	return $this->mensagem;
    }
    
    public function get_entregue() {
    	return $this->entregue;
    }
    
    public function set_remetente_nome($remetente_nome) {
    	$this->remetente_nome = $remetente_nome;
    }
    
    public function set_hora_formatado() {
    	$this->hora_formatado = date("H:i:s", $this->data_hora);
    }
}



?>