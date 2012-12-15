<?php

include_once 'bean.php';

class Pedido extends Bean {
	
	// $data_hora será um timestamp
    protected $id, $item_id, $conta_id, $quantidade, $data_hora, $estado, $hora_formatado;
    
    public function Pedido($item_id = -1, $quantidade = 0, $estado = "Pendente", $id = -1, $conta_id = -1, $data_hora = 0) {
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
    }
    
    public function get_data_hora_mysql() {
    	return date("Y-m-d H:i:s", $this->data_hora);
    }
    
    public function get_data_hora_formatado() {
    	return date("d/m/Y H:i:s", $this->data_hora);
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
    
    public function get_conta_id() {
    	return $this->conta_id;
    }
    
    public function get_quantidade() {
    	return $this->quantidade;
    }
    
    public function set_quantidade($quantidade) {
    	$this->quantidade = $quantidade;
    }
    
    public function get_data_hora() {
    	return $this->data_hora;
    }
    
    public function set_data_hora($data_hora) {
    	$this->data_hora = $data_hora;
    }
    
    public function get_estado() {
    	return $this->estado;
    }
    
    public function set_estado($estado) {
    	$this->estado = $estado;
    }
    
    public function ha_quanto_tempo_foi_feito() {
    	$retorno = "";
    	$segundos = time() - $this->data_hora;
   		$minutos = $segundos / 60;
   		$segundos = $segundos % 60;
		$horas = (int) ($minutos / 60);
		$minutos = $minutos % 60;
		$separador = "";
		if ($horas > 0) {
			$retorno .= $separador.$horas." h";
			$separador = ", ";
		}
		if ($minutos > 0) {
			$retorno .= $separador.$minutos." min";
			$separador = ", ";
		}
		if ($segundos > 0) {
			$retorno .= $separador.$segundos." seg";
			$separador = ", ";
		}
		return $retorno;
    }
    
    public function cor_fundo() {
    	$segundos = time() - $this->data_hora;
    	$minutos = (int) ($segundos / 60);
    	if ($minutos >= 30) {
    		return "#ff4242";
    	}
    	elseif ($minutos >= 20) {
    		return "#ffcc7c";
    	}
    	elseif ($minutos >= 10) {
    		return "#f7ff7c";
    	}
    	return "#c3ff7c";
    }
    
    public function set_hora_formatado() {
    	$this->hora_formatado = date("H:i:s", $this->data_hora);
    }
}



?>