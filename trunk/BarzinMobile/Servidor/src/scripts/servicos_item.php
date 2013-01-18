<?php
include_once '../classes/dao.php';
include_once '../classes/SimpleImage.php';

function excluir_foto($id) {
	$nome_arquivo_imagem = '../img/itens/'.$id.'g.jpg';
	$nome_arquivo_thumb = '../img/itens/'.$id.'p.jpg';
	$nome_temp = '../img/itens/'.$id.'t.jpg';
	
	if (file_exists($nome_temp)) {
		unlink($nome_temp);
	}
	
	if (file_exists($nome_arquivo_imagem)) {
		unlink($nome_arquivo_imagem);
		if (file_exists($nome_arquivo_thumb)) {
			unlink($nome_arquivo_thumb);
		}
	}
	
	return "ok";
}

$banco = new DAO();

$operacao = $_REQUEST["operacao"];

if ($operacao == "alterar" || $operacao == "excluir") {
	$item = $banco->recupera_item($_GET["id"]);
}
else if ($operacao == "criar") {
	$item = new Item();
}

if ($operacao == "alterar" || $operacao == "criar") {
	$item->set_nome($_GET["nome"]);
	$item->set_descricao($_GET["descricao"]);
	$item->set_preco($_GET["preco"]);
	$item->set_disponivel($_GET["disponivel"]);
	$item->set_categoria_id($_GET["categoria_id"]);
	echo $banco->salvar_item($item);
	exit;
}

if ($operacao == "excluir") {
	$resultado = $banco->excluir_item($item);
	if ($resultado == "ok") {
		echo excluir_foto($item->get_id());
	}
	else {
		echo $resultado;
	}
	exit;
}

if ($operacao == "foto_temp") {
	$nome_imagem_temp = '../img/itens/'.$_REQUEST["id"].'t.jpg';
	
	if (move_uploaded_file($_FILES["arquivo_imagem"]["tmp_name"], $nome_imagem_temp)) {
		$imagem = new SimpleImage();
		$imagem->load($nome_imagem_temp);
		if ($imagem->getWidth() > $imagem->getHeight()) {
			$maior = "largura";
		}
		else {
			$maior = "altura";
		}
		if ($maior == "largura" && $imagem->getWidth() > 400) {
			$imagem->resizeToWidth(400);
			$imagem->save($nome_imagem_temp);
		}
		elseif ($maior == "altura" && $imagem->getHeight() > 400) {
			$imagem->resizeToHeight(300);
			$imagem->save($nome_imagem_temp);
		}
		echo $nome_imagem_temp;
		exit;
	}
	else {
		echo "Erro";
		exit;
	}
}

if ($operacao == "salvar_foto") {
	$nome_temp = '../img/itens/'.$_REQUEST["id"].'t.jpg';
	$nome_arquivo_imagem = '../img/itens/'.$_REQUEST["id"].'g.jpg';
	$nome_arquivo_thumb = '../img/itens/'.$_REQUEST["id"].'p.jpg';

	if (file_exists($nome_temp)) {
		$imagem = new SimpleImage();
		$imagem->load($nome_temp);
		if ($imagem->getWidth() > $imagem->getHeight()) {
			$maior = "largura";
		}
		else {
			$maior = "altura";
		}
		if ($maior == "largura" && $imagem->getWidth() > 350) {
			$imagem->resizeToWidth(350);
		}
		elseif ($maior == "altura" && $imagem->getHeight() > 350) {
			$imagem->resizeToHeight(350);
		}
		$imagem->save($nome_arquivo_imagem);
		$quadro = imagecreatetruecolor(80, 80);
		$preto = imagecolorallocate($quadro, 0, 0, 0);
		imagefill($quadro, 0, 0, $preto);
		if ($maior == "largura") {
			$imagem->resizeToWidth(80);
			$posicao_x = 0;
			$posicao_y = round((80 - $imagem->getHeight())/2);
		}
		elseif ($maior == "altura") {
			$imagem->resizeToHeight(80);
			$posicao_x = round((80 - $imagem->getWidth())/2);
			$posicao_y = 0;
		}
		imagecopy ($quadro, $imagem->image, $posicao_x, $posicao_y, 0, 0, $imagem->getWidth(), $imagem->getHeight());
		imagejpeg($quadro, $nome_arquivo_thumb, 80);
		unlink($nome_temp);
	}
	
	$bar = $banco->recupera_bar_pelo_item($_REQUEST["id"]);
	
	echo $banco->incrementar_versao_cardapio($bar->get_id());
	exit;
}

if ($operacao == "excluir_temp") {
	$nome_temp = '../img/itens/'.$_REQUEST["id"].'t.jpg';
	if (file_exists($nome_temp)) {
		unlink($nome_temp);
	}
	echo "ok";
	exit;
}

if ($operacao == "excluir_foto") {
	echo excluir_foto($_REQUEST["id"]);
	exit;
}

if ($operacao == "pegar_foto") {
	if (file_exists('../img/itens/'.$_REQUEST["id"].'g.jpg')) {
		$nome_arquivo_imagem = '../img/itens/'.$_REQUEST["id"].'g.jpg';
		$nome_arquivo_thumb = '../img/itens/'.$_REQUEST["id"].'p.jpg';
	}
	else {
		$nome_arquivo_imagem = '../img/itens/semfotog.jpg';
		$nome_arquivo_thumb = '../img/itens/semfotop.jpg';
	}
	$pegar_thumb = $_REQUEST["pegar_thumb"];
	if ($pegar_thumb) {
		echo $nome_arquivo_thumb;
		exit;
	}
	else {
		echo $nome_arquivo_imagem;
		exit;
	}
}
?>