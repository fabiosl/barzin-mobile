<?php 
include_once 'design.php';
include_once 'transformavel_json.php';

class Cardapio extends transformavel_json {
    
    protected $categorias, $design, $versao;
    
    public function Cardapio($categorias = array()) {
        $this->categorias = $categorias;
        $this->design = new Design(null);
    }
    
    public function adiciona_categoria($nova_categoria) {
        $this->categorias[] = $nova_categoria;
    }
    
	public function imprimir_links_categorias() {
		foreach ($this->categorias as $categoria) {
			$categoria->imprimir_links_manipulacao();
		}
	}
	
	public function imprimir_itens_manipulacao() {
		foreach ($this->categorias as $categoria) {
			$linhas = array();
			$categoria->carregar_manipulacao_itens($linhas);
			$larguras = array('', 1, 1);
			$this->design->imprimir_tabela(array(), $linhas, $larguras);
			echo "<br/>";
		}		
	}
    
	public function get_versao() {
		return $this->versao;
	}
	
	public function set_versao($versao) {
		$this->versao = $versao;
	}
}

?>