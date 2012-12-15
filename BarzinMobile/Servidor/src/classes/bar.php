<?php
include_once 'bean.php';

class Bar extends Bean {
    
	protected $id, $nome, $rua, $numero, $complemento, $bairro, $cidade, $estado, $cep, $telefone1, $telefone2, $email, $versao_cardapio;
	
	public function Categoria($id = 0, $nome = "", $rua = "", $numero = "", $complemento = "", $bairro = "", $cidade = "", $estado = "", $cep = "", $telefone1 = "", $telefone2 = "", $email = "") {
		$this->id = $id;
		$this->nome = $nome;
		$this->rua = $rua;
		$this->numero = $numero;
		$this->complemento = $complemento;
		$this->bairro = $bairro;
		$this->cidade = $cidade;
		$this->estado = $estado;
		$this->cep = $cep;
		$this->telefone1 = $telefone1;
		$this->telefone2 = $telefone2;
		$this->email = $email;
	}
	
	public function get_id() {
		return $this->id;
	}
	
	public function set_nome($nome) {
		$this->nome = $nome;
	}
	
	public function get_nome() {
		return $this->nome;
	}
	
}
	
?>