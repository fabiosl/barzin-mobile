<?php
include_once 'bean.php';

class Bar extends Bean {
    
	protected $id, $nome, $rua, $numero, $complemento, $bairro, $cidade, $estado, $cep, $telefone1, $telefone2, $email, $versao_cardapio, $admin_login, $func_login;
	
	public function Bar($nome = "", $rua = "", $numero = "", $complemento = "", $bairro = "", $cidade = "", $estado = "", $cep = "", $telefone1 = "", $telefone2 = "", $email = "", $admin_login = "", $func_login = "", $id = 0) {
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
		$this->admin_login = $admin_login;
		$this->func_login = $func_login;
	}
	
	public function get_admin_login() {
		return $this->admin_login;
	}
	
	public function get_bairro() {
		return $this->bairro;
	}
	
	public function get_cep() {
		return $this->cep;
	}
	
	public function get_cidade() {
		return $this->cidade;
	}
	
	public function get_complemento() {
		return $this->complemento;
	}
	
	public function get_email() {
		return $this->email;
	}
	
	public function get_estado() {
		return $this->estado;
	}
	
	public function get_func_login() {
		return $this->func_login;
	}
	
	public function get_id() {
		return $this->id;
	}
	
	public function get_nome() {
		return $this->nome;
	}
	
	public function get_numero() {
		return $this->numero;
	}
	
	public function get_rua() {
		return $this->rua;
	}
	
	public function get_telefone1() {
		return $this->telefone1;
	}
	
	public function get_telefone2() {
		return $this->telefone2;
	}
	
	public function get_versao_cardapio() {
		return $this->versao_cardapio;
	}
	
	public function set_admin_login($admin_login) {
		$this->admin_login = $admin_login;
	}
	
	public function set_bairro($bairro) {
		$this->bairro = $bairro;
	}
	
	public function set_cep($cep) {
		$this->cep = $cep;
	}
	
	public function set_cidade($cidade) {
		$this->cidade = $cidade;
	}
	
	public function set_complemento($complemento) {
		$this->complemento = $complemento;
	}
	
	public function set_email($email) {
		$this->email = $email;
	}
	
	public function set_estado($estado) {
		$this->estado = $estado;
	}
	
	public function set_func_login($func_login) {
		$this->func_login = $func_login;
	}
	
	public function set_id($id) {
		$this->id = $id;
	}
	
	public function set_nome($nome) {
		$this->nome = $nome;
	}
	
	public function set_numero($numero) {
		$this->numero = $numero;
	}
	
	public function set_rua($rua) {
		$this->rua = $rua;
	}
	
	public function set_telefone1($telefone1) {
		$this->telefone1 = $telefone1;
	}
	
	public function set_telefone2($telefone2) {
		$this->telefone2 = $telefone2;
	}
	
	public function set_versao_cardapio($versao_cardapio) {
		$this->versao_cardapio = $versao_cardapio;
	}
	
}
	
?>