<?php

include_once 'bean.php';
include_once 'design.php';

class Item extends Bean {
	
    protected $nome, $descricao, $preco, $id, $disponivel, $categoria_id, $design, $pasta_raiz, $passado;
    
    public function Item($nome = "", $descricao = "", $preco = 0, $id = -1, $disponivel = TRUE, $categoria_id = NULL, $passado = 0, $pasta_raiz = "..") {
        $this->nome = $nome;
        $this->descricao = $descricao;
        $this->preco = $preco;
        $this->id = $id;
        $this->disponivel = $disponivel;
        $this->categoria_id = $categoria_id;
        $this->design = new Design($pasta_raiz);
        $this->pasta_raiz = $pasta_raiz;
        $this->passado = $passado;
    }
    
    public function get_preco_formatado() {
    	return sprintf("R$ %.2f", $this->preco);
    }
    
    public function get_nome() {
    	return $this->nome;
    }
    
    public function get_disponivel() {
    	return $this->disponivel;
    }
    
    public function get_descricao() {
    	return $this->descricao;
    }
    
    public function get_id() {
    	return $this->id;
    }
    
    public function get_preco() {
    	return $this->preco;
    }
    
    public function get_categoria_id() {
    	return $this->categoria_id;
    }

    public function set_nome($nome) {
    	$this->nome = $nome;
    }
    
    public function set_descricao($descricao) {
    	$this->descricao = $descricao;
    }
    
    public function set_preco($preco) {
    	$this->preco = $preco;
    }
    
    public function set_id($id) {
    	$this->id = $id;
    }
    
    public function set_disponivel($disponivel) {
    	$this->disponivel = $disponivel;
    }
    
    public function set_categoria_id($categoria_id) {
    	$this->categoria_id = $categoria_id;
    }
    
    public function get_thumb() {
    	if ($this->tem_imagem()) {
    		return $this->design->get_imagem('itens/'.$this->id.'p.jpg', 'Foto: '.$this->get_nome());
    	}
    	return $this->design->get_imagem('itens/semfotop.jpg', 'Foto: '.$this->get_nome());
    }
    
    public function get_endereco_thumb() {
    	if ($this->tem_imagem()) {
    		return $this->design->get_endereco_imagem('itens/'.$this->id.'p.jpg');
    	}
    	return $this->design->get_endereco_imagem('itens/semfotop.jpg');
    }
    
    public function get_imagem() {
    	if ($this->tem_imagem()) {
    		return $this->design->get_imagem('itens/'.$this->id.'g.jpg', 'Foto: '.$this->get_nome());
    	}
    	return $this->design->get_imagem('itens/semfotog.jpg', 'Foto: '.$this->get_nome());
    }
    
    public function get_endereco_imagem() {
    	if ($this->tem_imagem()) {
    		return $this->design->get_endereco_imagem('itens/'.$this->id.'g.jpg');
    	}
    	return $this->design->get_endereco_imagem('itens/semfotog.jpg');
    }
    
    public function set_pasta_raiz($pasta_raiz) {
    	$this->pasta_raiz = $pasta_raiz;
    	$this->design = new Design($pasta_raiz);
    }
    
    public function get_passado() {
    	return $this->passado;
    }
    
    public function set_passado($passado) {
    	$this->passado = $passado;
    }
    
    public function tem_imagem() {
    	return (file_exists($this->pasta_raiz.'/img/itens/'.$this->id.'p.jpg') ||
    			file_exists($this->pasta_raiz.'/img/itens/'.$this->id.'g.jpg'));
    }
}



?>