<?php 
include_once 'transformavel_json.php';

class Bean extends transformavel_json {
	
	public function setar_atributos_consulta($consulta, $atributos_ignorar = array()) {
		$array = mysql_fetch_assoc($consulta);
		foreach ($array as $atributo => $valor) {
			if (!in_array($atributo, $atributos_ignorar)) {
				$this->$atributo = $valor;
			}
		}
	}
		
}

?>