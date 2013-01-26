<?php

include_once 'bean.php';

class Mensagem extends Bean {
	
	// $data_hora será um timestamp
    protected $id, $mesa_id, $data_hora, $mensagem;
    
    public function Mensagem($mesa_id = -1, $data_hora = 0, $mensagem = "", $id = -1) {
    	$this->id = $id;
    	$this->mesa_id = $mesa_id;
        $this->mensagem = $mensagem;
    	if ($data_hora == 0) {
    		$this->data_hora = time();
    	}
    	else {
    		$this->data_hora = $data_hora;
    	}
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
    
    public function get_hora() {
    	return date("H:i:s", $this->data_hora);
    }

    public function get_id() {
    	return $this->id;
    }

    public function get_mensagem() {
        return $this->mensagem;
    }
    
    public function get_mesa_id() {
    	return $this->mesa_id;
    }
    
    public function set_data_hora($data_hora) {
    	$this->data_hora = $data_hora;
    }
    
    public function set_id($id) {
    	$this->id = $id;
    }

    public function set_mensagem($mensagem) {
        $this->mensagem = $mensagem;
    }
    
    public function set_mesa_id($mesa_id) {
    	$this->mesa_id = $mesa_id;
    }
    
}



?>