<?php
include_once 'bean.php';
include_once 'design.php';

class Categoria extends Bean {
    
	protected $nome, $subcategorias, $itens, $id, $categoria_mae_id, $bar_id;
	private $design;
	
	public function Categoria($nome = "", $subcategorias = array(), $itens = array(), $id = -1, $pastaRaiz = "../..") {
		$this->nome = $nome;
		$this->subcategorias = $subcategorias;
		$this->itens = $itens;
		$this->design = new Design($pastaRaiz);
		$this->id = $id;
	}
	
	public function adiciona_item($novo_item) {
		$this->itens[] = $novo_item;
	}
	
	public function adiciona_subcategoria($nova_subcategoria) {
		$this->subcategorias[] = $nova_subcategoria;
	}
	
	public function get_nome() {
		return $this->nome;
	}
	
	public function set_nome($nome) {
		$this->nome = $nome;
	}

	public function get_id() {
		return $this->id;
	}
	
	public function carregar_manipulacao(&$array, $prefixo = '') {
		$id_categoria = $this->id;
		$nome_categoria = $this->nome;
		$linha = array("<div id=\"categoria_$id_categoria\">
							$prefixo
							<div id=\"nome_categoria_$id_categoria\" style=\"display: inline-block;\">
								<b>$nome_categoria</b><br/>
								<div id=\"link_nova_subcategoria_$id_categoria\" style=\"display: inline;\">+ <a href=\"javascript: void(0);\" onclick=\"prepararNovaSubcategoria($id_categoria, '$nome_categoria')\">Nova subcategoria</a></div>
								<form id=\"form_nova_subcategoria_$id_categoria\" action=\"\" onsubmit=\"return criarSubcategoria($id_categoria);\" style=\"display: none\">
									Nova subcategoria*: <input type=\"text\" size=\"10\" name=\"nome\" class=\"Nome da subcategoria|Obrig\"/>
									<button type=\"submit\">OK</button>
									<button type=\"button\" onclick=\"cancelarNovaSubcategoria($id_categoria)\">Cancelar</button>
								</form>
								</div>
								<div id=\"carregando_$id_categoria\" style=\"display: none;\">Carregando...</div>
							</div><br/>
			 			</div>
			 			<div id=\"form_categoria_$id_categoria\" style=\"display: none;\">
							$prefixo
							<div style=\"display: inline-block;\">
								<form id=\"form_editar_categoria_$id_categoria\" action=\"\" onsubmit=\"return alterarCategoria($id_categoria);\">
									Alterar categoria*: <input type=\"text\" size=\"10\" name=\"nome\" class=\"Nome da categoria|Obrig\"/>
									<button type=\"submit\">OK</button>
									<button type=\"button\" onclick=\"cancelarEditarCategoria($id_categoria, '$nome_categoria')\">Cancelar</button>
								</form>
							</div>
			 			</div>
			 			", "
				 		<div id=\"link_editar_categoria_$id_categoria\">
					 		<a href=\"javascript: void(0);\" onclick=\"prepararEditarCategoria($id_categoria, '$nome_categoria')\">Editar</a>
			 			</div>
			 			", "
						<div id=\"link_excluir_categoria_$id_categoria\">
							<a href=\"javascript: void(0);\" onclick=\"prepararExcluirCategoria($id_categoria, '$nome_categoria', ".count($this->subcategorias).", ".$this->get_numero_itens().")\">Excluir</a>
						</div>");
		$array[] = $linha;
		$prefixo = $prefixo.'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
		foreach ($this->subcategorias as $subcategoria) {
			$subcategoria->carregar_manipulacao($array, $prefixo);
		}
	}
	
	public function get_numero_itens() {
// 		$soma = count($this->itens);
// 		foreach ($this->subcategorias as $subcategoria) {
// 			$soma += count($subcategoria->itens);
// 		}
// 		return $soma;
		return count($this->itens);
	}
	
	public function get_categoria_mae_id() {
		return $this->categoria_mae_id;
	}
	
	public function set_categoria_mae_id($categoria_mae_id) {
		$this->categoria_mae_id = $categoria_mae_id;
	}
	
	public function get_bar_id() {
		return $this->bar_id;
	}
	
	public function set_bar_id($bar_id) {
		$this->bar_id = $bar_id;
	}
	
	public function carregar_manipulacao_itens(&$array, $prefixo = '') {
		$id_categoria = $this->id;
		$nome_categoria = $this->nome;
		$linha = array("$prefixo
						<b>$nome_categoria</b><br/>
				 		");
		$array[] = $linha;
		$prefixo = $prefixo.'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
		foreach ($this->subcategorias as $subcategoria) {
			$subcategoria->carregar_manipulacao_itens($array, $prefixo);
		}
		foreach ($this->itens as $item) {
			$item->carregar_manipulacao($array, $prefixo);
		}
	}
	
	public function imprimir_links_manipulacao($espaco = 0) {
		echo "<a href=\"editar_categoria.php?id=".$this->id."\" style=\"margin-left: ".$espaco."px\">".$this->nome."</a> <i>(".$this->get_numero_itens()." ite".($this->get_numero_itens() == 1 ? "m" : "ns").")</i><br/>";
		$espaco += 30;
		foreach ($this->subcategorias as $subcategoria) {
			$subcategoria->imprimir_links_manipulacao($espaco);
		}
	}
	
	public function get_subcategorias() {
		return $this->subcategorias;
	}
	
	public function get_itens() {
		return $this->itens;
	}
}
?>